<?php
/**
# init.php 
# CAKE X
# 預設載入功能
# --------------------------------------------------------
# 2012/11            v1.1.0 : [cx]相容 傳統寫法專案(v1.1)，不相容 舊版 v1.0框架專案
# 2012/12/07         v1.2.0 : [cx]增加 MVC 控制器模式
# 2012/12/22 AM09:15 v1.2.0 : [cx]修正 baseController
# --------------------------------------------------------
**/
session_start();
$_path = "../";

//check MVC and set PATH
isset( $MVC_PATH ) and $_path = $MVC_PATH;

require 'help.php';
require 'template.php';
require 'core.php';
require 'baseController.php';
$Core = new core();
$Core->init();
$Core->mConfig['file_path'] = $_path;
require $_path.'_base/config.php';
ini_set( 'session.gc_maxlifetime', $Core->config( 'gc_maxlifetime' ) );
define( 'BASEPATH', $Core->config( 'BASEPATH' ) );
define( 'CXDEBUG', $Core->config( 'CXDEBUG' ) );
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
}
require 'form-validation.php';
include "library/cx_db.php";
include "library/cx_lib.php";
include $_path."_data/opensql.php";
header( "Cache-control:private" );
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

//CX.php 為MVC模式物件 
require 'CX.php';


?>
