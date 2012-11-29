<?php
session_start();
require 'core.php';
$Core = new core();
require '../_base/config.php';
ini_set('session.gc_maxlifetime', $Core->config('gc_maxlifetime'));
define( 'BASEPATH', $Core->config('BASEPATH') );
define( 'CXDEBUG', $Core->config('CXDEBUG') );
if($Core->config('CXDEBUG')){
	require 'ChromePhp.php';
}
require 'help.php';
require 'template.php';
require 'form-validation.php';
include "../_library/cx/cx_db.php";
include "../_data/opensql.php";
header( "Cache-control:private" );
//session_cache_limiter(¡¥private¡¦);
//echo "session :" . ini_get("session.gc_maxlifetime"); 
$conn->SetFetchMode( ADODB_FETCH_ASSOC );
$CONN = &$conn;
$Core->setConn($CONN);
// *! check user

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

//------------- [ project Config set] --------------



?>
