<?php
/**
 # 通用 設定檔案
 ***/
$Core->setConfig('CXDEBUG' ,true);

/**
 # MVC 模式 設定檔
 **/
//設定INDEX自動進入控制器入口
$Core->setConfig("baseController" , "/demo_file/demo/add/bbb?g1=a");

/**
 # 自定義 設定檔
 **/
$Core->setConfig('json_file' ,"../history/");
$Core->setConfig('json' ,".json");
$Core->setConfig('PAR' ,1.25);


  
?>