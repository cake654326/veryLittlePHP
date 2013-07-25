<?php

class cx_Exception extends ErrorException {

	public $mException = array();

	public function __construct ( $type , $message, $code = 0 ){
		// $message = $_E['message'];
		// $type = $_E['type'];
		parent::__construct($message,$code);
		array_push($this->mException, array($type=>$message) );
	}

	//Exception set and send
	function setException( $_code , $_e){
		array_push($this->mException, array($_code=>$_e) );
	}

	function sendException(){
		return $this->mException;
	}

} 
