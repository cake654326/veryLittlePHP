<?php //if ( ! defined('MVC_PATH')  ) exit('404');

class lib_excel extends cx_lib {
	var $aRule;
	var $objPHPExcel;
	var $_nowGroupKey;

	//--- libxl

	/* @$_locale 
	 * windows = cht ,chs ...
	 * linux or mac or unix = zh_TW.UTF-8 OR ....
	 **/
	var $sLocale = "cht" ; 

	/* @$_iconv
	 * is windows set  array('UTF-8' , 'BIG5' ) else set false
	 **/
	var $sIconv = array('UTF-8' , 'BIG5' );


	public function __construct(  ) {
		parent::__construct( null );
		//$this->mConn
		$this->mCore->loadLib("excel/PHPExcel",false);
		$this->objPHPExcel = new PHPExcel();
		$this->aRule = array();
		$this->_nowGroupKey = "default";

	}


	// ===== Input Function =======

	//PHPExcel
	public function load( $_file_path ,$importCells=null){
		// $objPHPExcel = PHPExcel_IOFactory::load($_FILES['empData']['tmp_name']);

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
			// $cellIterator->setIterateOnlyExistingCells(true);
			$cellIterator->setIterateOnlyExistingCells(false);//所有欄位
			$rowdata = array();
			foreach ($cellIterator as $i => $cell)
			{
				//echo "<br/>i:".$i." ,".  $importCells[$i] . " => " . $cell;
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

	//libxl
	public function libxlLoad( $_file_path ,$importCells=null){
		// $objPHPExcel = PHPExcel_IOFactory::load($_FILES['empData']['tmp_name']);
		// $obj = PHPExcel_IOFactory::load( $_file_path );

		$mBook = new ExcelBook();
		// $mBook->setLocale( $this->sLocale );
		

		$_title_io = true;
		if($importCells == null){
			//標題欄位名稱
			$_title_io = false;
		}
		
		// $objPHPExcel;
		try {
			$mBook->loadFile( $_file_path );
		    // $objPHPExcel = PHPExcel_IOFactory::load($_file_path);
		} catch (Exception $e) {
		    // die('Error loading file: ' . $e->getMessage());
		    return false;
		}

		$mSheet = $mBook->getSheet( );
		// $objWorksheet = $objPHPExcel->getActiveSheet();

		$data = array();
		for( $row_index = 0 , $e = $mSheet->lastRow(); $row_index < $e ;$row_index++ )
		{

  			$_row_data = $mSheet->readRow($row_index) ;
			$cellIterator = $mSheet->readRow($row_index) ;
			$rowdata = array();
			foreach ($cellIterator as $i => $cell)
			{
				// echo "i:" . $i . " :" . $cell . "<br/>";
				$cell_big5 = iconv("big5", "UTF-8//TRANSLIT//IGNORE", $cell );
				if($_title_io == true){
					if (isset($importCells[$i])) $rowdata[$importCells[$i]] = $cell_big5;
				}else{
					$rowdata[$i] = $cell_big5;
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


		//處理 IE 問題
		if ( 
			strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ) , "MSIE" ) !== FALSE
			or 
			strpos( strtoupper($_SERVER['HTTP_USER_AGENT']) , "TRIDENT" ) !== FALSE
			)
		{

			$_fileName = iconv('utf-8', 'big5', $_fileName);
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

	public function saveCSV(	 $_path 
								,$_fileName  
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


		//處理 IE 問題
		if ( 
			strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ) , "MSIE" ) !== FALSE
			or 
			strpos( strtoupper($_SERVER['HTTP_USER_AGENT']) , "TRIDENT" ) !== FALSE
			)
		{

			$_fileName = iconv('utf-8', 'big5', $_fileName);
		}

		$_str = $this->_outputCsv($_aRule,$_aData,$sep,$sepreplace,$outstream,$addtitles,$quote,$escquote,$replaceNewLine);
		
		return $this->write_file( $_path , $_str );


	}

	private function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE)
	{
		if ( ! $fp = @fopen($path, $mode))
		{
			return FALSE;
		}

		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);

		return TRUE;
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

	public function outputLibxl(
				$_fileName  ,
				$_aData, 
				$_aRule = false,
				$_Category = "report"
				){

		$mBook = new ExcelBook();
		$mBook->setLocale( $this->sLocale );
		$mSheet = $mBook->addSheet( 'report' );

		if($_aRule == false){
			$_aRule = $this->aRule[$this->_nowGroupKey];
		}

		$_x = 0;
		foreach ( (array)$_aRule as $_val ) {
			$_title = '';
			if( $this->sIconv != false ){
				$_title = $_val['title'];
				//mb_convert_encoding( $_title  , "BIG5" , "UTF-8"); 
				$_title = iconv("UTF-8", "big5//TRANSLIT//IGNORE", $_val['title']);
				//$_title =  iconv('UTF-8' , 'BIG5' , $_val['title'] );
			}

			$mSheet->write(0, $_x, $_title  );

			// $this->objPHPExcel->getActiveSheet()
			// 		->setCellValueByColumnAndRow( $_x , 1
			// 			, $_title , PHPExcel_Cell_DataType::TYPE_STRING );
			$_x++;
		}


		$_x = 0;
		$_y = 1;
		foreach ( (array)$_aData as $_d ) {
			foreach ( (array)$_aRule as $_key=> $_val ) {
				// $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $_x, $_y, $_d[$_key] ,PHPExcel_Cell_DataType::TYPE_STRING);
				$_data_val = '';
				if( $this->sIconv != false ){
				
					
					//600mb
					//$_data_val = mb_convert_encoding( $_d[$_key ] , "BIG5" , "UTF-8");
					
					$_data_val = iconv("UTF-8", "big5//TRANSLIT//IGNORE", $_d[$_key ] );
					
					//600mb
					//$_data_val =  iconv('UTF-8' , 'BIG5' , $_d[$_key] );
					
					//$_data_val =  $_d[$_key];
				}

				$mSheet->write($_y, $_x, $_data_val  );

				// $this->objPHPExcel->getActiveSheet()
				// ->getCellByColumnAndRow( $_x, $_y )
				// ->setValueExplicit( $_d[$_key], PHPExcel_Cell_DataType::TYPE_STRING );

				$_x++;
			}
			$_x = 0;
			$_y++;
		}

		$file = tempnam('/tmp', 'excel');
		$mBook->save($file);
		
		//處理 IE 問題
		if ( 
			strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ) , "MSIE" )
			or 
			strpos( strtoupper($_SERVER['HTTP_USER_AGENT']) , "TRIDENT" )
			){

			$_fileName = iconv('utf-8', 'big5', $_fileName);
			// exit();
		}

		header('Content-Type: application/vnd.ms-excel');  
		header('Content-Disposition: attachment;filename="'.$_fileName.'.xls"');  
		header('Cache-Control: max-age=0');   
		readfile($file);
		unlink($file);

	
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



		//處理 IE 問題
		if ( 
			strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ) , "MSIE" )
			or 
			strpos( strtoupper($_SERVER['HTTP_USER_AGENT']) , "TRIDENT" )
			){

			$_fileName = iconv('utf-8', 'big5', $_fileName);
			// exit();
		}

		// Redirect output to a client’s web browser (Excel5)
		header( 'Content-Type: application/vnd.ms-excel' );
		header( 'Content-Disposition: attachment;filename="'.$_fileName.'.xls"' );
		header( 'Cache-Control: max-age=0' );

		$objWriter = PHPExcel_IOFactory::createWriter( $this->objPHPExcel, 'Excel5' );
		$objWriter->save( 'php://output' );
		// exit;



	}

	//--- save JSON
	


}

