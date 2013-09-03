<?php

/**
 * form helper 
 *
 * @package     form helper 
 * @author      Cake X
 * @link        https://github.com/cake654326/
 *
 * @history
 *          2013 - 05/02
 *              + form_YMD
 *
 ***/


if ( ! function_exists( '__cx_array_to_string' ) ) {
	function __cx_array_to_string( $_arr ) {
		$att = '';
		foreach ( $_arr as $key => $val ) {
			$att .= $key . "='" . $val . "'";
		}
		return $att;
	}
}

if ( ! function_exists( 'form_tag' ) ) {

	function form_tag( $_tag, $_arr, $_val , $_click = '' ) {
		return "<" . $_tag . " " . __cx_array_to_string( $_arr ) . " ".$_click.">" . $_val . "</" . $_tag . ">";
	}

}

if ( ! function_exists( 'form_YMD' ) ) {
	/***
	 * form_YMD()
	 * $_type => y , m , d
	 * 
	 EX:
	 $present_job_date  格式可以為 2012-08-25 or 純粹數字
	 echo form_YMD('Y', array( 'name'=>'present_job_date_Y','id'=>'present_job_date_Y' ) , 
			  					$present_job_date , array('start'=>1950,'end'=>2015) );
	 ***/
	function form_YMD( $_type , $_tag_array , $_select , $_time_between = array('start'=>null,'end'=>null) ) {
		( $_select == '' ) and $_select = null;
		$_type = strtolower($_type);
		($_type == 'y') and $_type = 'Y';
		if($_select != null){
			// if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$_select)){
			if( preg_match('/^\d+\.\d+$/',$_select) ){
				// number
			}else{
				$_select = date($_type, strtotime($_select) );
			}
		}

		
		$_tag = 0;
		$_default_time = array();
		$_default_time['Y']['start'] = 1950;
		$_default_time['Y']['end'] = 2050;
		$_default_time['m']['start'] = 1;
		$_default_time['m']['end'] = 12;
		$_default_time['d']['start'] = 1;
		$_default_time['d']['end'] = 31;
		$_str = '';
		$_start =$_time_between['start'];
		$_end = $_time_between['end'];
// print_cx($_time_between);exit();
		if( $_start == null or $_start == ''  ){
			$_start = $_default_time[$_type]['start'] ;
		}
		if( $_end == null or $_end == ''  ){
			$_end = $_default_time[$_type]['end'] ;
		}


		for( $i = $_start;$i <= $_end;$i++ ){
			// value="11" selected="selected"
			$_arr = array( 'value'=>$i );
			if( ( $_select != null ) and $i == (int)$_select ){
				$_arr['selected'] = 'selected';
				$_tag = 1;
			}
			$_str .= form_tag( "option", $_arr , $i );
		}
		if( $_tag != 1 ){
			$_str .=
				form_tag( "option", array( 'value'=>'' , 'selected'=>'selected' ) , '請選擇' );
		}
		// <select name="y"> => array( 'name'=>$_name )  => $_tag_array
		return form_tag( "select", $_tag_array, $_str );
	}

}



?>
