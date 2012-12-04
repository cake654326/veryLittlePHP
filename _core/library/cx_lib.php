<?php
class cx_lib {
	var $mCore;

	public function __construct( $_Core = null ) {
		//parent::__construct();
		if($_Core == null){
			global $Core;
			if($Core){
				$_Core = &$Core->getDB();
			}else{
				echo "ERROR:[cx_lib] don't have Core OR __construct( $_conn ) val adodb loading";
				exit(0);
			}
		}
		$this->mCore = &$_Core;
	}
}
?>