### EXCEL PHP 

### Library
1. lib_excel.php
2. 依賴 Excel\PHPExcel

### History
* ***time: 2013-08/19*** version: 0.1

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

### 來源範例
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




### 用法

``` php Code

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
	//載入資料
	$this->mCore->mExcel->outputCsv($_file_name ,$_reportData);


```
