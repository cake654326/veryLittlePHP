<?php
//cx_adodb
class cxAdodb{
	// var $mDrives = 'mysql'; //mysql or ado_mssql or access
	public static function linkDB($_config){
		// $class_methods = get_class_methods('cxAdodb');
		// print_cx($class_methods);
		$DB = array();
		$DB['config'] = $_config;

		switch( $_config['Drive'] ){

			case "adoMssql":
			case "ado_mssql":
				$DB['conn'] = self::linkAdoMssql($_config);
			break;

			case "pdoMssql":
			case "pdo_mssql":
				$DB['conn'] = self::linkPdoMysql($_config);
			break;

			case "pdoOdbcMssql":
			case "pdo_odbc_mssql":
				$DB['conn'] = self::linkPdoOdbcMssql($_config);
			break;
			// case "mssql":
			// 	// $DB['conn'] = self::linXxxxx($_config);
			// break;

			case "mysql":
				// echo "mysql";exit();
				// print_cx($_config);
				$DB['conn'] = self::linkMysql($_config);
				// print_cx($DB);
			break;

			case "pdoMysql":
			case "pdo_mysql":
			// print_cx($_config);exit();
				$DB['conn'] = self::linkPdoMysql($_config);
			break;
			// case "pdo_odbc_mysql":
				//尚未實做
			// break;

			case "access":
				// echo "cake";
				// echo "access";exit();
				$DB['conn'] = self::linkAccess( $_config );
				// echo "link";
			break;

			default:
				$DB['conn'] = false;
			break;
		}


		if( @method_exists( $DB['conn'] , 'SetFetchMode' ) ){
				$DB['conn']->SetFetchMode( ADODB_FETCH_ASSOC );
		}else{
				echo "[DB][ERROR]: Link Drive「" . $_config['Drive']  . "」 BAD ";   
				exit();
		}
		    
		return $DB['conn'];

	}



	// ==== 實做 ====
	public static function linkMysql($_config){
		// print_cx($_config);exit();
		$db = null;
		$db = ADONewConnection("mysql"); 
		@$db->charPage=65001;
		$_isok = $db->PConnect($_config['Host'], $_config['User'], $_config['Password'], $_config['Database'] );
		if(!$_isok){
			echo $db->ErrorMsg();
			die("link DB Error!");
			exit();
		}
		return $db;
	}

	public static function linkAdoMssql($_config){
		$db = null;
		$dsn="PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER={"
				.$_config['Host']."};DATABASE=".$_config['Database']
				.";UID=".$_config['User'].";PWD=".$_config['Password'].";"; 
		$db = ADONewConnection("ado_mssql"); 
		@$db->charPage=65001;
		$_isok = $db->Connect($dsn);
		if(!$_isok){
			echo $db->ErrorMsg();
			die("link DB Error!");
		}
		
		return $db;

	}

	public static function linkPdoMssql($_config){
		$db = null;
		$db =NewADOConnection('pdo');
		//ex: $dsn = 'sqlsrv:Server=172.16.44.209;Database=CAP_JHSV2'; 
		$db->Connect('sqlsrv:Server='.$_config['Host'],$_config['User'],$_config['Password'], $_config['Database']);
		return $db;
	}

	public static function linkPdoOdbcMssql($_config){
		$db = null;
		$db =NewADOConnection('pdo');
		$db->Connect('odbc:Driver={SQL Server};Server='.$_config['Host'].';DATABASE='. $_config['Database'].';charset=utf8',$_config['User'],$_config['Password'],false);
		return $db;
	}
	
	public static function linkPdoMysql($_config){
		$db = null;
		$db =NewADOConnection('pdo');
		//$dsn = 'sqlsrv:Server=172.16.44.209;Database=CAP_JHSV2'; 
		$db->Connect('sqlsrv:Server='.$_config['Host'],$_config['User'],$_config['Password'], $_config['Database']);
		// $this->mConn->ErrorMsg();
		return $db;
	}

	public static function linkAccess($_config){
		$access = realpath( $_config['Path'] ); 
		// $access = $_config['Path']; 
/*
$access = \'test.mdb\'; 
$myDSN = \'PROVIDER=Microsoft.Jet.OLEDB.4.0;\'.\'DATA SOURCE=\'. $access . \';\';.\'USER ID=;PASSWORD=;\'; 

if (@$db->PConnect($myDSN, \"\", \"\", \"\")) { 
*/
	/*
	$dsn = 'PROVIDER=Microsoft.Jet.OLEDB.4.0;'.'DATA SOURCE='. $access . ';'.'USER ID=;PASSWORD=;'; 
	$db = &ADONewConnection("ado_access"); 

	$db->PConnect($dsn);
*/

// $connection = odbc_connect( 'Driver={Microsoft Access Driver (*.mdb)};Dbq="'. $access .'"' , '', '' );


/*ok
$DBPath_acc= realpath( $xPath_acc );
			$dsn_acc = "Driver={Microsoft Access Driver (*.mdb)};Dbq=".$DBPath_acc.";Uid=;Pwd=;";
			$conn_acc = &ADONewConnection( 'access' );
			$conn_acc->Connect( $dsn_acc );
*/

		$dsn = 
	"Driver={Microsoft Access Driver (*.mdb)};Dbq=".$access.";Uid=".$_config['User'].";Pwd=".$_config['Password'].";";
		// echo $dsn;
		$db = null;
		$db	= ADONewConnection("access");


// $obj=odbc_connect($dsn,'',''); 


		@$db->charPage=CP_UTF8;
		$db->PConnect($dsn);
		// echo $dsn;
		// print_cx($db);exit();


		return $db;
	}

	public static function linkSqlite($_config){
		// --- 未實做

	}


}