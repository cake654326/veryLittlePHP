<?php
/**
# core.php 
# CAKE X
# Core 常用以及簡化工具組
# --------------------------------------------------------
# 「History」
#
# 2012/11/22 AM  :   v1.1.0 : [cx] loadMod  自動載入 MOD
#								   loadLib  自動載入 LIB
# 2012/12/   AM  :   v1.2.0 : [cx] loadView  載入樣板 （會自動清空）
# 2012/12/22 PM05:59 v1.2.1 : [cx] 增加 baseUrl 
# 2012/12/27 AM09:14 v1.2.1 : [cx] 增加 redirect Url
# 2013/01/03 AM09:40 v1.2.2 : [cx] 增加 log
# 2013/01/09 PM04:06 v1.2.3 : [cx] 增加 _cx_init_object , _cx_load_class 提供載入物件並建立
# 2013/01/09 PM04:06 v1.2.3 : [cx] 更新 loadLib ,向下相容 提供自動初始化
# 2013/01/10 AM11:02 v1.2.3 : [cx] 更新 loadMod ,向下相容 提供自動初始化
#
# --------------------------------------------------------
#「Function」(常用)
#
#@寫入LOG檔案 - 設定檔可設定 系統路徑以及系統檔名
# log(  $_msg , $_file_name = null ,$_path = null )
#  @ $_msg 訊息
#  @ $_file_name 檔案名稱( 預設為 設定檔資料)
#  @ $_path 檔案路徑( 預設為 設定檔資料)
#
#@自動取得相對路徑 並且 導入頁面
# redirect( $_url , $_CONTROLLER as TRUE)
#	@ $_url 路徑名
#	@ $_CONTROLLER = TRUE : 自動加入 INDEX.PHP (MVC入口)
#					 FALSE: 不會自動增加index.php，適用載入樣式或傳統開發
#
#@自動取得相對路徑 
# Url( $_url , $_CONTROLLER as TRUE)     
#	@ $_url 路徑名
#	@ $_CONTROLLER = TRUE : 自動加入 INDEX.PHP (MVC入口)
#					 FALSE: 不會自動增加index.php，適用載入樣式或傳統開發
#
#@設定絕對路徑 ,可由設定檔 或者 路由會自動取得
# setBaseUrl( $_url )                    
#
#@取得 絕對路徑
# getUrl()                               
#
#@CORE端載入樣板(已經自動宣告樣板物件)
# loadView( $_path, $_arr, $_b  = false )
#
#@自動include載入模組
# loadMod( $_name , $_autoSet = false , $_object_name = null ,$_params = null )     
#	@ $_name        物件名
#	@ $_autoSet     是否啟用自動初始化
#	@ $_object_name 自定物件名稱
#	@ $_params      初始化參數參數1
#
#@自動include載入Lib
# loadLib( $_name , $_autoSet = false , $_object_name = null ,$_params = null )     
#	@ $_name        物件名
#	@ $_autoSet     是否啟用自動初始化
#	@ $_object_name 自定物件名稱
#	@ $_params      初始化參數參數1
#
#@取得POST資料
# Post() 
#
# --------------------------------------------------------
**/
class core {
	var $mConfig = array();
	var $mConn = null;
	var $mPost =array();
	var $mGet = array();
	var $mMod = array();
	var $mLib = array();
	var $mSysLib = array();
	var $mHelpFunc = array();
	var $mTpl = null;
	var $mBaseUrl = null;
	var $mBackUrl = array(); // 後輟
	var $mLog = null;

	var $mLayout = array(); //[實驗] !* 未定 載入LAYOUT樣板

	public function __construct(  ) {
		//parent::__construct(  );
		$this->mConfig['sysLib_name'] = "library";
		$this->mConfig['sysFile_path'] = "";
		$this->mConfig['sysHelp_path'] = "help";

		$this->mConfig['file_path'] = "../";
		$this->mConfig['mod_name'] = "_model";
		$this->mConfig['lib_name'] = "_library";
		$this->mConfig['style_name'] = "style";
		$this->mConfig['BASEPATH'] = "_base/init";
		$this->mConfig['sysModules_name'] = "_modules";
		$this->mConfig['gc_maxlifetime'] = 0;
		$this->mConfig['CXDEBUG'] = true;

		$this->mGet = $this->_addslashes_arr( $_GET );
		$this->mPost = $this->_addslashes_arr( $_POST );
		$this->mTpl = new template();
		
	}

	public function init(){
		$this->loadSysLib('cx_log');
		$this->mLog = new cx_log();

		return $this;
	}

	public function log(  $_msg , $_file_name = null ,$_path = null ){
		if(!$this->config('WRITELOG'))return;
		( $_path == null ) and $_path = "./" . $this->config( 'LOGFILENAME' ) ;
		( $_file_name == null ) and $_file_name =  $this->config( 'SYSLOGNAME' ) ;
		$this->mLog->lfile( $_path . '/' . $_file_name );
		$this->mLog->lwrite($_msg);
		$this->mLog->lclose();

	}


	//MVC HEADER
	/* redirect
 	 * @ $_url : WEB URL ,IF $_CONTROLLER AS FALSE ,NEED index.php/XXX/xx/x/xxx
	 */
	public function redirect( $_url , $_CONTROLLER = TRUE){
		header("Refresh: 0; url=" . $this->Url( $_url , $_CONTROLLER) );
	}

	public function Url( $_url , $_CONTROLLER = TRUE){
		//mBackUrl
		$_back_num = count($this->mBackUrl);
		$_title_url = "./";
		for( $_i = 0; $_i < $_back_num ;$_i++){
			$_title_url .= "../";
		}
		($_CONTROLLER == true ) and $_title_url .= $this->config("INDEX") . "/" ;
		return $_title_url . $_url;
	}

	public function setBaseUrl( $_url ){
		$this->mBaseUrl = $_url;
		return $this;
	}

	public function getBaseUrl(){
		return $this->mBaseUrl;
	}

	public function getUrl(){
		return $this->config( 'base_http_title' ) . $this->getBaseUrl();
	}

	public function loadView( $_path, $_arr, $_b = false ) {
		$val = $this->mTpl->view( $_path, $_arr, $_b );
		$this->mTpl->clear();
		return $val;
	}

	public function loadMod( $_name , $_autoSet = false , $_object_name = null ,$_params = null ) {
		if ( !in_array( $_name, $this->mMod ) ) {
			$_path = $this->config( 'file_path' ).
				$this->config( 'mod_name' ) .
				'/'.$_name.'.php';
			
			if(! $this->_cx_load_class("mMod" ,$_path,$_name,$_autoSet,$_object_name,$_params) ) return false;
		}elseif( $_autoSet == true ){
			if(! $this->_cx_init_object($_name ,$_object_name , $_params) ) return false;
			return true;
		}
		return false;
	}

//_cx_load_class($_type , $_path ,$_library, $_autoSet = false ,$_params = null, $_object_name = null){
	public function loadLib( $_name , $_autoSet = false , $_object_name = null ,$_params = null ) {
		if ( !in_array( $_name, $this->mLib ) ) {
			$_path = $this->config( 'file_path' ).
				$this->config( 'lib_name' ) .
				'/'.$_name.'.php';
			if(! $this->_cx_load_class("mLib" ,$_path,$_name,$_autoSet,$_object_name,$_params) ) return false;
		}elseif( $_autoSet == true ){
			if(! $this->_cx_init_object($_name ,$_object_name , $_params) ) return false;
			return true;
		}
		return false;
	}


	public function loadSysLib( $_name ) {
		if ( !in_array( $_name, $this->mSysLib ) ) {
			$_path = $this->config( 'sysFile_path' ).
				$this->config( 'sysLib_name' ) .
				'/'.$_name.'.php';
			//echo $_path;
			//if( !file_exists($_path) ) return false;
			array_push( $this->mSysLib, $_name );
			require $_path;
			return true;
		}
		return false;
	}

//load help function
	public function loadHelp( $_name ) {
		if ( !in_array( $_name, $this->mSysLib ) ) {
			$_path = $this->config( 'sysFile_path' ).
				$this->config( 'sysHelp_path' ) .
				'/'.$_name.'_helper.php';
				
			// if( !file_exists($_path) ){
			// 	//[cx next ]add log
			// 	return false;
			// } 
			array_push( $this->mHelpFunc, $_name );
			require $_path;
			return true;
		}
		return false;
	}

	private function _cx_init_object($_library ,$_object_name = null, $_params = null){
			($_object_name == null) and $_object_name = $_library;
			$_classvar = $_object_name;
			if($_params != null){
				$this->{$_classvar} = new $_library($_params);
			}else{
				// echo "_library:" . $_library . " ,_classvar:" . $_classvar;
				$this->{$_classvar} = new $_library();
			}
			return true;
	}

	private function _cx_load_class($_type , $_path ,$_library, $_autoSet = false ,
									 $_object_name = null ,$_params = null){
		if( !file_exists($_path) ) return false;
		array_push( $this->$_type, $_library );
		require $_path;
		if($_autoSet){
			$this->_cx_init_object($_library ,$_object_name , $_params);
		}
		return true;
	}

	public function Post() {
		return $this->mPost;
	}
	public function Get() {
		return $this->mGet;
	}

	public function setConn( $_conn ) {
		$this->mConn = &$_conn;
	}

	public function getDB() {
		return $this->mConn;
	}

	public function setConfig( $_key, $_val ) {
		$this->mConfig[$_key] = $_val;
	}

	public function config( $_key ) {
		return $this->mConfig[$_key];
	}

	public function checkPost() {
		if ( is_array( $_POST ) && !get_magic_quotes_gpc() ) {
			while ( list( $k, $v ) = each( $_POST ) ) {
				$$k = mysql_real_escape_string( trim( $v ) );
			}
			@reset( $_POST );
		}
	}

	public function array2keyStr( $_arr ) {
		//$a=array("星期日","星期一");
		$_data = array();
		if ( count( $_arr ) == 0 )return false;
		foreach ( $_arr as $key => $val ) {
			$_data[] = '"' . $key . '"';
		}
		$_str = "array(".implode( ",", $_data ) . ");";
		return $_str;
	}

	public function checkArrayKey( $_key, $_arr ) {
		$_data = array();
		foreach ( $_key as $val ) {
			if ( array_key_exists( $val, $_arr ) ) {
				$_data[$val] = $_arr[$val];
			}
		}
		return $_data;
	}

	/**
	 * 對於POST 做 addslashes 以及 trim
	 * **/
	function postValueBaseCheck() {

		return $this->mPost;
	}

	function _addslashes_arr( $val ) {
		// postValueBaseCheck function
		$_arr = array();
		foreach ( (array)$val as $k => $v ) {
			if ( is_array( $v ) ) {
				$_arr[$k] = $this->_addslashes_arr( $v );
			}else {
				$_arr[$k] = addslashes( trim( $v ) );
			}
		}
		return $_arr;
	}

	/**
	 * 對於GET 做 addslashes 以及 trim
	 * ***/
	function getValueBaseCheck() {

		return $this->mGet;
	}

	function is_debug() {
		if ( defined( 'CXDEBUG' ) ) {
			if ( CXDEBUG == true ) {
				
				//ChromePhp::log("ADODB SQL 總花費時間", $this->_cx_all_time);
				return true;
			}

		}
		return false;
	}



}
?>
