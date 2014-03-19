<?php
if ( ! function_exists( 'force_download' ) )
{

	function force_download( $filename = '', $data = '', $is_file = true )
	{
		// if (!is_file($data)) { die("<b>404 File not found!</b>"); }
	
		if ( $filename == '' or $data == '' )
		{
			return FALSE;
		}

		//讀取檔案轉為下載 限制
		// if( $is_file && !is_file( $data ) ){
		// 	return FALSE;
		// }

		// Try to determine if the filename includes a file extension.
		// We need it in order to set the MIME type
		if ( FALSE === strpos( $filename, '.' ) )
		{
			return FALSE;
		}

		// Grab the file extension
		$x = explode( '.', $filename );
		$extension = end( $x );

		// Load the mime types
		/*zcs=Reduce Dependence
        if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/mimes'.EXT))
        {
                include(APPPATH.'config/'.ENVIRONMENT.'/mimes'.EXT);
        }
        elseif (is_file(APPPATH.'config/mimes'.EXT))
        {
                include(APPPATH.'config/mimes'.EXT);
        }
        */
		$mimes = array(); //zcs=[NOTE] Here we just assign an empty array to reduce the dependence of the CI framework

		// Set a default mime if we can't find it
		if ( ! isset( $mimes[$extension] ) )
		{
			$mime = 'application/octet-stream';
		}
		else
		{
			$mime = ( is_array( $mimes[$extension] ) ) ? $mimes[$extension][0] : $mimes[$extension];
		}

		$size = 0;
		if( $is_file ){
			$size = filesize( $data );
		}else{
			$size = strlen( $data );
		}

		// Generate the server headers
		if ( strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ) , "MSIE" ) !== FALSE 
			or 
			strpos( strtoupper($_SERVER['HTTP_USER_AGENT']) , "TRIDENT" )!== FALSE 
			)
		{
			
			header( 'Content-Type: "'.$mime.'"' );
			header('Content-Disposition: attachment; filename="'.iconv('utf-8', 'big5', $filename).'"');
			// header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( "Content-Transfer-Encoding: binary" );
			header( 'Pragma: public' );
			header( "Content-Length: ".$size );
		}
		else
		{

			header( 'Content-Type: "'.$mime.'"' );
			header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
			header( "Content-Transfer-Encoding: binary" );
			header( 'Expires: 0' );
			header( 'Pragma: no-cache' );
			header( "Content-Length: ".$size );
		}

		if( $is_file ){
			$h_file = fopen( $data, 'r' );
			while( !feof( $h_file ) ){
				echo fread( $h_file, 1048576 );//1M
			}
			fclose( $h_file );
		}else{
			exit( $data );
		}
	}

}
//header('Content-Disposition: attachment; filename="'.iconv('utf-8', 'big5', $filename).'"');
