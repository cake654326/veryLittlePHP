### EXCEL PHP 

### 依附版本
* CORE v1.3.6 => version: 0.1
* CORE v1.3.7 => version: 0.2
* CORE v1.4.3 => version: 0.3
* CORE v1.4.4 => version: 0.4

### Library
1. lib_excel.php
2. 依賴 Excel\PHPExcel

### History
* ***time: 2013-08/19*** version: 0.1
* ***time: 2013-09/24*** version: 0.2
	* ***增加讀取excel函數***
* ***time: 2013-12/04*** version: 0.3
	* ***增加 支援 Libxl output ***
* ***time: 2013-12/24*** version: 0.4
	* ***增加 支援 Libxl load ***

### 用途
1. 簡化建立excel csv 功能
2. 整合ADODB 輸出直接使用

### function

1. setGroupKey( $_group_key )
	-  @_group_key : 設定資料群組
	-  @return : @this
2. setRule( $_data_key , $_title_name )
	- @_data_key : 來源陣列key值
	- @_title_name: 所代表的 表頭名稱(excel 表頭顯示用)
3. outputExcel( $_file_name , $_reportData )
	- @_file_name : 下載檔名
	- @_reportData : 資料來源
4. outputCsv
5. outputLibxl 

### DB讀出資料 範例 $_reportData
```php
	Array
	(
	    [0] => Array
	        (
	            [uData_u_id] => 3008
	            [user_u_name_big5] => SUMMIT SCH
	            [user_s_contract] => CAKE
	            [user_s_c_title] => PM
	        )
	
	    [1] => Array
	        (
	            [uData_u_id] => 3016
	            [user_u_name_big5] => 
	            [user_s_contract] => JEFF
	            [user_s_c_title] => RD 
	        )
....

```

### 用法

```php

	//載入 library 
	$this->mCore->loadLib("Excel/lib_excel",true,"mExcel");
	
	//設定資料 表頭 以及 來源 KEY值
	//A1 為 群組key
	$this->mCore->mExcel->setGroupKey("A1")
								->setRule("uData_u_id","校代碼")
								->setRule("user_u_name_big5","校名")
								->setRule("user_s_contract","承辦人 姓名")
								->setRule("user_s_c_title","承辦人 職稱");
	

	$this->mCore->mExcel->setGroupKey("A2")
								->setRule("uData_u_id","校代碼")
								->setRule("user_u_name_big5","校名")
								->setRule("uView_5","試場 筆數")
								->setRule("uView_1","級別1 筆數")
								->setRule("uView_2","級別2 筆數")
								->setRule("uView_3","級別3 筆數");

	//輸出下載 
	
	//設定下載的群組
	$this->mCore->mExcel->setGroupKey("A1");

	// ---- 載入資料並下載 ---- 
	//to csv
	$this->mCore->mExcel->outputCsv($_file_name ,$_reportData);

	//to excel
	$this->mCore->mExcel->outputExcel($_file_name ,$_reportData);

``` 


<hr/>

### 讀取EXCEL
**load($uploadpath , $importCells);**
* @uploadpath EXCEL 檔案位置
* @importCells EXCEL 欄位依照順序之自定名稱（若沒填則以數字作為KEY)

##### ***importCells 範例***
```php
//需要依照 excel欄位 順序
	$importCells = array(
	'Sch_Code',	
	'GClass',	
	'AcntTP',	
	'PWD',	
	'REM',	
	'Acnt_Name'
	);
```

##### ***用法***
```php
$this->mCore->loadLib("Excel/lib_excel",true,"mExcel");

$uploaddir =  realpath( "./_public/excel_file" );

$uploadpath = $uploaddir . "\\" . "abc.xls" ;
$importCells = null;//可為空
$data = $this->mCore->mExcel->load($uploadpath , $importCells);
```

##### ***完整範例 包含讀取上傳***
```php
$_exe = strtolower( array_pop( explode( '.', $_FILES['upload_file']['name'] ) )  );
//echo "exe:" . $_exe;
if( preg_match("/\.(xls|xlsx)$/i" , strtolower( $_FILES['upload_file']['name'] ) ) ){
	echo "<br/>". $_FILES['upload_file']['name'] ." is excel!";

	$this->mCore->loadLib("Excel/lib_excel",true,"mExcel");

	$_uploadFileName  = date ("YmdHis")  ;
	$_rPath = './_public/upload_excel';
	$uploaddir =  realpath( $_rPath );
	$uploadfile = $uploaddir . "\\" . $_uploadFileName  ;
	$uploadpath =  $uploadfile . "." . $_exe;
	// if ( move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadpath ) ){

	try {
	   copy( $_FILES['upload_file']['tmp_name'] , $uploadpath );
	   // move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadpath )
	} catch (Exception $e) {
	    die('Error copy file: ' . $e->getMessage());
	}


	//設定 title
	$importCells = array(
	'Sch_Code',	
	'GClass',	
	'AcntTP',	
	'PWD',	
	'REM',	
	'Acnt_Name'
	);

	//使用PHPExcel
	$data = $this->mCore->mExcel->load($uploadpath , $importCells);

	//使用libxl
	$this->mCore->mExcel->libxlLoad($uploadpath , $importCells);


}
```

