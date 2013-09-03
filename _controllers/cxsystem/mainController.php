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
		$this->showDebug(true);
	}

	public function init( $aUrl ) {

	}

	public function IndexAction( $aUrl ) {
		echo "demo index";
		print_cx( $aUrl );
	}
	
	public function dbAction( $aUrl ){
		$mDb = new cx_db("Report_DB");
		$mDb->setTitle( "資料庫測試連接 Area " );
		$_sql = "select top 10 * from Area ";
		$_ddd = $mDb->sqlExec($_sql,array() );
		print_cx($_ddd->getArray());
		echo "<br/> --- <br/>";


		$mDb2 = new cx_db("User_DB");
		$mDb2->setTitle( "資料庫測試連接 User_DB " );
		$_sql = "select * from user ";
		$_ddd = $mDb2->sqlExec($_sql,array() );
		print_cx($_ddd->getArray());
		echo "<br/> --- <br/>";
	}

	public function ShowAction( $aUrl ) {
		// echo "show page ";
		// print_cx( $aUrl );
		// echo $this->mCore->loadView( './_view/cxsystem/base.php', array() , true );

		// $this->mCore->log( 'test log:' . $aUrl[0] ,'cake.txt');
		//$this->mCore->getDB()->Execute("select * from abc",array() );
		// $db = new cx_db();
		// $db->Execute("select * from abc",array() );

		// echo $this->mCore->loadView( './_view/cxsystem/demo.php', array() , true );
		// $this->mCore->loadLib("lib_demo");
		// $_demo = new lib_demo();
		// $_demo->test();

		echo 'cake';

		$this->mCore->loadLib("lib_demo" , true , "demo");
		$this->mCore->demo->test();

		$mDb = new cx_db();
		$mDb->setTitle( "資料庫測試連接 TEMP " );
		$_sql = "select * from temp";
		$mDb->sqlExec($_sql,array() );



//---test
$_war = array("aaa"=>"bbb","bbb"=>"ccc");
$this->mCore->debugGroupCollapsed("WARNING","測試參數",0);
$this->mCore->debugLog( "WARNING" ,"測試1", $_war );
$this->mCore->debugLog( "INFO" ,"測試2", $_war );
$_aTest = array();
$_aTest['cxSql;[save]SQL'] = "SELECT * FROM test WHERE id = 1 LIMIT 1 OFFSET 0;";
$_aTest['訊息'] = "is bad sql code";
$_aTest['error'] = new cx_Exception( "ERROR","Backtrace",2);
$this->mCore->debugLog( "ERROR" ,"測試3", "abc");
$this->mCore->debugLog( array("class"=>"dump-clock") , "SERVER", $_SERVER);
$this->mCore->debugGroupEnd(); 



		$aView = array();
		$aView['vLeft'] = $this->mCore->loadView( './_view/cxsystem/vLeft.php', array() , true );
		echo $this->mCore->loadView( './_view/cxsystem/base.php', $aView , true );
		// echo $this->mCore->cxDebugView();
	}

}
?>
