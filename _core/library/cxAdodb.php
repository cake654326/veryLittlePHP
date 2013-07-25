<?php
//cx_adodb
class cxAdodb{
	// var $mDrives = 'mysql'; //mysql or ado_mssql or access
	public static function linkDB($_config){
		$class_methods = get_class_methods('cxAdodb');
		print_cx($class_methods);
	}

	public static function linkMysql($_config){
		$db = null;
		$db = ADONewConnection("mysql"); 
		$db->charPage=65001;
		$db->PConnect($_config['host'], $_config['user'], $_config['password'], $_config['database'] );
		return &$db;
	}

	public static function linkAdoMssql(){
		$db = null;
		$dsn="PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER={"
				.$_config['host']."};DATABASE=".$_config['database']
				.";UID=".$_config['user'].";PWD=".$_config['password'].";"; 

		$db = ADONewConnection("ado_mssql"); 
		$db->charPage=65001;
		$db->PConnect($_config['host'], $_config['user'], $_config['password'], $_config['database'] );
		return &$db;
// $dsn="PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER={$myhost};DATABASE=$mydatabase;UID=$myuser;PWD=$mypassword;"; 
// 		$conn	= &ADONewConnection($my_DB);
// 		$conn->charPage =65001;
// 		$conn->Connect($dsn);
	}
	
	public static function linkAccess(){
/*
$dsn 	= "Driver={Microsoft Access Driver (*.mdb)};Dbq=".$DBPath.";Uid=;Pwd=;";
		$conn	= &ADONewConnection("access");
		$conn->charPage=CP_UTF8;
		$conn->PConnect($dsn);
*/
	}


}