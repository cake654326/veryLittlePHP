<?php

if ( ! function_exists( 'notePages' ) )
{

/**
$_config['total_rows'] = false;
$_config['offset'] = false;

$_config['url_str'] = false;
$_config['class'] = false;
$_config['mod'] = false;

class = page 
**/

	function notePages( 
		$_title_url , 
		$total_rows, $offset, $limit_row, 
		$url_str = '',  $_href_base = "href" , $_tag_array = array() 
		){
			$sAtt = '';
			foreach ( (array)$_tag_array as $key => $val ) {
				$sAtt .= $key . "='" . $val . "'";
			}

		    $current_page = ( (float)$offset / (float)$limit_row ) + 1;
		    $total_pages  = ceil( $total_rows / $limit_row );

			$str2 = "";

			$i = ceil( $current_page / 10 ) - 1;
// echo "<br/><br/><br/><br/>" . $current_page;

			if ( $i >= 1 ) {
			    $of = max( 0, $offset - ( $limit_row * 10 ) );
			    $str2 .= "
			    <li class='disabled'>
			    <a $_href_base=\"$_title_url?offset=$of&$url_str\" $sAtt >上10頁</a>
			    </li> 
			    ";
			}


			$a = min( $total_pages, ( $i * 10 ) + 10 );
			for ( $i = 1 + ( $i * 10 ); $i <= $a; $i++ ) {
			    $of = $i * $limit_row - $limit_row;
			    if ( $i == (int)$current_page )
			        $str2 .= '
			    	<li class="active">
			    	<a href="#">' . $i . '</a>
			    	</li>
			    	';

			    else
			        $str2 .= "
			    	<li class='no_active'>
			    	<a $_href_base=\"$_title_url?offset=$of&$url_str\" $sAtt >$i</a>
			    	</li> 
			    	";
			}

			if ( $i < $total_pages ) {

			    $of = min( $total_rows, $offset + ( $limit_row * 10 ) );

			    $str2 .= "
			    <li class='disabled'>
			    <a $_href_base=\"$_title_url?offset=$of&$url_str\" $sAtt >下10頁</a>
			    </li>
			    ";

			}

		    return $str2;


	}


}


