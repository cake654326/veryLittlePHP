<?php
include("../_core/init.php");
if($_SESSION['ad_user']  != 1 && $_SESSION['seat'] != 1)
{ 
	echo alert("請重新登入","../user/login.php"); 
	exit();
}

?>
