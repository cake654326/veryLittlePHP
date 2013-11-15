<?php  if ( ! defined( 'MVC_PATH' )  ) exit( '404' );
class VcodeController extends baseController
{
	var $mMessage = '';
	var $sMessage;
	var $mBaseData;
	var $uData = array();
	var $aUrl = null;

	public function __construct( $_core ) 
	{
		parent::__construct( $_core );
		$this->showDebug(false);
	}
//public function init( $aUrl ) {
	public function init( $aUrl ) {
		// parent::init( $aUrl );
		$this->aUrl = $aUrl;
	}

	public function IndexAction( $aUrl ){
		 return ;
	}

	public function imgAction( $aUrl ){
		// $this->mCore->loadLib("Excel/lib_excel",true,"mExcel");
		$this->mCore->loadLib("lib_vcode",true,"mVcode");
		// print_cx($this->mCore->mVcode);
		$this->mCore->mVcode->SetImage( 1, 5, 110, 45, 100, 0 );

		

		
	}

}