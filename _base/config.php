<?php

ini_set( 'session.gc_maxlifetime', 0 );

/**
 # 通用 設定檔案
 ***/
$Core->setConfig('CXDEBUG' ,true);

//-- 使用 _GET['__xDebug_key'] 強制開啟 debug 功能 ---
$Core->setConfig('CXDEBUG_KEY' ,"__xDebug_key");//get['__xDebug_key'] is  CXDEBUG_VAL
$Core->setConfig('CXDEBUG_VAL' ,"10f3dcfbe0b40b5d26926a01fe9132e5");//CXDEBUG_KEY val 
$Core->setConfig('CXDEBUG_IPs' , array('172.16.3','172.16.4','192.168.') );//強制開啟 debug 功能 限制進入的 ip
//------------------------------------

$Core->setConfig('WRITELOG' ,true);//是否開啟寫入 系統 log 檔
$Core->setConfig('404PAGELOG' ,true);
$Core->setConfig('LOGFILENAME' , "_log");//log 系統檔案路徑
$Core->setConfig('SYSLOGNAME' , "system");//log 系統檔案名稱

/**
 # MVC 模式 設定檔
 **/

$Core->setConfig("INDEX" , "index.php");

//設定INDEX自動進入控制器入口
$Core->setConfig("baseController" , "/cxsystem/main/show");
//index.php/cxsystem/main/show

//-- 框架控制台帳號密碼 --
$Core->setConfig("CXSYSTEM" , true ); //是否啟用 框架控制器
$Core->setConfig("root_pwd" , "admin");
$Core->setConfig("base_http_title" , "http://");
// -- [base_host] and [base_base_url] -- 若未設定，將會自動取得。
// $Core->setConfig("base_host" , "172.16.44.80");
// $Core->setConfig("base_base_url" , "172.16.44.80/120903/cx_core/veryLittlePHP/");

/**
 # DB 設定檔
 **/
//-- 啟用 DB 設定檔,會自動停用 opensql.php --
$Core->setConfig("CXSYSTEM" , true ); //是否啟用 框架控制器

$Core->setConfig("DB_Defaut" , array(
										"Drive"    => "mysql", 
										"Path"     => "", //DB defaut Path 資料庫路徑 access 用
										"Host"     => "", 
										"User"     => "", 
										"Password" => "",
										"Database" => ""
							) );

// $Core->setConfig("DB_Defaut" , array(
// 										"Drive"    => "ado_mssql", 
// 										"Path"     => "", //DB defaut Path 資料庫路徑 access 用
// 										"Host"     => "172.16.3.48", 
// 										"User"     => "sa", 
// 										"Password" => "seat",
// 										"Database" => "framework_db"
// 							) );


// $Core->setConfig("DB_Defaut" , array(
// 										"Drive"    => "access", 
// 										"Path"     => "../xxx.mdb", //DB defaut Path 資料庫路徑 access 用
// 										"Host"     => "", //no host
// 										"User"     => "", 
// 										"Password" => "",
// 										"Database" => ""
// 							) );


/**
 # 自定義 設定檔
 **/
$Core->setConfig('json_file' ,"../history/");
$Core->setConfig('json' ,".json");
$Core->setConfig('PAR' ,1.25);


  
?>