<?php
/**
 * router 路由器
 * 
 *
 * **/
class cx_router {
	var $aUrl = array();
	var $sController = null;
	var $sAction = null;
	var $sPath = '';
	var $sMvcUrl = './';
	var $aData = array();



	public function __construct() {
		

	}

	public function init($_MVC_URL= './'){
		$this->sMvcUrl = $_MVC_URL;
		$this->aUrl = $this->_parseUrl();
		$_key = $this->UrlCTRL();
		return $this;
	}

	public function getPath(){
		return $this->sPath;
	}

	public function UrlCTRL(){
		//echo "<br> now getcwd: " . getcwd() . "<br>"; length
		$_path = $this->sMvcUrl;
		//echo "path:" . $_path;
		$_key = 0;
		foreach($this->aUrl as $key => $val){
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
				//echo "ok <br>";
			}else{

				if(file_exists($__path."Controller.php"))
				{
					$_path = $__path."Controller.php";
					$this->sController = $val;
					if( isset($this->aUrl[$key + 1] )){
						$this->sAction = $this->aUrl[$key + 1];
					}else{
						$this->sAction = "Index";
					}
					$this->sPath = $_path;
					break;
				}
				// echo "bad <br>";
			}
		}

		//print_r($this->aUrl);
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
		// $pattern = '/^\/' . preg_quote( $baseDir, '/' ) . '\/*(.*)$/';
		$pattern = '/\/' . preg_quote( $baseDir, '/' ) . '\/*(.*)$/';

		// echo "<br> pattern: " . $pattern . "<br>";

		preg_match( $pattern, $currDir, $matches );

		if ( empty( $matches ) ) {
			$matches = array( '', ltrim( $currDir, '/' ) );
		}

		$tickets = isset( $matches[1] ) ? explode( '/', $matches[1] ) : array ( '', '' );
		//tickets: Array ( [0] => con1 [1] => val1 [2] => val2 [3] => val3 )

		$_controller = ( $tickets[0] ) ? strtolower( $tickets[0] ) : 'index';
		$_action = ( isset( $tickets[1] ) && $tickets[1] ) ? strtolower( $tickets[1] ) : 'index';
		return $tickets;
		//echo "contrl:" . $_controller . " ,action:" . $_action;
	}






}



?>
