<?php
/***
 * very Little php API 說明
 * validateForm
 ***/

/**
 * validateForm 
 * version     0.0.3
 * 說明 2013 01/23 [cake x]
 * 判斷 是否成立 需要增加 $_SERVER["REQUEST_METHOD"] == 'POST' 或者其他 判斷是否為POST狀態
 * 非POST狀態 formSuccess 將會回傳 FALSE
***/
$cForm = new validateForm();
		$cForm->setRule( 'form_txt1', "姓名", 'max_length[30]|sql' );
		$cForm->runValidation();
		if ( !$cForm->formSuccess() and $_SERVER["REQUEST_METHOD"] == 'POST'){
			$_msg =  $cForm->displayTextError();
			 // echo "msg:" . $_msg;
			echo alertMsg($_msg, " window.location.href='".$_back_url."' " );
 		    exit ;
		}


/**
 * validateForm
 * version     0.5
 * 說明 2013 01/31 [cake x]
 * 1.修改為可以自定POST OR GET 格式,未來將會加入 自定ARRAY
 * 2.可不需使用 $_SERVER["REQUEST_METHOD"] 來判斷狀態
 ***/

$cPost = new validateForm();
		$cGet  = new validateForm();
		$cPost->setRule( 'search_tag', "選擇項目", 'max_length[30]|sql' );
		$cPost->setRule( 'form_txt1', "搜尋條件", 'max_length[30]|sql' );
		$cGet->setRule( 'search_tag', "選擇項目", 'max_length[30]|sql' );
		$cGet->setRule( 'form_txt1', "搜尋條件", 'max_length[30]|sql' );
		if ( !$cPost->runValidation("POST") or !$cGet->runValidation("GET") ){
			$_msg =  $cPost->displayTextError() . $cGet->displayTextError();
			  // echo "msg:" . $_msg;
			echo alertMsg($_msg, " window.location.href='".$_back_url."' " );
 		    exit ;
		}
?>