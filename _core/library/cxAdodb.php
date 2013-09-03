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
			case "access":
				//尚未實做
				// $DB['conn'] = self::linXxxxx($_config);
			break;
			case "adoMssql":
			case "ado_mssql":
				$DB['conn'] = &self::linkAdoMssql($_config);
			break;
			case "pdoMssql":
			case "pdo_mssql":
				$DB['conn'] = &self::linkPdoMysql($_config);
			break;
			case "pdoOdbcMssql":
			case "pdo_odbc_mssql":
				$DB['conn'] = &self::linkPdoOdbcMssql($_config);
			break;
			// case "mssql":
			// 	// $DB['conn'] = &self::linXxxxx($_config);
			// break;
			case "mysql":
				$DB['conn'] = &self::linkMysql($_config);
			break;
			case "pdoMysql":
			case "pdo_mysql":
				$DB['conn'] = &self::linkPdoMysql($_config);
			break;
			// case "pdo_odbc_mysql":
				//尚未實做
			// break;

			default:
				$DB['conn'] = false;
			break;
		}
		return $DB['conn'];

	}

/*
  [Drive] => ado_mssql
    [Path] => 
    [Host] => 172.16.44.209
    [User] => sa
    [Password] => seat
    [Database] => CAP_JHSV2
*/

	// ==== 實做 ====
	public static function linkMysql($_config){
		// print_cx($_config);exit();
		$db = null;
		$db = ADONewConnection("mysql"); 
		@$db->charPage=65001;
		$db->PConnect($_config['Host'], $_config['User'], $_config['Password'], $_config['Database'] );
		return $db;
	}

	public static function linkAdoMssql($_config){
		$db = null;
		$dsn="PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER={"
				.$_config['Host']."};DATABASE=".$_config['Database']
				.";UID=".$_config['User'].";PWD=".$_config['Password'].";"; 
		$db = ADONewConnection("ado_mssql"); 
		@$db->charPage=65001;
		$db->Connect($dsn);
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
		return $db;
	}

	public static function linkAccess($_config){
		// --- 未實做
/*
$dsn 	= "Driver={Microsoft Access Driver (*.mdb)};Dbq=".$DBPath.";Uid=;Pwd=;";
		$conn	= &ADONewConnection("access");
		$conn->charPage=CP_UTF8;
		$conn->PConnect($dsn);
*/
	}

	public static function linkSqlite($_config){
		// --- 未實做

	}


}