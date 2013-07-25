<?php
//-------------------------------------------------------------	
	//====================資料庫
	//	$my_DB = "access";					//Access
	$my_DB = "ado_mssql";				//MSSQL
	// $my_DB = "mysql";						//Mysql
	switch ($my_DB)
	{
	case "access";
		$xPath = $xPath_p ."_data/nkc_epaper_+=-_)(&^%!@#$%^&..mdb";
		$DBPath = realpath($xPath);
		break;

	case "ado_mssql";
		// $myhost		= "XXXXXXXXXXXXXXXX";
		$myhost		= "172.16.3.48";
		$myuser		= "sa";
		$mypassword	= "seat";
		$mydatabase	= "framework_db";
	//------
		// $myhost		= "172.16.44.208";
		// $myuser		= "sa";
		// $mypassword	= "seat";
		// $mydatabase	= "UnivSelect";
		break;

	case "mysql";
		$myhost		= "172.16.44.80";
		$myuser		= "root";
		$mypassword	= "seat";
		$mydatabase	= "cx_tasks";
		break;
	}
