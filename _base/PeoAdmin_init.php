<?php
include("../_core/init.php");
//check user
$mS = 'S';
if($_SESSION["Calss_No"]  != $mS)
{ 
	echo alert("請重新登入","../PeoMain/login.php"); 
	exit();
}

?>
