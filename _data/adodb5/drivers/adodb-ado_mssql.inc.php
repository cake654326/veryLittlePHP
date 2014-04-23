<?php
/* 
V5.18 3 Sep 2012  (c) 2000-2012 John Lim (jlim#natsoft.com). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence. 
Set tabs to 4 for best viewing.
  
  Latest version is available at http://adodb.sourceforge.net
  
  Microsoft SQL Server ADO data driver. Requires ADO and MSSQL client. 
  Works only on MS Windows.
  
  Warning: Some versions of PHP (esp PHP4) leak memory when ADO/COM is used. 
  Please check http://bugs.php.net/ for more info.
*/

// security - hide paths
if (!defined('ADODB_DIR')) die();

if (!defined('_ADODB_ADO_LAYER')) {
	if (PHP_VERSION >= 5) include(ADODB_DIR."/drivers/adodb-ado5.inc.php");
	else include(ADODB_DIR."/drivers/adodb-ado.inc.php");
}


class  ADODB_ado_mssql extends ADODB_ado {        
	var $databaseType = 'ado_mssql';
	var $hasTop = 'top';
	var $hasInsertID = true;
	var $sysDate = 'convert(datetime,convert(char,GetDate(),102),102)';
	var $sysTimeStamp = 'GetDate()';
	var $leftOuter = '*=';
	var $rightOuter = '=*';
	var $ansiOuter = true; // for mssql7 or later
	var $substr = "substring";
	var $length = 'len';
	var $_dropSeqSQL = "drop table %s";
	
	//var $_inTransaction = 1; // always open recordsets, so no transaction problems.
	
	function ADODB_ado_mssql()
	{
	        $this->ADODB_ado();
	}
	
	function _insertid()
	{
	        return $this->GetOne('select SCOPE_IDENTITY()');
	}
	
	function _affectedrows()
	{
	        return $this->GetOne('select @@rowcount');
	}
	
	function SetTransactionMode( $transaction_mode ) 
	{
		$this->_transmode  = $transaction_mode;
		if (empty($transaction_mode)) {
			$this->Execute('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
			return;
		}
		if (!stristr($transaction_mode,'isolation')) $transaction_mode = 'ISOLATION LEVEL '.$transaction_mode;
		$this->Execute("SET TRANSACTION ".$transaction_mode);
	}
	
	function qstr($s,$magic_quotes=false , $is_string = false )
	{
		// $s = ADOConnection::qstr($s, $magic_quotes );
		$s = $this->_cx_qstr( $s, $magic_quotes ,$is_string );
		return str_replace("\0", "\\\\000", $s);
		
		 
	}


	function _cx_qstr($s,$magic_quotes=false , $is_string = false )
	{	
		$_tag ='';
		if( $is_string ){
			$_tag = 'N';
		}

		if (!$magic_quotes) {
		
			if ($this->replaceQuote[0] == '\\'){
				// only since php 4.0.5
				$s = adodb_str_replace(array('\\',"\0"),array('\\\\',"\\\0"),$s);
				//$s = str_replace("\0","\\\0", str_replace('\\','\\\\',$s));
			}
			return  $_tag . "'".str_replace("'",$this->replaceQuote,$s)."'";
		}
		
		// undo magic quotes for "
		$s = str_replace('\\"','"',$s);
// echo "<br/>" . $s;
		if ($this->replaceQuote == "\\'" || ini_get('magic_quotes_sybase'))  // ' already quoted, no need to change anything
			return $_tag . "'$s'";
		else {// change \' to '' for sybase/mssql
			$s = str_replace('\\\\','\\',$s);
			return $_tag . "'".str_replace("\\'",$this->replaceQuote,$s)."'";
		}


	}

	function Execute($sql,$inputarr=false) 
	{

		// print_cx($inputarr);
		if ($this->fnExecute) {
			$fn = $this->fnExecute;
			$ret = $fn($this,$sql,$inputarr);
			if (isset($ret)) return $ret;
		}
		if ($inputarr) {
			if (!is_array($inputarr)) $inputarr = array($inputarr);
			
			$element0 = reset($inputarr);
			# is_object check because oci8 descriptors can be passed in
			$array_2d = $this->bulkBind && is_array($element0) && !is_object(reset($element0));
		
			//remove extra memory copy of input -mikefedyk
			unset($element0);
			
			if (!is_array($sql) && !$this->_bindInputArray) {
				$sqlarr = explode('?',$sql);
				$nparams = sizeof($sqlarr)-1;
				if (!$array_2d) $inputarr = array($inputarr);
	
				foreach($inputarr as $arr) {
					$sql = ''; $i = 0;
					//Use each() instead of foreach to reduce memory usage -mikefedyk
					while(list(, $v) = each($arr)) {
						$sql .= $sqlarr[$i];
						// from Ron Baldwin <ron.baldwin#sourceprose.com>
						// Only quote string types	
						$typ = gettype($v);
						if ($typ == 'string'){
							//New memory copy of input created here -mikefedyk
							// $qqq = $this->qstr($v);
							// echo "<br/>" . $qqq;
							$sql .= $this->qstr($v , false , true);
						}else if ($typ == 'double')
							$sql .= str_replace(',','.',$v); // locales fix so 1.1 does not get converted to 1,1
						else if ($typ == 'boolean')
							$sql .= $v ? $this->true : $this->false;
						else if ($typ == 'object') {
							if (method_exists($v, '__toString')) $sql .= $this->qstr($v->__toString());
							else $sql .= $this->qstr((string) $v);
						} else if ($v === null)
							$sql .= 'NULL';
						else
							$sql .= $v;
						$i += 1;
						
						if ($i == $nparams) break;
					} // while
					if (isset($sqlarr[$i])) {
						$sql .= $sqlarr[$i];
						if ($i+1 != sizeof($sqlarr)) $this->outp_throw( "Input Array does not match ?: ".htmlspecialchars($sql),'Execute');
					} else if ($i != sizeof($sqlarr))	
						$this->outp_throw( "Input array does not match ?: ".htmlspecialchars($sql),'Execute');
		
					$ret = $this->_Execute($sql);
					if (!$ret) return $ret;
				}	
			} else {
				if ($array_2d) {
					if (is_string($sql))
						$stmt = $this->Prepare($sql);
					else
						$stmt = $sql;
					
					foreach($inputarr as $arr) {
						$ret = $this->_Execute($stmt,$arr);
						if (!$ret) return $ret;
					}
				} else {
					$ret = $this->_Execute($sql,$inputarr);
				}
			}
		} else {
			$ret = $this->_Execute($sql,false);
		}

		return $ret;
	}
	
	
	function MetaColumns($table, $normalize=true)
	{
        $table = strtoupper($table);
        $arr= array();
        $dbc = $this->_connectionID;
        
        $osoptions = array();
        $osoptions[0] = null;
        $osoptions[1] = null;
        $osoptions[2] = $table;
        $osoptions[3] = null;
        
        $adors=@$dbc->OpenSchema(4, $osoptions);//tables

        if ($adors){
                while (!$adors->EOF){
                        $fld = new ADOFieldObject();
                        $c = $adors->Fields(3);
                        $fld->name = $c->Value;
                        $fld->type = 'CHAR'; // cannot discover type in ADO!
                        $fld->max_length = -1;
                        $arr[strtoupper($fld->name)]=$fld;
        
                        $adors->MoveNext();
                }
                $adors->Close();
        }
        $false = false;
		return empty($arr) ? $false : $arr;
	}
	
	function CreateSequence($seq='adodbseq',$start=1)
	{
		
		$this->Execute('BEGIN TRANSACTION adodbseq');
		$start -= 1;
		$this->Execute("create table $seq (id float(53))");
		$ok = $this->Execute("insert into $seq with (tablock,holdlock) values($start)");
		if (!$ok) {
				$this->Execute('ROLLBACK TRANSACTION adodbseq');
				return false;
		}
		$this->Execute('COMMIT TRANSACTION adodbseq'); 
		return true;
	}

	function GenID($seq='adodbseq',$start=1)
	{
		//$this->debug=1;
		$this->Execute('BEGIN TRANSACTION adodbseq');
		$ok = $this->Execute("update $seq with (tablock,holdlock) set id = id + 1");
		if (!$ok) {
			$this->Execute("create table $seq (id float(53))");
			$ok = $this->Execute("insert into $seq with (tablock,holdlock) values($start)");
			if (!$ok) {
				$this->Execute('ROLLBACK TRANSACTION adodbseq');
				return false;
			}
			$this->Execute('COMMIT TRANSACTION adodbseq'); 
			return $start;
		}
		$num = $this->GetOne("select id from $seq");
		$this->Execute('COMMIT TRANSACTION adodbseq'); 
		return $num;
		
		// in old implementation, pre 1.90, we returned GUID...
		//return $this->GetOne("SELECT CONVERT(varchar(255), NEWID()) AS 'Char'");
	}
	
	} // end class 
	
	class  ADORecordSet_ado_mssql extends ADORecordSet_ado {        
	
	var $databaseType = 'ado_mssql';
	
	function ADORecordSet_ado_mssql($id,$mode=false)
	{
	        return $this->ADORecordSet_ado($id,$mode);
	}



}