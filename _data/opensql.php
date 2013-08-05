<?php

	// include('_adodb/adodb.inc.php');//old
	include('adodb5/adodb.inc.php');//update adodb5
	include('_web_set.php');
	
	//-----------------------資料庫連結	
	switch ($my_DB)
	{
	case "access";
		$dsn 	= "Driver={Microsoft Access Driver (*.mdb)};Dbq=".$DBPath.";Uid=;Pwd=;";
		$conn	= ADONewConnection($my_DB);
		//$conn->charPage=CP_UTF8;
		$conn->charPage =65001;
		$conn->PConnect($dsn);
		break;
	case "ado_mssql";
		$dsn="PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER={$myhost};DATABASE=$mydatabase;UID=$myuser;PWD=$mypassword;"; 
		$conn	= ADONewConnection($my_DB);
		$conn->charPage =65001;
		$conn->Connect($dsn);

	
		break;
	case "mssql";
//		dl("php_mssql.dll");	//動態裝載元件(php.ini就無須更改設定值)
		$conn	= ADONewConnection($my_DB);
		$conn->Connect($myhost, $myuser, $mypassword, $mydatabase );
		break;
	case "mysql";
		$conn	= ADONewConnection($my_DB);
		//$conn->charPage=CP_UTF8;
		$conn->charPage =65001;
		$conn->PConnect($myhost, $myuser, $mypassword, $mydatabase );
		break;
	}	
	//-----------------------
	
	
//	打開偵錯模式
//	$conn->debug=true;
//	$conn->debug=1;

//	關閉偵錯模式
//	$conn->debug=false;
//	$conn->debug=0;
#公用參數
//session_start();


