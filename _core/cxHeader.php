<?php
// error_reporting (0);


//debug header
//cxHeader.php
global $Core;
if ( $Core->config( 'CXDEBUG' ) ) {
	set_error_handler ('cx_error_handler');
	register_shutdown_function("shutdownHandler");
}

/**
 * Error_Handler
 * 
 * @param $error_level 錯誤等級
 * @param $error_message 错误訊息
 * @param $file 文件
 * @param $line 行數
 *
 */
function cx_error_handler($error_level, $error_message, $file, $line) {

    $EXIT = FALSE;
// echo $error_level;
    $error_type = '';
    $error_title = '';
    $error_level = 0;
    switch ($error_level) {

        case E_NOTICE:
        case E_USER_NOTICE:
        	$error_level = 1;
        	$error_title = "INFO";
            $error_type = 'Notice';
            $msg = $error_message;
            printf ("<font color='#ff0000'><b>%s</b></font>: %s in <b>%s</b> on line <b>%d</b><br /><br />\n", $error_type, $error_message, $file, $line);

            break;
		
        case E_WARNING:
        case E_USER_WARNING:
        	$error_level = 3;
        	$error_title = "WARNING";
            $error_type = 'Warning';
            $msg = $error_message;
            printf ("<font color='#ff0000'><b>%s</b></font>: %s in <b>%s</b> on line <b>%d</b><br /><br />\n", $error_type, $error_message, $file, $line);

            break;
		
        case E_ERROR:
        case E_USER_ERROR:
        	$error_level = 4;
        	$error_title = "ERROR";
            $error_type = 'Fatal Error';
            $msg = $error_message;
            printf ("<font color='#ff0000'><b>%s</b></font>: %s in <b>%s</b> on line <b>%d</b><br /><br />\n", $error_type, $error_message, $file, $line);
            $EXIT = TRUE;
            break;
		
        default:
        	$error_level = 3;
        	$error_title = "INFO";
            $error_type = 'Unknown';
            $msg = $error_message;
            $EXIT = TRUE;
            break;
    }
    global $Core;
    $Core->debugGroupCollapsed($error_title,$error_type,$error_level);
	$Core->debugLog( $error_title ,$error_type, array("msg", $error_message) );
	// $Core->debugLog( "ERROR" , "Backtrace", new cx_Exception( "ERROR","Backtrace",2) ,5);
	$Core->debugGroupEnd(); 
	
	// printf ("<font color='#ff0000'><b>%s</b></font>: %s in <b>%s</b> on line <b>%d</b><br /><br />\n", $error_type, $error_message, $file, $line);

 	// if (TRUE == $EXIT) {
	// 	echo "<script language='Javascript'>location='err.html'; </script>";
	// }
        
}
function shutdownHandler() {
// header("Content-Type:text/html; charset=utf-8");
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
$lasterror = error_get_last();
	switch ($lasterror['type']){
		case E_ERROR:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
		case E_PARSE:

		$error = "[SHUTDOWN] lvl:" 
		. $lasterror['type'] . " | msg:" 
		. $lasterror['message'] . " | file:" 
		. $lasterror['file'] . " | ln:" . $lasterror['line'];
		echo $error;

		$_ERROR_DATA = array();
		$_ERROR_DATA['LEVEL'] =  $lasterror['type'];
		$_ERROR_DATA['cxSql;MESSAGE'] =  $lasterror['message'] ;
		$_ERROR_DATA['FILE'] =   $lasterror['file'];
		$_ERROR_DATA['lINE'] =  $lasterror['line'];
		$_ERROR_DATA["cxFileCode;Source;PHP;".$_ERROR_DATA['lINE'].";".$_ERROR_DATA['FILE']] = '';
		global $Core;
		$Core->debugGroupCollapsed("ERROR","嚴重錯誤",5);
		$Core->debugLog( "ERROR" ,"詳細訊息", $_ERROR_DATA ,5);
		// $Core->debugLog( "ERROR" , "Backtrace", new cx_Exception( "ERROR","Backtrace",2) ,5);
		$Core->debugGroupEnd(); 

		
	}
}