<?php
/**
# core.php 
# CAKE X
# Core 常用以及簡化工具組
# --------------------------------------------------------
# 2012/11/22 AM  :   v1.1.0 : [cx] loadMod  自動載入 MOD
#								   loadLib  自動載入 LIB
# 2012/12/   AM  :   v1.2.0 : [cx] loadView  載入樣板 （會自動清空）
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
	var $mTpl = null;

	var $mLayout = array(); //[實驗] !* 未定 載入LAYOUT樣板

	public function __construct(  ) {
		//parent::__construct(  );
		$this->mConfig['sysLib_name'] = "library";
		$this->mConfig['sysFile_path'] = "";
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

	public function loadView( $_path, $_arr, $_b = false ) {
		$val = $this->mTpl->view( $_path, $_arr, $_b );
		$this->mTpl->clear();
		return $val;
	}

	public function loadMod( $_name ) {
		//array_search
		if ( !in_array( $_name, $this->mMod )  ) {

			$_path = $this->config( 'file_path' ).
				$this->config( 'mod_name' ) .
				'/'.$_name.'.php';
			array_push( $this->mMod, $_name );
			require $_path;
			return true;
		}
		return false;
	}

	public function loadLib( $_name ) {
		if ( !in_array( $_name, $this->mLib ) ) {
			$_path = $this->config( 'file_path' ).
				$this->config( 'lib_name' ) .
				'/'.$_name.'.php';
			array_push( $this->mLib, $_name );
			require $_path;
			return true;
		}
		return false;
	}

	public function loadSysLib( $_name ) {
		if ( !in_array( $_name, $this->mSysLib ) ) {
			$_path = $this->config( 'sysFile_path' ).
				$this->config( 'sysLib_name' ) .
				'/'.$_name.'.php';
			array_push( $this->mSysLib, $_name );
			require $_path;
			return true;
		}
		return false;
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
