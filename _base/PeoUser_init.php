<?php
include("../_core/init.php");
//check user
//print_cx($_SESSION);

if( $_SESSION['peo_user']  != 1 && $_SESSION['ad_user']  != 1 ){
	echo alert("請重新登入","../PeoUser/PeoLogin.php"); 
}
//echo alert("請重新登入","../user/login.php"); 


?>
