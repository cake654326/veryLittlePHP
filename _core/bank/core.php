<?php
class core {
	var $mConfig = array();
	var $mConn = null;
	var $mPost =array();
	var $mGet = array();
	public function __construct(  ) {
		//parent::__construct(  );
		$this->mConfig['style_name'] = "style";
		$this->mConfig['BASEPATH'] = "_base/init";
		$this->mConfig['gc_maxlifetime'] = 0;
		$this->mConfig['CXDEBUG'] = true;
		$this->mGet = $this->_addslashes_arr( $_GET );
		$this->mPost = $this->_addslashes_arr( $_POST );
	}

	public function Post(){
		return $this->mPost;
	}
	public function Get(){
		return $this->mGet;
	}

	public function setConn( $_conn ){
		$this->mConn = &$_conn;
	}

	public function getDB(){
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

	public function array2keyStr($_arr){
		//$a=array("星期日","星期一");
		$_data = array();
		if(count($_arr) == 0)return false;
		foreach($_arr as $key => $val){
			$_data[] = '"' . $key . '"';
		}
		$_str = "array(".implode(",", $_data) . ");";
		return $_str;
	}

	public function checkArrayKey($_key,$_arr){
		$_data = array();
		foreach($_key as $val){
			if(array_key_exists($val,$_arr)){
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

}
?>
