<?php
abstract class baseController {
	var $mCore = null;
	private $bShowDebug = false;

	function __construct( $_core ) {
		//parent::__construct();
		//echo "cx control";
		$this->mCore = &$_core;
		
		
	}

	abstract public function init( $_url );

	public function showDebug($_io = null){
		if($_io != null)
			$this->bShowDebug = $_io;
		return $this->bShowDebug;
	}


}
?>
