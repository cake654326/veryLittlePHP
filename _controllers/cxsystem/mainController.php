<?php
/**
 * # mainController - 框架 控制台
 * # CAKE X
 * # 繼承 baseController ,未來將建立 自定 Contoller 功能
 * # --------------------------------------------------------
 * # 2012/12/22 AM16:07 v1.2 : [cx] cxsystem/mainController.php
 * # --------------------------------------------------------
 * */
class mainController extends baseController
{
	public function __construct( $_core ) {
		parent::__construct( $_core );
		//$this->mCore->loadLib( "lib_demo" );
	}

	public function init() {

	}

	public function IndexAction( $aUrl ) {
		echo "demo index";
		print_cx( $aUrl );
	}

	public function ShowAction( $aUrl ) {
		// echo "show page ";
		// print_cx( $aUrl );
		// echo $this->mCore->loadView( './_view/cxsystem/base.php', array() , true );

		// $this->mCore->log( 'test log:' . $aUrl[0] ,'cake.txt');
		//$this->mCore->getDB()->Execute("select * from abc",array() );
		$db = new cx_db();
		$db->Execute("select * from abc",array() );

		echo $this->mCore->loadView( './_view/cxsystem/demo.php', array() , true );
	}

}
?>
