<?php
/**
# CX.php 
# CAKE X
# router 路由器
# --------------------------------------------------------
# 2012/12/07 AM  :   v1.2.0 : [cx] 啟用
# 2012/12/22 PM17:59 v1.2.1 : [cx] 增加取得 baseUrl 網址
# 2012/12/24 PM10:30 v1.2.1 : [cx] aUrl 修正
# 2013/04/08         v1.2   : [cx] 增加讀取資料架預設導入控制器
# --------------------------------------------------------
**/
class cx_router {
	var $aUrl = array();
	var $sController = null;
	var $sAction = null;
	var $sPath = '';
	var $sMvcUrl = './';
	var $aData = array();
	var $sBaseUrl = '';
	var $sHostUrl = '';
	var $aREQUEST_URI = array();
	var $aVal_URI = array();

	public function __construct() {
		

	}

	public function init($_MVC_URL= './' , $sHostUrl = null , $_index_name = 'index.php'){
		$this->sMvcUrl  = $_MVC_URL;
		$this->sHostUrl = $sHostUrl;
		( $sHostUrl == null ) and $this->sHostUrl = $_SERVER['HTTP_HOST'];
		$this->aUrl = $this->_parseUrl($_index_name);
		$_key = $this->UrlCTRL($_index_name);
		return $this;
	}

	public function getBaseUrl(){
		return $this->sBaseUrl ;
	}

	public function getPath(){
		return $this->sPath;
	}


	public function UrlCTRL($_index_name = 'index.php'){
		//---- get base url [cx] 未最佳化
		//aREQUEST_URI
		$_index = 0;
		// print_cx($this->aUrl);
		foreach($this->aUrl as $key => $val){
			$_index++;
			if($val == $_index_name){
				break;
			}
			// array_push($this->aREQUEST_URI, $var);
		}

		$_bad_index = 0;
		foreach($this->aREQUEST_URI as $key => $val){
			$_bad_index++;
			if($val == $_index_name){
				break;
			}
		}
		//未作字串重複解析
		$this->aVal_URI = array_slice($this->aUrl ,  $_bad_index , count($this->aUrl)); 
		 // print_cx($this->aVal_URI );
		// echo $_index . "<br>";
		$_data = array_slice($this->aUrl , 0 , $_index-1) ;
		$_str = implode( '/', $_data ) ;
		$this->sBaseUrl = $this->sHostUrl . '/' . $_str . '/';
		 // echo $this->sBaseUrl;
		 // print_cx($_data);
		// var $sBaseUrl = '';


		//---- get back val and controller val and dir 
		//echo "<br> now getcwd: " . getcwd() . "<br>"; length
		$_path = $this->sMvcUrl;
		//echo "path:" . $_path;
		$_key = 0;
		 // print_r($this->aUrl);
		$_next = null;//下一個元素
		foreach($this->aUrl as $key => &$val){

			$_next = current($this->aUrl);
			// echo "next :" . $_next . "<br>"; 

			$this->sAction = "Index";
			$_key = $key;
			if($val == ''){
				//echo "val is null";
				break;
			}

			$__path = $_path . '/' . $val;
			// echo "<br>path: " . $__path . ":";
			if(is_dir($__path)){
				//return $_file;
				$_path = $__path;
				// echo "ok _path" .$_path. "<br>";
				//下一個序列為空
				if($_next == null){
					// echo "ok __path: "  .$__path. "<br>";
					$_crtl_path = $__path."/indexController.php";
					// echo "  => _crtl_path:" .$_crtl_path. "<br>";
					if( file_exists( $_crtl_path ) )
					{
						// echo "ok2";
						// $_path = "indexController.php";
						$this->sController = "Index";
						$this->sAction = "Index";
						$this->sPath = $_crtl_path;
						
					}
				}
				
			}else{
				$_crtl_path = $__path."Controller.php";
				// echo "file:" . $_crtl_path . "<br>";
				if( file_exists( $_crtl_path ) )
				{
					// echo "=> ok";
					// echo " ,val=>" . $val;
					$_path = $__path."Controller.php";
					$this->sController = $val;
					if( isset($this->aUrl[$key + 1] )){
						$this->sAction = $this->aUrl[$key + 1];
					}else{
						$this->sAction = "Index";
					}
					$this->sPath = $_path;
					break;
				}else{

				}
				// echo "bad <br>";
			}
		}

		 // print_r($this->sPath);
		// print_r($this->aUrl);
		// print_r(array_slice($this->aUrl , $_key+1 , $_len) );
		$_len =  count($this->aUrl);
		$this->aData = array_slice($this->aUrl , $_key+1 , $_len) ;
		return $_key;
	}

	public function getControllerName(){
		return $this->sController;
	}
	



	//protected
	public function _parseUrl( $baseDir = "index.php" ) {
		//currdir: /120903/cx_core/veryLittlePHP/index.php/con1/val1/val2/val3?g1=a&g2=b&g3=c
		$currDir = $_SERVER['REQUEST_URI'];
		if ( false !== strpos( $currDir, '?' ) ) {//get not GET uRL
			$currDir = str_replace( substr( $currDir, strpos( $currDir, '?' ) ), '', $currDir );
		}
		// $pattern2 = '/^\/' . preg_quote( $baseDir, '/' ) . '\/*(.*)$/';
		$pattern = '/\/' . preg_quote( $baseDir, '/' ) . '\/*(.*)$/';

		if ( empty( $matches ) ) {
			$matches = array( '', ltrim( $currDir, '/' ) );
		}

		//$___Url = array( '', ltrim( $currDir, '/' ) );
		$_aREQUEST_URI = explode('/', $_SERVER['REQUEST_URI']);
		foreach($_aREQUEST_URI as $val){
			if($val != ''  and !is_null($val) ){
				array_push($this->aREQUEST_URI,$val);
			}
		}
		// unset($arr[$ii]);

		

		$tickets = isset( $matches[1] ) ? explode( '/', $matches[1] ) : array ( '', '' );
		 // print_cx($tickets);

		//tickets: Array ( [0] => con1 [1] => val1 [2] => val2 [3] => val3 )
		// echo "contrl:" . $_controller . " ,action:" . $_action;

		$_controller = ( $tickets[0] ) ? strtolower( $tickets[0] ) : 'index';
		$_action = ( isset( $tickets[1] ) && $tickets[1] ) ? strtolower( $tickets[1] ) : 'index';
		// echo "contrl:" . $_controller . " ,action:" . $_action;
		// print_cx($tickets);

		return $tickets;
	}






}



?>
