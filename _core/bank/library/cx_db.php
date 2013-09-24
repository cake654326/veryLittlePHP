<?php
/**
# cx_db.php
#@author      Cake X 
#@link
#@version     DB v1.2.3
#
# ADODB 常用以及簡化工具組
# --------------------------------------------------------
# 「History」
#
# 2012/10/24 AM  :   v1.1     : [cx] save()  重寫之前版本的SAVE , 無法相容 以前專案
#								 setTable
#
# 2012/11/06 AM  :   v1.1.1   : [cx] Execute()  簡化 execute 長度并且建立錯誤簡化 ADODB
# 2012/11/08 AM  :   v1.1.2   : [cx] sqlExec()  使 EXECUTE 回傳 this
#								 getArray() 簡化 Adodb  GetArray
#								 getCout()  取得資料數量 簡化 adodb 函數名
#
# 2012/11/14 AM  :   v1.1.3   : [cx] autoSave 簡化SAVE 采固定PK的方式
#							  	   checkTableArray 為save 增加自動化 欄位過濾
#
# 2012/11/15 AM  :   v1.1.4   : [cx] setTableData 設定 INSERT OR UPDATE 預設載入的 欄位名稱，將會自動檢查。
#
# 2012/11/29 AM  :   v1.1.5   : [cx] __construct = 增加 CORE DB 自動加載
#
# 2013/01/03 pm14:00 v1.1.6   : [cx] Execute = Add auto message to log file
#
# 2013/02/02                  : [cx] getArray( $eq ) 直接取得陣列KEY之VALUE，NULL = 得到全部，若無資料則回傳false
#
# 2013/02/21                  : [cx] 增加 getDescribe,setDrives($_sqlDrives='mysql') //or ado_mssql 設定載體函數（目的為了設定autoSave() 需要知道載體，否則會有資料庫語法不合之因素
#
# 2013/08/13         v1.2.1   : [cx]  autoSave ,save 增加 狀態限制(INSERT,UPDATE,AUTO)
#
# 2013/08/14         v1.2.3   : [cx]  修正getCount 函數名稱
									增加重構selectLimit() 函數
# 2013/09/02         v1.2.4-2 : [cx]  修正autoSave 函數相容 PDO

# 2013/09/24 Core v1.3.7(DB-v1.2.5)   : [cx]  insertMultiple($_table,$_datafield,$_data ) insert 多筆

# --------------------------------------------------------
#「Function」(常用)
#
#
#
**/

class cx_db {
	var $mCore;
	var $mConn;
	var $mTable;
	var $mWhere;
	var $mPk;
	var $mRs;
	var $mDrives = 'ado_mssql';//mysql ado_mssql mssql pdo_mssql
	var $mDrives_keyName = '';//mysql: Field ,mssql:COLUMN_NAME
	var $bAutoAddN = true;//即將廢除
	var $bAutoSetUseCX = true;//是否使用自身 autoSave 解析器

	public function __construct( $_conn = null ) {
		//parent::__construct();
		global $Core;
		$this->mCore = &$Core;
		if($_conn == null){
			if($Core){
				$_conn = $Core->getDB();
			}else{
				$_msg = "ERROR:[cx_db] don't have Core OR __construct( $_conn ) val adodb loading";
				exit;
			}
		}else{
			if($Core){
				if( is_string($_conn) ){
					$_db_name = ( $Core->config($_conn) == null)?$_conn: $Core->config($_conn);

					$_c = $Core->getDB($_db_name);

					if($_c != false){
						// echo "<br/>!false<br/>";
						$_conn = &$_c;
					}else{
						$_conn = $Core->getDB();
					}

				}else{
					$_conn = $Core->getDB();
				}
			}else{
				$_msg = "ERROR:[cx_db] don't have Core OR __construct( $_conn ) val adodb loading";
				exit;
			}
				
		}

		
		$this->mConn = &$_conn;
		// print_cx($_conn);exit();
	}
	public function setDrives($_sqlDrives='ado_mssql'){
		//or mysql ado_mssql
		$this->mDrives = $_sqlDrives;
	}

	public function clear() {
		$this->mConn = '';
		$this->mTable = '';
		$this->mWhere = '';
		$this->mPk = '';
		$this->mRs = '';
	}

	public function setTitle( $_str ) {
		$this->mConn->setCxTitle( $_str );
		return true;
	}

	public function setTable( $_table ) {
		$this->mTable = $_table;
		return $this;
	}

	public function getError() {
		return $this->mConn->ErrorMsg();
	}

	/**
	 * 取得資料數量 簡化 adodb 函數名
	 * */
	public function getCout() {
		// 函數名錯誤
		//echo "c:" . $this->mRs->RecordCount();
		if( !$this->mRs ) return false;

		return $this->mRs->RecordCount();
	}
	public function getCount() {
		//echo "c:" . $this->mRs->RecordCount();
		if( !$this->mRs ) return false;

		return $this->mRs->RecordCount();
	}

	/**
	 * 簡化 Adodb  GetArray
	 * **/
	public function getArray( $_eq = null) {
		//echo "run array()";
		$_cout = $this->getCount();

		if( !$_cout ) return false;
		if ( $_cout > 0 ) {
			$_mArray = $this->mRs->GetArray();
			if($_eq !== null){
				if(!isset( $_mArray[$_eq] ) )return false;
				return $_mArray[$_eq];
			}
			return $_mArray;
		}
		if ( $_cout == 0 ) return 0;

		return false;
	}

	/**
	 * 使 EXECUTE 回傳 this
	 ***/
	public function sqlExec($_sql,$_arr){
		$this->Execute($_sql,$_arr);
		//print_cx($this->mRs );
		return $this;
	}



	function qstr($s,$magic_quotes=false)
	{	
		if (!$magic_quotes) {
		
			if ($this->replaceQuote[0] == '\\'){
				// only since php 4.0.5
				$s = adodb_str_replace(array('\\',"\0"),array('\\\\',"\\\0"),$s);
				//$s = str_replace("\0","\\\0", str_replace('\\','\\\\',$s));
			}
			return  "'".str_replace("'",$this->replaceQuote,$s)."'";
		}
		
		// undo magic quotes for "
		$s = str_replace('\\"','"',$s);
		
		if ($this->replaceQuote == "\\'" || ini_get('magic_quotes_sybase'))  // ' already quoted, no need to change anything
			return "'$s'";
		else {// change \' to '' for sybase/mssql
			$s = str_replace('\\\\','\\',$s);
			return "'".str_replace("\\'",$this->replaceQuote,$s)."'";
		}
	}

	public function mssqlArrayFormatter( $inputarr = false){
		if( $inputarr ){
			foreach( $inputarr as $k => $v) {
				if (is_string($v)) {
					$len = strlen($v);
					if ($len == 0) $len = 1;
					
					if ($len > 4000 ) {
						// NVARCHAR is max 4000 chars. Let's use NTEXT
						$decl[ $k ] = "NTEXT";
					} else {
						$decl[ $k ] = "NVARCHAR($len)";
					}

					$params[ $k ] = "N". (strncmp($v,"'",1)==0? $v : $this->qstr($v));
				} else if (is_integer($v)) {
					$decl[ $k ] = "INT";
					$params[ $k ] = "".$v;
				} else if (is_float($v)) {
					$decl[ $k ] = "FLOAT";
					$params[ $k ] = "".$v;
				} else if (is_bool($v)) {
					$decl[ $k ] = "INT"; # Used INT just in case BIT in not supported on the user's MSSQL version. It will cast appropriately.
					$params[ $k ] = "".(($v)?'1':'0'); # True == 1 in MSSQL BIT fields and acceptable for storing logical true in an int field
				} else {
					$decl[ $k ] = "CHAR"; # Used char because a type is required even when the value is to be NULL.
					$params[ $k ] = "NULL";
					}
				$i += 1;
			}
		}
		
			
// echo "<br/>p:";
// print_r($params);
// echo "<br/>d:";
// print_r($decl);
// echo "<br/>".$decl;

		return $params;
	}

	/**
	 * 簡化 execute 長度
	 * 并且建立錯誤簡化 ADODB
	 * **/
	public function Execute( $_sql, $inputarr = false ) {
		$params = array();
		$decl = array();
		if($inputarr){
			$params = $inputarr;
		}

		$this->mRs  = $this->mConn->Execute( $_sql , $params );
		if ( !$this->mRs  ) {
			$_error = $this->getError();
			$_msg = "CX_DB Execute Error:" . $_error ;

			//show log
			$this->mCore->log( $_msg , "DB_".date( 'YmdH' ).".txt" ); //bug
			$this->mCore->systemLog( $_msg  );

			$this->setTitle( $_msg );
			return false;
		}
		return $this->mRs;
	}

	public function selectPkCount( $_table, $_pk_val, $_pk_key = "id" ) {
		$this->mConn->setCxTitle( "cx_db selectPK " );
		$_sql = "Select ". $_pk_key . " from " . $_table . " Where " . $_pk_key . "=?";
		$_rs = $this->Execute( $_sql, array( $_pk_val ) );
		if ( !$_rs )return false;
		return $_rs->RecordCount();

	}
	/**
	 * string where
	 * **/
	public function updateWhere( $_w ) {
		$this->mWhere = $_w;
		return $this;
	}

	public function setPk( $_pk ) {
		$this->mPk = $_pk;
		return $this;
	}

	/**
	 * 簡化 SAVE 函數 ，采用固定PK 和 WHERE 固定為pK數值為條件。
	 ***/
	public function autoSave($_table,$_pk,$_data,$_set_type = 'AUTO'){
		$this->mConn->setCxTitle( "cx_db autoSave: " .$_table );
		$_rs = $this->setTable( $_table )
					->setPk( $_pk )
					->updateWhere( $_pk."='" . $_data[$_pk] . "'")
					->save( $_data , $_data[$_pk],$_set_type);
		return $_rs;
	}

//mDrives
	public function getDescribe( $_table_name = null ){
		/*
			mssql : exec sp_columns $tablename;
			mysql : DESCRIBE $tablename;
		*/
		$_table_name = ($_table_name === null )?$this->mTable:$_table_name;
		$_sql = '';
		switch($this->mDrives){
			case "mysql":
			case "pdo_mysql":
			case "odbc_pdo_mysql":
				$_sql = "DESCRIBE " . $_table_name;
				$this->mDrives_keyName =  'Field' ;

			break;
			case "mssql":
			case "ado_mssql":
			case "pdo_mssql":
			case "odbc_pdo_mssql":
			case "cx_mssql":
			default:
				$_sql = "exec sp_columns " . $_table_name;
				$this->mDrives_keyName = 'COLUMN_NAME';
			
			break;
		}
		$this->mConn->setCxTitle( "cx_db getDescribe: " .$_table_name);
		
		return $this->sqlExec($_sql,array())->getArray();

	}

	public function checkTableArray($_table,$_input_arr){
		// 自動化 ARRAY檢查
		
		//exec sp_columns Tb_EndUser
		$_tb = $this->getDescribe($_table);
// print_cx($_tb);
		$this->mConn->setCxTitle( "cx_db checkTableArray: " .$_table);
		// $_sql = "exec sp_columns " . $_table;
		// $_tb = $this->sqlExec($_sql,array())->getArray();

		$_output_arr = array();

		foreach((array)$_tb as $key => $val){
			if(array_key_exists($val[$this->mDrives_keyName],$_input_arr)){
				$_output_arr[$val[$this->mDrives_keyName]] = $_input_arr[$val[$this->mDrives_keyName]];
			}
		}

		if(count($_output_arr) == 0){
			// error_log("count($_output_arr) is 0", 3, "../_log/db.log");
			return false;
		}

		// print_cx($_output_arr);
		// exit(0);
		return $_output_arr;
	}



	/*
	強制設定
	$_type: AUTO , INSERT , UPDATE
	*/
	public function save( $_input_arr , $_pk_val ,$_set_type = 'AUTO' ) {
		$_table = $this->mTable;
		$_where = $this->mWhere;
		$_pk_key = $this->mPk;
		$kSave_type = '';//設定儲存狀態 UPDATE or INSERT

		// 自動化 ARRAY檢查
		$_inputarr = $this->checkTableArray($_table,$_input_arr);
		if(!$_inputarr)return false;

		$this->mConn->setCxTitle( "cx_db save " );
		
		if( $_pk_val == null){
			//insert
			$kSave_type = "INSERT";
		}else{
			if ( $this->selectPkCount( $_table, $_pk_val, $_pk_key ) >0 )  {
				//updata
				$kSave_type = "UPDATE";
			}else {
				//insert
				$kSave_type = "INSERT";
			}
		}

		if($_set_type != 'AUTO'){
			if($_set_type != $kSave_type){
				$kSave_type = '';
			}
		}

		// //-- 檢查 是否加N -- 廢除
		// if ( ($this->mDrives == 'ado_mssql' or $this->mDrives == 'mssql' ) and $this->bAutoAddN ){
		// 	//$_inputarr = $this->mssqlArrayFormatter($_inputarr);
		// }
		// print_cx($_inputarr);

		switch($kSave_type){
			case "UPDATE":

				if ( array_key_exists( $_pk_key, $_inputarr ) ) {
					unset( $_inputarr[$_pk_key] );
					if(count($_inputarr) == 0)return false;
				}

			//UPDATE temp SET DATA=?, TIME=?, NAME=? WHERE id=? 
				if($this->mDrives == 'pdo_mssql' or $this->bAutoSetUseCX == true){
					$_update_set = array();
					$_update_data = array();
					foreach((array)$_inputarr  as $_input_key => $_input_val){
						$_update_set[] = $_input_key."=? ";
						$_update_data[] = $_input_val;
					}
					$_auto_sql = " UPDATE " . $_table . " SET " .implode(",", $_update_set) . " WHERE " . $_where ;
					$_rs = $this->Execute( $_auto_sql , $_update_data );

				}else{
					$_rs = $this->mConn->AutoExecute( $_table, $_inputarr, "UPDATE", $_where );
				}
				
			break;
			
			case "INSERT":
			if($this->mDrives == 'pdo_mssql' or $this->bAutoSetUseCX == true){
				$_insert_set = array();
				$_insert_val = array();
				$_insert_data = array();
				foreach((array)$_inputarr  as $_input_key => $_input_val){
						$_insert_set[] = $_input_key;
						$_insert_val[] = "?";
						$_insert_data[] = $_input_val;
				}

				$_auto_sql = " INSERT INTO " . $_table . "( " .  implode(",", $_insert_set)  ." )
								 VALUES ( " . implode(",",$_insert_val) ." ) ";
				$_rs = $this->Execute( $_auto_sql , $_insert_data );
			}else{
				//INSERT INTO temp ( DATA, TIME, NAME ) VALUES ( ?, ?, ? )
				$_rs = $this->mConn->AutoExecute( $_table, $_inputarr, "INSERT" );
			}
			
			break;
			default:
				$_rs = false;
			break;
			
		}
		
		if ( !$_rs )return false;
		return true;

	}

	/***
	 * selectLimit($_sql , $_numlist, $_offset )
	 * 分頁工具
	 * @_sql 
	 * @_numlist  一頁顯示筆數
	 * @_offset   筆數開頭
	 */
	public function selectLimit($_sql , $_numlist, $_offset ,$_arr = array() ){
		$this->mRs  = $this->mConn->SelectLimit( $_sql, $_numlist, $_offset ,$_arr );
		if( !$this->mRs ) return false;
		return $this;
	}


	/***
	 * # insert多筆資料
	 * insertMultiple($_table,$_datafield,$_data )  
	 * 
	 * @table 表
	 * @datafield 欄位名稱
	 * @data 資料串列
	 *
	 ***/
	public function insertMultiple($_table,$_datafield,$_data ){
		$insert_values  = array();
		$question_marks = array();
		foreach($_data as $d){
			$question_marks[] = '('  . $this->placeholders('?', sizeof($d)) . ')';
			$insert_values = array_merge($insert_values, array_values($d));
		}
		$sql = " INSERT INTO " .
				$_table . " (" . implode(",", array_keys($_datafield) ) . ") VALUES " . 
				implode(',', $question_marks);
		// $sql = " INSERT INTO " .
		// 		$_table . " (" . implode(",", $_datafield ) . ") VALUES " . 
		// 		implode(',', $question_marks);


		// echo $sql;
		// print_cx($insert_values);
		return $this->Execute( $sql , $insert_values );
		// exit();

	}

	private function placeholders($text, $count=0, $separator=","){
	    $result = array();
	    if($count > 0){
	        for($x=0; $x<$count; $x++){
	            $result[] = $text;
	        }
	    }

	    return implode($separator, $result);
	}

}

?>
