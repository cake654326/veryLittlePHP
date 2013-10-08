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
 
// -- 啟用 DB 設定檔,啟用會自動停用 _data/opensql.php 及 _web_set.php
$Core->setConfig("CXDATABASE_ENABLE" , true ); //是否啟用 DB 設定檔
$__config_my_database = array();

//YOUR_DB_ALIAS_NAME 是對應 model 內部設定的名稱
$Core->setConfig('YOUR_DB_ALIAS_NAME' ,'YOUR_CONFIG_DB_NAME');//設定資料庫別名
$__config_my_database['YOUR_CONFIG_DB_NAME'] = 
							array(
										"Auto"            => false,//是否啟用自動連線
										"Drive"    => "ado_mssql", //pdo_mssql
										"Path"     => "", //DB defaut Path 資料庫路徑 access 用
										"Host"     => "127.0.0.1", 
										"User"     => "root", 
										"Password" => "Passwd",
										"Database" => "DB_NAME"

							);

$Core->setConfig('YOUR_DB_ALIAS_NAME2' ,'YOUR_CONFIG_DB_NAME2');//設定資料庫別名
$__config_my_database['YOUR_CONFIG_DB_NAME2'] = 
							array(
										"Auto"            => false,//是否啟用自動連線
										"Drive"    => "mysql", //pdo_mysql
										"Path"     => "", //DB defaut Path 資料庫路徑 access 用
										"Host"     => "127.0.0.2", 
										"User"     => "root", 
										"Password" => "Passwd",
										"Database" => "DB_NAME"
							);
$Core->setConfig("CXDATABASE"  ,$__config_my_database );


/**
 # 自定義 設定檔
 **/
$Core->setConfig('json_file' ,"../history/");



  
?>