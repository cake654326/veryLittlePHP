<?php
/**
 # 通用 設定檔案
 ***/
$Core->setConfig('CXDEBUG' ,true);

/**
 # MVC 模式 設定檔
 **/

$Core->setConfig("INDEX" , "index.php");

//設定INDEX自動進入控制器入口
$Core->setConfig("baseController" , "/demo_file/demo/add/bbb?g1=a");

//-- 框架控制台帳號密碼 --
$Core->setConfig("CXSYSTEM" , true ); //是否啟用 框架控制器
$Core->setConfig("root_pwd" , "admin");
$Core->setConfig("base_http_title" , "http://");
// -- [base_host] and [base_base_url] -- 若未設定，將會自動取得。
// $Core->setConfig("base_host" , "172.16.44.80");
// $Core->setConfig("base_base_url" , "172.16.44.80/120903/cx_core/veryLittlePHP/");

/**
 # 自定義 設定檔
 **/
$Core->setConfig('json_file' ,"../history/");
$Core->setConfig('json' ,".json");
$Core->setConfig('PAR' ,1.25);


  
?>