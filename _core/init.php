<?php
/**
# init.php 
# CAKE X
# 預設載入功能
# --------------------------------------------------------
# 2012/11            v1.1.0 : [cx]相容 傳統寫法專案(v1.1)，不相容 舊版 v1.0框架專案
# 2012/12/07         v1.2.0 : [cx]增加 MVC 控制器模式
# 2012/12/22 AM09:15 v1.2.0 : [cx]修正 baseController
# 2013/02/22 AM10:45 v1.2.0 : [cx]add old php have __dir__ defined value
# --------------------------------------------------------
**/
session_start();

global $_path;
if($_path == '' or $_path == null)
	$_path = "../";

//check MVC and set PATH
isset( $MVC_PATH ) and $_path = $MVC_PATH;

if(!defined('__DIR__')) { 
	$_file = __FILE__;
	$_file = str_replace("\\", "/", __FILE__  );
	$_iPos = strrpos($_file, "/"); 
	// echo $_file . " dir:" . substr($_file, 0, $_iPos) . "<br>";
    define("__DIR__", substr($_file, 0, $_iPos) . "/"); 
} 

require 'help.php';
require 'template.php';
require 'core.php';
require 'baseController.php';
require 'cx_Exception.php';//尚未建立 例外處理
$Core = new core();
$Core->init();
$Core->mConfig['file_path'] = $_path;
require $_path.'_base/config.php';

define( 'BASEPATH', $Core->config( 'BASEPATH' ) );

$_bDebug = $Core->config( 'CXDEBUG' );
//--強制開啟 debug 模式[_GET[$CXDEBUG_KEY] == $CXDEBUG_VAL]
if( $Core->_GET( $Core->config( 'CXDEBUG_KEY' ) ) != null){
	if( $_GET[$Core->config( 'CXDEBUG_KEY' )] == $Core->config( 'CXDEBUG_VAL' ) ){
		$_bDebug = true;
	}
}
$_ip = $_SERVER['REMOTE_ADDR'];
foreach($Core->config( 'CXDEBUG_IPs' ) as $_val ){
	if(preg_match("/".$_val."/", $_ip))
		$_bDebug = true;
}
define( 'CXDEBUG', $_bDebug );

if ( $Core->config( 'CXDEBUG' ) ) {
	

	require 'ChromePhp.php';
	ChromePhp::groupCollapsed( '[CX_Init] $_SESSION ' );
	ChromePhp::log( $_SESSION );
	ChromePhp::groupEnd();
	ChromePhp::groupCollapsed( '[CX_Init] $_POST ' );
	ChromePhp::log( $_POST );
	ChromePhp::groupEnd();
	ChromePhp::groupCollapsed( '[CX_Init] $_GET ' );
	ChromePhp::log( $_GET );
	ChromePhp::groupEnd();
	// require 'library/dump/iDump.php';

}


require 'form-validation.php';
include "library/cx_db.php";
include "library/cx_lib.php";
include $_path."_data/opensql.php";
// header( "Cache-control:private" );
global $conn;//cx
$conn->SetFetchMode( ADODB_FETCH_ASSOC );
$CONN = &$conn;
$Core->setConn( $CONN );

$VIEW['_v_jquery'] = "";
$VIEW['_v_menu'] = "";
$VIEW['_v_body'] = "";

$Core->checkPost();

$mGet = $Core->getValueBaseCheck();
$mPost = $Core->postValueBaseCheck();

function alert( $_msg, $_url ) {
	$_arr['_v_alert'] = $_msg;
	$_arr['_v_url'] = $_url;
	$mTpl = new template();
	$_val = $mTpl->view( 'alert.php', $_arr, true );
	$mTpl->clear();
	return $_val;
}

function alertMsg( $_msg, $_js ) {
	$_arr['_v_alert'] = $_msg;
	$_arr['_v_js'] = $_js;
	$mTpl = new template();
	$mTpl->clear();
	$_val = $mTpl->view( 'alertMsg.php', $_arr, true );
	$mTpl->clear();
	return $_val;
}
function __autoload($class_name)
  {
      $filename = strtolower($class_name) .'.php';
      // $file = site_path .'classes' .DIRECTORY_SEPARATOR .$filename;
       $file = "_base/" . $filename;
      if(!file_exists($file))
      {
          return false;
      }
      include_once ($file);
  }

//CX.php 為MVC模式物件 
require 'CX.php';






?>
