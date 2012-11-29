<?php

	include('_adodb/adodb.inc.php');
	include('_web_set.php');
	
	//-----------------------資料庫連結	
	switch ($my_DB)
	{
	case "access";
		$dsn 	= "Driver={Microsoft Access Driver (*.mdb)};Dbq=".$DBPath.";Uid=;Pwd=;";
		$conn	= &ADONewConnection($my_DB);
		$conn->charPage=CP_UTF8;
		$conn->PConnect($dsn);
		break;
	case "ado_mssql";
		$dsn="PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER={$myhost};DATABASE=$mydatabase;UID=$myuser;PWD=$mypassword;"; 
		$conn	= &ADONewConnection($my_DB);
		$conn->charPage =65001;
		$conn->Connect($dsn);

	
		break;
	case "mssql";
//		dl("php_mssql.dll");	//動態裝載元件(php.ini就無須更改設定值)
		$conn	= &ADONewConnection($my_DB);
		$conn->Connect($myhost, $myuser, $mypassword, $mydatabase );
		break;
	case "mysql";
		$conn	= &ADONewConnection($my_DB);
		$conn->charPage=CP_UTF8;
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
	session_start();
	global $pageSize5;
	$pageSize1	= 1;		//設定每頁顯示 1 筆
	$pageSize5	= 5;		//設定每頁顯示 5 筆
	$pageSize10	= 10;		//設定每頁顯示 10 筆
	$pageSize15	= 15;		//設定每頁顯示 15 筆
	$pageSize20	= 20;		//設定每頁顯示 20 筆
	$pageSize20	= 200;		//設定每頁顯示 200 筆
	
	$bgcolor_body	= 	"#DCDCDC";
	$bgcolor_ffffff =	"#FFFFFF";
	$bgcolor_tr1 	=	"#0099FF";
	$bgcolor_tr2 	=	"#CEEBFF";

	$org_NAME='松盟科技';
	$rpt_NAME='101學年度大學多元入學選填志願輔導';

//--------------------------------------------------------------------------------------------
//////驗證是否已登入
function check_nm_pw_admin10($xPath_p,$URL_to)
{
	IF (@$_SESSION["u_grade_ck"]<>"10" or @$_SESSION["check_pass_ck"]<>"_true"){
		header("Location:". @$xPath_p ."id/logout.php?URL_to=". @$URL_to);
		exit;
	}
}
//////驗證是否已登入
function check_nm_pw_1020($xPath_p,$URL_to)
{
	IF ((@$_SESSION["u_grade_ck"]<>"10" and @$_SESSION["u_grade_ck"]<>"20") or @$_SESSION["check_pass_ck"]<>"_true"){
		header("Location:". @$xPath_p ."id/logout.php?URL_to=". @$URL_to);
		exit;
	}
}


  

//--------------------------------------------------------------------------------------------
//////驗證是否含有隱碼
function check_nm_pw($username,$password)
{
	if (substr_count($username,"'")<>0 or substr_count($password,"'")<>0)	//數據預處理,檢測輸入中是否存在 ′ 符號 
	{
		echo "<script>";
		echo "alert('帳號或密碼錯誤！');";
		echo "history.go(-1);";
		echo "</script>";
		exit;
	}
}


//--------------------------------------------------------------------------------------------
  

//--------------強制下載檔案
//$file:檔名
function dl_file($file)
{
	//First, see if the file exists
	if (!is_file($file)) { die("<b>404 File not found!</b>"); }
	
	//Gather relevent info about file
	$len = filesize($file);
	$filename = basename($file);
	$file_extension = strtolower(substr(strrchr($filename,"."),1));
	
	//This will set the Content-Type to the appropriate setting for the file
	switch( $file_extension ) {
	case "pdf": $ctype="application/pdf"; break;
	case "exe": $ctype="application/octet-stream"; break;
	case "zip": $ctype="application/zip"; break;
	case "txt": $ctype="application/txt"; break;
	case "doc": $ctype="application/msword"; break;
	case "xls": $ctype="application/vnd.ms-excel"; break;
	case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	case "gif": $ctype="image/gif"; break;
	case "png": $ctype="image/png"; break;
	case "jpeg":
	case "jpg": $ctype="image/jpg"; break;
	case "mp3": $ctype="audio/mpeg"; break;
	case "wav": $ctype="audio/x-wav"; break;
	case "mpeg":
	case "mpg":
	case "mpe": $ctype="video/mpeg"; break;
	case "mov": $ctype="video/quicktime"; break;
	case "avi": $ctype="video/x-msvideo"; break;
	
	//The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
	case "php":
	case "htm":
	case "html":die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;
	
	default: $ctype="application/force-download";
	}
	
	//Begin writing headers
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public"); 
	header("Content-Description: File Transfer");
	
	//Use the switch-generated Content-Type
	header("Content-Type: $ctype");
	
	//Force the download
	$header="Content-Disposition: attachment; filename=".$filename.";";
	header($header );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$len);
	@readfile($file);
	exit;
}
//------------










/* 
$arr = 我給你的comb.json
$scores = 科系加權方式的陣列; // ex: $scores = array("國文" => 1.5, "英文" => 1, "數學" => 2);
$score_arr = $arr['score'];
$studentscore = 考生依該科系加權之後加起來的總分; // ex: 211.52
$percent = get_guess_percent($scores, $score_arr, $studentscore);

$percent即可算出單一考生對單一科系的加權方式算出來的百分比，以下為兩個會用到的函式。
*/

function get_guess_percent($scores, $score_arr, $lastscore){
	$sum = 0;
	for($s = 0; $s <= 100; $s++){
		$lastsum = $sum;
		$sum = 0;
		foreach($scores as $key => $val){
			$tmp = get_history_data($s, $score_arr[$key][0], $score_arr[$key]);
			$sum += $tmp['score'] * $val;
		}

		if($lastscore >= $sum)
			break;
	}

	$tmp_distance = $lastscore - $sum;
	$tmp_wholedis = $lastsum - $sum;
	//echo "tmp_wholedis:" . $tmp_wholedis; // BUG 2012 - 10/11 除數為零
	$tmp_percent = 0;
	if($tmp_wholedis != 0){
		$tmp_percent = $tmp_distance / $tmp_wholedis;
	}
	
	$ans = $s - $tmp_percent;

	return $ans;
}

function get_history_data($now_percent, $rank_max, $score_arr, $comb = false){
	$rank = floor($now_percent * $rank_max / 100);
	if($rank == 0)
		$rank = 1;
	
	$start = 100;
	if($comb)
		$start = 99;
	
	for($s = $start; $s >= 0; $s--){
		if($rank <= $score_arr[$s]){
			if($s == 100)
				$guess = 100;
			else{
				$tmp_distance = $score_arr[$s] - $score_arr[$s + 1];
				$tmp_decimal  = $score_arr[$s] - $rank;
				$tmp_percent  = $tmp_decimal / $tmp_distance;
				$guess = $s + $tmp_percent;
			}
			$guess_round = round($guess, 2);
			break;
		}
	}
	
	return array("score" => $guess_round, "rank" => $rank);
}



?>