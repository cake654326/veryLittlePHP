<?php
class cx_lib {
	var $mConn;

	public function __construct( $_conn = null ) {
		//parent::__construct();
		if($_conn == null){
			global $Core;
			if($Core){
				$_conn = &$Core->getDB();
			}else{
				echo "ERROR:[cx_lib] don't have Core OR __construct( $_conn ) val adodb loading";
				exit(0);
			}
		}
		$this->mConn = &$_conn;
	}
}
?>