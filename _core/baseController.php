<?php
abstract class baseController {
	var $mCore = null;

	function __construct( $_core ) {
		//parent::__construct();
		//echo "cx control";
		$this->mCore = &$_core;
		
	}

	abstract public function init( $_url );

}
?>
