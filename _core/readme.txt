<?php
/**
 * validateForm 說明 2013 01/23 [cake x]
 * 判斷 是否成立 需要增加 $_SERVER["REQUEST_METHOD"] == 'POST' 或者其他 判斷是否為POST狀態
 * 非POST狀態 formSuccess 將會回傳 FALSE

$cForm = new validateForm();
		$cForm->setRule( 'form_txt1', "姓名", 'max_length[30]|sql' );
		$cForm->runValidation();
		if ( !$cForm->formSuccess() and $_SERVER["REQUEST_METHOD"] == 'POST'){
			$_msg =  $cForm->displayTextError();
			 // echo "msg:" . $_msg;
			echo alertMsg($_msg, " window.location.href='".$_back_url."' " );
 		    exit ;
		}

?>