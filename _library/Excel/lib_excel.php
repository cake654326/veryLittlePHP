<?php //if ( ! defined('MVC_PATH')  ) exit('404');

class lib_excel extends cx_lib {
	var $aRule;
	var $objPHPExcel;
	var $_nowGroupKey;
	public function __construct(  ) {
		parent::__construct( null );
		//$this->mConn
		$this->mCore->loadLib("excel/PHPExcel",false);
		$this->objPHPExcel = new PHPExcel();
		$this->aRule = array();
		$this->_nowGroupKey = "default";

	}


	// ===== Input Function =======
	public function load( $_file_path ,$importCells=null){
		// $objPHPExcel = PHPExcel_IOFactory::load($_FILES['empData']['tmp_name']);
		$obj = PHPExcel_IOFactory::load( $_file_path );

		$_title_io = true;
		if($importCells == null){
			//標題欄位名稱
			$_title_io = false;
		}
		
		$objPHPExcel;
		try {
		    $objPHPExcel = PHPExcel_IOFactory::load($_file_path);
		} catch (Exception $e) {
		    // die('Error loading file: ' . $e->getMessage());
		    return false;
		}

		$objWorksheet = $objPHPExcel->getActiveSheet();

		$data = array();
		foreach ($objWorksheet->getRowIterator() as $row)
		{
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(true);
			$rowdata = array();
			foreach ($cellIterator as $i => $cell)
			{
				if($_title_io == true){
					if (isset($importCells[$i])) $rowdata[$importCells[$i]] = $cell->getValue();
				}else{
					$rowdata[$i] = $cell->getValue();
				}
				
			}
		    $data[] = $rowdata;
		}

		return $data;

	}


	// ===== Output Function =======

	public function setGroupKey( $_group ){
		$this->_nowGroupKey = $_group;
		return $this;
	}

	public function setRule( $_pKey,$_title ,$_groupKey = false){
		if($_groupKey!=false){
			$this->_nowGroupKey= $_groupKey;
		}
		$this->aRule[$this->_nowGroupKey][$_pKey]['title'] = $_title;

		return $this;
	}

	public function outputExcel($_fileName  ,$_aData, $_aRule = false,$_Category = "report"){
		if($_aRule == false){
			$_aRule = $this->aRule[$this->_nowGroupKey];
		}

		$this->_outputExcel($_fileName , $_aRule, $_aData ,$_Category);
		return ;
	}

	public function outputCsv(	 $_fileName  
								,$_aData
								,$_aRule = false
								,$sep = ','
								,$sepreplace = '，'
								,$addtitles=true
								,$quote = '"'
								,$escquote = '"'
								,$replaceNewLine = ' ')
	{
		if($_aRule == false){
			$_aRule = $this->aRule[$this->_nowGroupKey];
		}
		// $fp = fopen('php://stdout','wb');
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header('Content-Disposition: attachment; filename="'.$_fileName.'.csv"');
		$outstream = fopen("php://output", 'wb+');
		// $fp=false;
		$_str = $this->_outputCsv($_aRule,$_aData,$sep,$sepreplace,$outstream,$addtitles,$quote,$escquote,$replaceNewLine);
		fclose($outstream);
		return ;
	}

	private function _outputCsv($_aRule,$rs,$sep,$sepreplace,$fp=false,$addtitles=true,$quote = '"',$escquote = '"',$replaceNewLine = ' '){
		if (!$rs) return '';
		$NEWLINE = "\r\n";
		$BUFLINES = 100;
		$escquotequote = $escquote.$quote;
		$s = '';
		$elements = array();

		foreach($_aRule as $_key => $_val){
			$v = $_val['title'];
			if($escquote) $v = str_replace($quote,$escquotequote,$_val['title']);
			$v = strip_tags(str_replace("\n", $replaceNewLine, str_replace("\r\n",$replaceNewLine,str_replace($sep,$sepreplace,$v))));
			$elements[] = $v;
		}
		$s .= implode($sep, $elements).$NEWLINE;
		$line = 0;
		$max = count($rs);

		// echo $s;

		foreach($rs as $_rsKey => $_rsVal ){
			$elements = array();
			$i = 0;
			foreach($_aRule as $_key => $_val){
				$v = $_rsVal[$_key];
				if ($escquote) $v = str_replace($quote,$escquotequote,trim($v));
				$v = strip_tags(str_replace("\n", $replaceNewLine, str_replace("\r\n",$replaceNewLine,str_replace($sep,$sepreplace,$v))));
				if (strpos($v,$sep) !== false || strpos($v,$quote) !== false) $elements[] = "$quote$v$quote";
				else $elements[] = $v;
			}
			$s .= implode($sep, $elements).$NEWLINE;
			$line += 1;
			if ($fp && ($line % $BUFLINES) == 0) {
				if ($fp === true) echo $s;
				else fwrite($fp,$s);
				$s = '';
			}

		}
		
		if ($fp) {
				if ($fp === true) echo $s;
				else fwrite($fp,$s);
				$s = '';
			}
		
		return $s;

	}

	private function _outputExcel($_fileName , $_aRule, $_aData ,$_Category = "report"){
		// echo $_fileName;
		$this->mCore->loadLib("excel/PHPExcel",false);
		$this->objPHPExcel = new PHPExcel();
		// Set document properties
		$this->objPHPExcel->getProperties()->setCreator( "Maarten Balliauw" )
		->setLastModifiedBy( "Maarten Balliauw" )
		->setTitle( "Office 2007 XLSX Test Document" )
		->setSubject( "Office 2007 XLSX Test Document" )
		->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes." )
		->setKeywords( "office 2007 openxml php" )
		->setCategory( "report" );

		$_x = 0;
		foreach ( $_aRule as $_val ) {
			$this->objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $_x , 1
						, $_val['title'] , PHPExcel_Cell_DataType::TYPE_STRING );
			$_x++;
		}

		$_x = 0;
		$_y = 2;
		foreach ( (array)$_aData as $_d ) {
			foreach ( (array)$_aRule as $_key=> $_val ) {
				// $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $_x, $_y, $_d[$_key] ,PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()
				->getCellByColumnAndRow( $_x, $_y )
				->setValueExplicit( $_d[$_key], PHPExcel_Cell_DataType::TYPE_STRING );
				$_x++;
			}
			$_x = 0;
			$_y++;
		}

		$this->objPHPExcel->getActiveSheet()->setTitle( 'report' );


		$this->objPHPExcel->setActiveSheetIndex( 0 );


		// Redirect output to a client’s web browser (Excel5)
		header( 'Content-Type: application/vnd.ms-excel' );
		header( 'Content-Disposition: attachment;filename="'.$_fileName.'.xls"' );
		header( 'Cache-Control: max-age=0' );

		$objWriter = PHPExcel_IOFactory::createWriter( $this->objPHPExcel, 'Excel5' );
		$objWriter->save( 'php://output' );
		// exit;



	}


}