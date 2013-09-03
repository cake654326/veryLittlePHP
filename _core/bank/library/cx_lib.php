<?php
class cx_lib {
	var $mCore;
	var $mConn;

	public function __construct( $_Core = null ) {
		//parent::__construct();
		if($_Core == null){
			global $Core;
			if($Core){
				$_conn = &$Core->getDB();
				$_Core = &$Core;
			}else{
				echo "ERROR:[cx_lib] don't have Core OR __construct( $_conn ) val adodb loading";
				exit(0);
			}
		}
		$this->mConn = &$_conn;
		$this->mCore = &$_Core;
	}
}
?>