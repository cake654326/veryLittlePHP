<?php

/**
 * help public function
 *
 * @package     Cake-help-function
 * @author      Cake X
 * @link        https://github.com/cake654326/
 * @version     0.0.2
 *
 * @history
 *          2012 - 10
 *              + is_tw_id
 *              + is_email
 *              + pages2
 *
 *          2012 - 11
 *              + print_md
 *              + pagination_array
 *              + pagination_array2
 *              + getMicrotime
 *
 *
 * ***** Function *********
 * cx_checkValue              = 檢查字串 格式
 * cx_getHtmlTag              = 建立 html <tag> 碼
 * generatorPassword          = 亂數密碼
 * cxLetterAdd                = 取得下一個字母序號
 * arrayToUpdate              = 建立SQL UPDATE ARRAY TO STRING
 * arrayToInsert              = 建立SQL Insert ARRAY TO STRING
 * pages(bootstrap) && pages2 = 建立分頁
 * is_email                   = 檢查是否為密碼
 * is_tw_id                   = 驗證 身份證字號 格式
 * print_cx                   = print_r have pre tag to string
 * print_md                   = print_r have pre tag and array string is php code
 * pagination_array           = 建立分頁 ARRAY版
 * pagination_array2          = 建立分頁 ARRAY版 bootstrap
 * getMicrotime               = 取得時間
 * *************************
 *
 * * *******/


/* -=================================================-
判斷輸入字串是否符合格式,若不合會回傳false,
所有$_POST $_GET必需要先做此檢查!!
$Str = 要檢查的字串
$Type = 字串格式 0:不檢查 1:純英文 2:純數字 3:英文混合數字
$len = 字串長度 mb_strlen($str,'utf-8')
-=================================================- */
function cx_checkValue( $Input, $Type='', $len='' ) {
    $Str = trim( $Input ); //清空前後空白
    $Clean_Str = $Str;
    if ( !get_magic_quotes_gpc() )$Clean_Str = mysql_real_escape_string( $Str );//消去危險字元
    if ( $len && strlen( $Clean_Str ) > $len ) { //最大字串長度檢查,若太大就切斷後面的
        $Str = substr( $Clean_Str, 0, $len );
    }
    $Chk = 1;
    switch ( $Type ) {
    case '1':   //英文
        $Chk = ctype_alpha( $Clean_Str );
        break;
    case '2':   //數字
        $Chk = ctype_digit( $Clean_Str );
        break;
    case '3':   //英數混合
        $Chk = ctype_alnum( $Clean_Str );
        break;
    default:
        $Chk = 1;
        break;
    }
    if ( $Chk ) {
        return $Clean_Str;
    }else {
        return false;
    }
}

function _cx_array_to_string( $_arr ) {
    $att = '';
    foreach ( $_arr as $key => $val ) {
        $att .= $key . "='" . $val . "'";
    }
    return $att;
}

function cx_getHtmlTag( $_tag, $_arr, $_val ,$_click = '') {
    return "<" . $_tag . " " . _cx_array_to_string( $_arr ) . " ".$_click.">" . $_val . "</" . $_tag . ">";
}

function generatorPassword() {
    $password_len = 5;
    $password     = '';

    // remove
    $word = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
    $len  = strlen( $word );

    for ( $i = 0; $i < $password_len; $i++ ) {
        $password .= $word[rand() % $len];
    }

    return $password;
}

//下個字母字串
function cxLetterAdd( $s ) {
    $Str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $len = strlen( $s );
    $i   = 1;
    do {
        $a2  = substr( $s, $len - $i, 1 );
        $pos = strpos( $Str, $a2 ) + 1;
        $b2  = $pos > 25 ? "A" : $Str[$pos];
        $a1  = $len == $i ? ( $b2 == "A" ? "A" : "" ) : substr( $s, 0, $len - $i );
        $a3  = $i == 1 ? "" : substr( $s, $len - $i + 1 );
        $s   = $a1 . $b2 . $a3;
        $i++;
    } while ( $b2 == "A" && $len > $i - 1 );

    return $s;
}

function arrayToUpdate( $info, $table, $where ) {
    if ( !is_array( $info ) ) {
        die( "insert member failed, info must be an array" );
    }
    $sql = "UPDATE " . $table . " SET ";
    for ( $i = 0; $i < count( $info ); $i++ ) {
        $sql .= key( $info ) . " = '" . current( $info ) . "' ";

        if ( $i < ( count( $info ) - 1 ) ) {
            $sql .= ", ";
        } else
            $sql .= " "; //)

        next( $info );
    }
    $sql .= " WHERE " . $where;
    return $sql;
}

function arrayToInsert( $info, $table ) {
    if ( !is_array( $info ) ) {
        die( "insert member failed, info must be an array" );
    }
    $sql = "INSERT INTO " . $table . " (";
    for ( $i = 0; $i < count( $info ); $i++ ) {
        //we need to get the key in the info array, which represents the column in $table
        $sql .= key( $info );
        //echo commas after each key except the last, then echo a closing parenthesis
        if ( $i < ( count( $info ) - 1 ) ) {
            $sql .= ", ";
        } else
            $sql .= ") ";
        //advance the array pointer to point to the next key
        next( $info );
    }
    //now lets reuse $info to get the values which represent the insert field values
    reset( $info );
    $sql .= "VALUES (";
    for ( $j = 0; $j < count( $info ); $j++ ) {
        $sql .= "'" . current( $info ) . "'";
        if ( $j < ( count( $info ) - 1 ) ) {
            $sql .= ", ";
        } else
            $sql .= ") ";
        next( $info );
    }

    return $sql;
}

/*******
offset is page number
$offset = $_REQUEST["offset"] == "" ? 0 ; $_REQUEST["offset"];
$mrows = $conn->Execute("select count(a_account) as T from $TABLE_NAME $WHERE_STR ");
$mrow = $mrows->FetchObject();
$numrows = $mrow->T;
$pagestr = pages($numrows, $offset, $num_of_rows_per_page,"type=$type","no9", 2);
print $pagestr; // 印出分頁
$rows = $conn->SelectLimit($sql,$num_of_rows_per_page,$offset);
while ($row = $rows->fetchRow()) {
// 略 這裡是SHOW 資料
}
print $pagestr;

http://peeress.pixnet.net/blog/post/27760247
********/
function pages( $total_rows, $offset, $limit_row, $url_str = '', $class = "page", $mod = "2" ) {
    $current_page = ( $offset / $limit_row ) + 1;
    $total_pages  = ceil( $total_rows / $limit_row );
    if ( $mod == "1" ) {
        $current_page = ( $offset / $limit_row ) + 1;
        $total_pages  = ceil( $total_rows / $limit_row );
        $str2         = "";
        if ( $offset != 0 )
            $str2 .= "| <li class='disabled'><a href=\"$PHP_SELF?offset=0&$url_str\" class=\"$class\">FirstPage</a></li> | ";
        if ( ( $offset - $limit_row ) >= 0 ) {
            $prev_offset = $offset - $limit_row;
            $str2 .= " <li class='disabled'><a href=\"$PHP_SELF?offset=$prev_offset&$url_str\" class=\"$class\">Previous</a></li> | ";
        }
        $str2 .= " [ $current_page / $total_pages ] ";
        $last_row = ( ( $total_pages - 1 ) * $limit_row );
        if ( ( $offset + $limit_row ) < $total_rows ) {
            $next_offset = $offset + $limit_row;
            $str2 .= "<li class='disabled'><a href=\"$PHP_SELF?offset=$next_offset&$url_str\" class=\"$class\">NEXT</a></li> | ";
            $str2 .= "<li class='disabled'><a href=\"$PHP_SELF?offset=$last_row&$url_str\" class=\"$class\">LastPage</a></li> | ";
        }
    } elseif ( $mod == "2" ) {
        $str2 = "";
        $i    = ceil( $current_page / 10 ) - 1;

        if ( $i >= 1 ) {
            $of = max( 0, $offset - ( $limit_row * 10 ) );
            $str2 .= "<li class='disabled'><a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">上10頁</a></li> ";
        }
        $a = min( $total_pages, ( $i * 10 ) + 10 );
        for ( $i = 1 + ( $i * 10 ); $i <= $a; $i++ ) {
            $of = $i * $limit_row - $limit_row;
            if ( $i == $current_page )
                //$str2 .= "[ $i ] ";
                $str2 .= '<li class="active"><a href="#">' . $i . '</a></li>';
            //$str2 .= " $i  ";
            else
                $str2 .= "<li class='no_active'><a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">$i</a></li> ";
        }
        if ( $i < $total_pages ) {
            $of = min( $total_rows, $offset + ( $limit_row * 10 ) );
            $str2 .= "<li class='disabled'><a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">下10頁</a></li>";
        }
    } else {
        $str2 = "Page:";
        for ( $i = 1; $i <= $total_pages; $i++ ) {
            $of = $i * $limit_row - $limit_row;
            if ( $i == $current_page )
                $str2 .= "[ $i ] ";
            else
                $str2 .= "<li class='disabled'><a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">$i</a></li>";
        }

    }
    return $str2;
    //return "<span class=$class>$str2</span>";
}


function pages2( $total_rows, $offset, $limit_row, $url_str='', $class="page", $mod="2" ) {
    $current_page = ( $offset/$limit_row ) + 1;
    $total_pages = ceil( $total_rows/$limit_row );
    if ( $mod == "1" ) {
        $current_page = ( $offset/$limit_row ) + 1;
        $total_pages = ceil( $total_rows/$limit_row );
        $str2 = "";
        if ( $offset != 0 ) $str2 .="| <a href=\"$PHP_SELF?offset=0&$url_str\" class=\"$class\">FirstPage</a> | ";
        if ( ( $offset - $limit_row ) >= 0 ) {
            $prev_offset = $offset - $limit_row;
            $str2 .= " <a href=\"$PHP_SELF?offset=$prev_offset&$url_str\" class=\"$class\">Previous</a> | ";
        }
        $str2 .= " [ $current_page / $total_pages ] ";
        $last_row = ( ( $total_pages-1 ) * $limit_row );
        if ( ( $offset + $limit_row ) < $total_rows ) {
            $next_offset = $offset + $limit_row;
            $str2 .= "<a href=\"$PHP_SELF?offset=$next_offset&$url_str\" class=\"$class\">NEXT</a> | ";
            $str2 .= "<a href=\"$PHP_SELF?offset=$last_row&$url_str\" class=\"$class\">LastPage</a> | ";
        }
    }
    elseif ( $mod == "2" ) {

        $str2 = "";
        $i = ceil( $current_page / 10 ) - 1 ;

        if ( $i >= 1 ) {
            $of = max( 0, $offset - ( $limit_row * 10 ) ) ;
            $str2.= "<a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">上10頁</a> ";
        }
        $a=min( $total_pages, ( $i*10 )+10 );
        for ( $i = 1+( $i*10 ); $i <= $a; $i++ ) {
            $of = $i * $limit_row - $limit_row;
            if ( $i == $current_page )
                $str2.= "[ $i ] ";
            else
                $str2.= "<a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">$i</a> ";
        }
        if ( $i < $total_pages ) {

            $of = min( $total_rows, $offset + ( $limit_row * 10 ) );
            $str2.= "<a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">下10頁</a>";
        }
    }
    else {
        $str2 = "Page:";
        for ( $i =1; $i <= $total_pages; $i++ ) {
            $of = $i * $limit_row - $limit_row;
            if ( $i == $current_page )
                $str2.= "[ $i ] ";
            else
                $str2.= "<a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">$i</a> ";
        }

    }
    return "<span class=$class>$str2</span>";
}

function pages_css( $total_rows, $offset, $limit_row, $url_str='', $class="page", $mod="2" ,$_actionClass = '_cx_action' ) {
    $current_page = ( $offset/$limit_row ) + 1;
    $total_pages = ceil( $total_rows/$limit_row );
    if ( $mod == "1" ) {
        $current_page = ( $offset/$limit_row ) + 1;
        $total_pages = ceil( $total_rows/$limit_row );
        $str2 = "";
        if ( $offset != 0 ) $str2 .="| <a href=\"$PHP_SELF?offset=0&$url_str\" class=\"$class\">FirstPage</a> | ";
        if ( ( $offset - $limit_row ) >= 0 ) {
            $prev_offset = $offset - $limit_row;
            $str2 .= " <a href=\"$PHP_SELF?offset=$prev_offset&$url_str\" class=\"$class\">Previous</a> | ";
        }

        // $str2 .= " [ $current_page / $total_pages ] ";
        $str2 .= "<a href='#' class='".$_actionClass."'>  $current_page / $total_pages  </a>";
        //_actionClass <a href="?page=1" class="current">1</a>
        $last_row = ( ( $total_pages-1 ) * $limit_row );
        if ( ( $offset + $limit_row ) < $total_rows ) {
            $next_offset = $offset + $limit_row;
            $str2 .= "<a href=\"$PHP_SELF?offset=$next_offset&$url_str\" class=\"$class\">NEXT</a> | ";
            $str2 .= "<a href=\"$PHP_SELF?offset=$last_row&$url_str\" class=\"$class\">LastPage</a> | ";
        }
    }
    elseif ( $mod == "2" ) {

        $str2 = "";
        $i = ceil( $current_page / 10 ) - 1 ;

        if ( $i >= 1 ) {
            $of = max( 0, $offset - ( $limit_row * 10 ) ) ;
            $str2.= "<a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">上10頁</a> ";
        }
        $a=min( $total_pages, ( $i*10 )+10 );
        for ( $i = 1+( $i*10 ); $i <= $a; $i++ ) {
            $of = $i * $limit_row - $limit_row;
            if ( $i == $current_page )
                // $str2.= "[ $i ] ";
                $str2 .= "<a href='#' class='".$_actionClass."'>  $i  </a>";
            else
                $str2.= "<a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">$i</a> ";
        }
        if ( $i < $total_pages ) {

            $of = min( $total_rows, $offset + ( $limit_row * 10 ) );
            $str2.= "<a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">下10頁</a>";
        }
    }
    else {
        $str2 = "Page:";
        for ( $i =1; $i <= $total_pages; $i++ ) {
            $of = $i * $limit_row - $limit_row;
            if ( $i == $current_page )
                //$str2.= "[ $i ] ";
                $str2 .= "<a href='#' class='".$_actionClass."'>  $i  </a>";
            else
                $str2.= "<a href=\"$PHP_SELF?offset=$of&$url_str\" class=\"$class\">$i</a> ";
        }

    }
    return "<span class=$class>$str2</span>";
}

function is_email( $str ) //驗證 E-mail 格式
{
    if ( ereg( "^[A-Za-z0-9\.\-]+@[A-Za-z0-9]+\.[A-Za-z0-9\.]+$", $str ) ) {
        return 1;
    } else {
        return 0;
    }
}

function is_tw_id( $id ) //驗證 身份證字號 格式
{
    //建立字母分數陣列
    $head = array( 'A'=>10, 'B'=>11, 'C'=>12, 'D'=>13, 'E'=>14, 'F'=>15,
        'G'=>16, 'H'=>17, 'I'=>34, 'J'=>18, 'K'=>19, 'L'=>20,
        'M'=>21, 'N'=>22, 'O'=>35, 'P'=>23, 'Q'=>24, 'R'=>25,
        'S'=>26, 'T'=>27, 'U'=>28, 'V'=>29, 'W'=>32, 'X'=>30,
        'Y'=>31, 'Z'=>33 );
    //檢查身份字格式是否正確
    if ( ereg( "^[A-Za-z][1-2][0-9]{8}$", $id ) ) {
        //切開字串
        for ( $i=0; $i<10; $i++ ) {
            $idArray[$i] = substr( $id, $i, 1 );
        }
        $idArray[0] = strtoupper( $idArray[0] ); //小寫轉大寫
        //取得字母分數&建立加權分數
        $a[0] = substr( $head[$idArray[0]], 0, 1 );
        $a[1] = substr( $head[$idArray[0]], 1, 1 );
        $total = $a[0]*1+$a[1]*9;
        //取得數字分數&建立加權分數
        for ( $j=1; $j<=8; $j++ ) {
            $total += $idArray[$j]*( 9-$j );
        }
        //檢查比對碼
        if ( $total%10 == 0 ) {
            $checksum = 0;
        } else {
            $checksum = 10-$total%10;
        }
        if ( $idArray[9] == $checksum ) {
            return true;
        } else {
            return false;
        }
        return false;
    }
}

/**
 * 檢查是否為合法的日期格式
 *
 * @param unknown $str    日期字串
 * @param unknown $retype 傳回格式化資料
 * @return $retype = 0 ,boolean
 *         $retype = 1 ,0000-00-00
 * */

function is_date( $str, $retype=0 ) {

    $reVal = "";
    $dateArr = explode( "-", $str );
    if ( count( $dateArr ) != 3 ) return false;
    $year  = $dateArr[0];
    $month = $dateArr[1];
    $day   = $dateArr[2];

    switch ( $retype ) {
    case 1:
        if ( checkdate( $month, $day, $year ) ) {$reVal = sprintf( "%04d-%02d-%02d", $year, $month, $day );}else {$reVal = false;}
        break;
    default:$reVal = checkdate( $month, $day, $year );break;
    }
    return $reVal;
}

/***
 * print_r have pre tag to string
 ****/
function print_cx( $_arr, $_b = false ) {
    $_str =  "<pre>";
    $_str .= print_r( $_arr, true );
    $_str .=  "</pre>";

    if ( $_b == true ) {
        return $_str;
    }else {
        echo $_str;
        return $_str;
    }
}

/***
 * array to get url
 * $_arr is your $mGet array value
 * $_name is private value default is null
 ***/
function arrayToUrl( $_arr, $_name = null ) {
    $_str = '';
    $_arr2 = array();

    foreach ( (array)$_arr as $_k => $_v ) {
        if ( $_name != null ) {
            $_n = $_name;
        }else {
            $_n = $_k;
        }
        if ( is_array( $_v ) ) {
            $_arr2[] =arrayToUrl($_v,$_k."[]");
            }else {
            $_arr2[] = $_n . "=" . $_v;
        }

    }
    return implode( '&', $_arr2 );
}

/***
 * mdarray_to_string ( $ARRAY )
 * array to php array code string
 * ex: echo "<pre>$new_code = ".mdarray_to_string($existing_array)."</pre>";
 ****/
function print_md( $array , $depth=0 ) {
    return mdarray_to_string( $array, $depth );
}

function mdarray_to_string( $array, $depth=0 ) {
    //print_md function
    $string .= "array( \n";
    $depth++;
    foreach ( $array as $key => $val ) {
        $string .=  get_indent_space( $depth ).quote_type_wrap( $key ). ' => ';
        if ( is_array( $val ) ) {
            $string .= mdarray_to_string( $val, $depth ).",\n";
        }
        else
            $string .= quote_type_wrap( $val ).",\n";
    }
    $depth--;
    $string .= get_indent_space( $depth ).")";
    return $string;
}

function get_indent_space( $depth ) {
    //print_md function
    $output = '';
    for ( $i=0;$i<=$depth;$i++ )
        $output.='  ';
    return $output;
}

function quote_type_wrap( $var ) {
    //print_md function
    switch ( gettype( $var ) ) {
    case 'string':
        return '"'.$var.'"';
    case 'NULL':
        return "null";
        //TODO: handle other variable types.. ( objects? )
    default :
        return $var;
    }
}


/**------------------------------------------------------------
 * Array Pagination Function.
 * By Sergey Gurevich.
 *
 * Input:
 * 1 - Target Array.
 * 2 - Page Number.
 * 3 - Link prefix (example: "?page=").
 * 4 - Link suffix.
 * 5 - Results per page.
 * 6 - Number of pages displayed in the page link panel.
 *
 * Output:
 * - $output['panel'] - Link Panel HTML source.
 * - $output['offset'] - Current page number.
 * - $output['limit'] - Number of resuts per page.
 * - $output['array'] = - Array of current page results.
 *
 * //Creating dummy array.
 * for ($i = 1; $i <= 1000; $i++)
 * {
 * $array[] = "Result #$i";
 * }
 * //Getting currect page.
 * $page = $_GET['page'];
 * //Applying pagination.
 * $pagination = pagination_array($array, $page, "?page=");
 * //Page link panel.
 * echo $pagination['panel'];
 * //Displayed page results.
 * echo '<pre>';
 * print_r($pagination['array']);
 * echo '</pre>';
 * //Page link panel.
 * echo $pagination['panel'];
 */
function pagination_array( $array, $page = 1, $link_prefix = false, $link_suffix = false, $limit_page = 20, $limit_number = 10 ) {
    if ( empty( $page ) or !$limit_page ) $page = 1;

    
    $num_rows = count( $array );
    //if ( !$num_rows or $limit_page >= $num_rows ) return false;
    if ( !$num_rows or $limit_page >= $num_rows ){
        //array_values
        $output['panel'] = "";
        $output['offset'] = 1; 
        $output['limit'] = $limit_page; 
        $output['array'] = array_values( (array)$array );
        return $output;
    }


    $num_pages = ceil( $num_rows / $limit_page );
    $page_offset = ( $page - 1 ) * $limit_page;

    //Calculating the first number to show.
    if ( $limit_number ) {
        $limit_number_start = $page - ceil( $limit_number / 2 );
        $limit_number_end = ceil( $page + $limit_number / 2 ) - 1;
        if ( $limit_number_start < 1 ) $limit_number_start = 1;
        //In case if the current page is at the beginning.
        $dif = ( $limit_number_end - $limit_number_start );
        if ( $dif < $limit_number ) $limit_number_end = $limit_number_end + ( $limit_number - ( $dif + 1 ) );
        if ( $limit_number_end > $num_pages ) $limit_number_end = $num_pages;
        //In case if the current page is at the ending.
        $dif = ( $limit_number_end - $limit_number_start );
        if ( $limit_number_start < 1 ) $limit_number_start = 1;
    }
    else {
        $limit_number_start = 1;
        $limit_number_end = $num_pages;
    }
    //Generating page links.
    for ( $i = $limit_number_start; $i <= $limit_number_end; $i++ ) {
        $page_cur = "<a href='$link_prefix$i$link_suffix'>$i</a>";
        if ( $page == $i ) $page_cur = "<b>$i</b>";
        else $page_cur = "<a href='$link_prefix$i$link_suffix'>$i</a>";

        $panel .= " <span>$page_cur</span>";
    }

    $panel = trim( $panel );
    //Navigation arrows.
    if ( $limit_number_start > 1 ) $panel = "<b><a href='$link_prefix".( 1 )."$link_suffix'>&lt;&lt;</a>  <a href='$link_prefix".( $page - 1 )."$link_suffix'>&lt;</a></b> $panel";
    if ( $limit_number_end < $num_pages ) $panel = "$panel <b><a href='$link_prefix".( $page + 1 )."$link_suffix'>&gt;</a> <a href='$link_prefix$num_pages$link_suffix'>&gt;&gt;</a></b>";

    $output['panel'] = $panel; //Panel HTML source.
    $output['offset'] = $page_offset; //Current page number.
    $output['limit'] = $limit_page; //Number of resuts per page.
    $output['array'] = array_slice( $array, $page_offset, $limit_page, true ); //Array of current page results.

    return $output;
}

/***
.pagination-large, .pagination-small, or .pagination-mini
<div class="pagination pagination-small">
***
    <div class="pagination">
    <ul>
    <li class="disabled"><span>Prev</span></li>
    <li class="active"><span>1</span></li>
<li class="disabled"><a href="#">Prev</a></li>
<li class="active"><a href="#">1</a></li>
    ...
    </ul>
    </div>
    cx_getHtmlTag("div",array("class"=>"pagination"),"<ul>" . $pagestr . "</ul>");

***/

function cx_pagination_array( $array, $page = 1, $link_prefix = false, $link_suffix = false, $limit_page = 20, $limit_number = 10 ) {
    if ( empty( $page ) or !$limit_page ) $page = 1;
//print_cx($array);
    $num_rows = count( $array );
    //echo $num_rows;
    if ( !$num_rows or $limit_page >= $num_rows ){
        //array_values
        $output['panel'] = "";
        $output['offset'] = 1; 
        $output['limit'] = $limit_page; 
        $output['array'] = array_values( $array );
        return $output;
    }
    $num_pages = ceil( $num_rows / $limit_page );
    $page_offset = ( $page - 1 ) * $limit_page;

    //Calculating the first number to show.
    if ( $limit_number ) {
        $limit_number_start = $page - ceil( $limit_number / 2 );
        $limit_number_end = ceil( $page + $limit_number / 2 ) - 1;
        if ( $limit_number_start < 1 ) $limit_number_start = 1;
        //In case if the current page is at the beginning.
        $dif = ( $limit_number_end - $limit_number_start );
        if ( $dif < $limit_number ) $limit_number_end = $limit_number_end + ( $limit_number - ( $dif + 1 ) );
        if ( $limit_number_end > $num_pages ) $limit_number_end = $num_pages;
        //In case if the current page is at the ending.
        $dif = ( $limit_number_end - $limit_number_start );
        if ( $limit_number_start < 1 ) $limit_number_start = 1;
    }
    else {
        $limit_number_start = 1;
        $limit_number_end = $num_pages;
    }
    //Generating page links.
    for ( $i = $limit_number_start; $i <= $limit_number_end; $i++ ) {

        //$page_cur = "<a href='$link_prefix$i$link_suffix'>$i</a>";
        //echo $i;
        $page_cur = "<li><a href='$link_prefix$i$link_suffix'>$i</a></li>";
        //<li class='disabled'><a href='#'>Prev</a></li>

        //if ( $page == $i ) $page_cur = "<b>$i</b>";
        //<li class='active'><a href='#'>1</a></li>
        if ( $page == $i ) $page_cur = "<li class='active'><a href='#'>$i</a></li>";
        else $page_cur = "<li><a href='$link_prefix$i$link_suffix'>$i</a></li>";
        //else $page_cur = "<li class='disabled'><a href='$link_prefix$i$link_suffix'>$i</a></li>";
        $panel .= $page_cur;
        //$panel .= " <span>$page_cur</span>";
    }

    $panel = trim( $panel );
    //Navigation arrows.
    //if ( $limit_number_start > 1 ) $panel = "<b><a href='$link_prefix".( 1 )."$link_suffix'>&lt;&lt;</a>  <a href='$link_prefix".( $page - 1 )."$link_suffix'>&lt;</a></b> $panel";
    if ( $limit_number_start > 1 ) $panel =
            "<li class='disabled'><a href='$link_prefix".( 1 )."$link_suffix'>&lt;&lt;</a></li>".
            "  <li class='disabled'><a href='$link_prefix".( $page - 1 )."$link_suffix'>&lt;</a></li> ".
            $panel;

    if ( $limit_number_end < $num_pages ) $panel =
            $panel. " <li class='disabled'><a href='$link_prefix".( $page + 1 )."$link_suffix'>&gt;</a></li> ".
            "<li class='disabled'><a href='$link_prefix$num_pages$link_suffix'>&gt;&gt;</a></li>";


    $output['panel'] = $panel; //Panel HTML source.
    $output['offset'] = $page_offset; //Current page number.
    $output['limit'] = $limit_page; //Number of resuts per page.
    //print_cx($array);
    //print_cx($output);
    $output['array'] = array_slice( $array, $page_offset, $limit_page, true ); //Array of current page results.
    //print_cx($output);
    return $output;
}

//取得 Microtime 時間
function getMicrotime() {
    list( $usec, $sec ) = explode( ' ', microtime() );
    return (double)$usec + (double)$sec;
}
?>
