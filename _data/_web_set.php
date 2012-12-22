<?
//-------------------------------------------------------------	
	//====================資料庫
//	$my_DB = "access";						//Access當資料庫
	$my_DB = "ado_mssql";					//MSSQL當資料庫(ado_mssql支援UTF-8)
//	$my_DB = "mssql";						//MSSQL當資料庫
//	$my_DB = "mysql";						//MYSQL當資料庫(除了考勤查詢、門禁紀錄外，其他功能都用MYSQL解決)
	switch ($my_DB)
	{
	case "access";
		$xPath = $xPath_p ."_data/nkc_epaper_+=-_)(&^%!@#$%^&..mdb";
		$DBPath = realpath($xPath);
		break;
	case "ado_mssql";
		// $myhost		= "XXXXXXXXXXXXXXXX";
		// $myhost		= "127.16.3.48";
		// $myuser		= "sa";
		// $mypassword	= "seat";
		// $mydatabase	= "framework_db";
		$myhost		= "172.16.44.208";
		$myuser		= "sa";
		$mypassword	= "seat";
		$mydatabase	= "UnivSelect";


		break;
	case "mssql";
		$myhost		= "localhost";
		$myuser		= "sa";
		$mypassword	= "xxxx";
		$mydatabase	= "nkc_xxxx";
		break;
	case "mysql";
		$myhost		= "192.xxxxx";
		$myuser		= "xx_sch";
		$mypassword	= "xx";
		$mydatabase	= "xxx_sch";
		break;
	}

	//====================

//-------------------------------------------------------------

?>