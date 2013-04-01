<?php
define( 'FPDF_FONTPATH', './PDFI/font/' );
class cx_pdfi {
	public function __construct( &$_core ) {

		$_core->loadSysLib( "_PDFI/fpdf" );
		$_core->loadSysLib( "_PDFI/fpdf_alpha" );
		$_core->loadSysLib( "_PDFI/fpdf_tpl" );
		$_core->loadSysLib( "_PDFI/chinese-unicode" );
		$_core->loadSysLib( "_PDFI/fpdi" );


		//parent::__construct();
		// require_once ($_path . '_PDFI/fpdf.php');
		// require_once ($_path . '_PDFI/fpdf_tpl.php');
		// require_once ($_path . '_PDFI/chinese-unicode.php');
		// require_once ($_path . '_PDFI/fpdi.php');
		return $this;
	}

	/**
	 * 建立 FPDI OBJECT
	 * */
	public function createPDF(){
		return new FPDI();
	}

	/**
	 * createAlphaTextImage( $_text , $_pic , $_w, $_h , $_font_path )
	 * 輸出透明底圖的文字圖片
	 *
	 * @ $_text 文字
	 * @ $_pic 圖片儲存路徑
	 * @ $_w 圖片寬度
	 * @ $_h 圖片高度
	 * @ $_font_path 字形路徑
	 * */
	public function createAlphaTextImage( $_text , $_pic , $_w, $_h , $_font_path ){

		$img=ImageCreateTrueColor( $_w, $_h );
		ImageAlphaBlending( $img, true );
		ImageAntiAlias( $img, true );
		ImageSaveAlpha( $img, true );
		$bgcolor = ImageColorAllocateAlpha( $img, 255, 255, 255, 127 );
		ImageFill( $img, 0, 0, $bgcolor );
		$black = imagecolorallocate( $img, 0, 0, 0 );
		// $utf_text = iconv('big5', 'utf-8', $text);
		$utf_text = $_text;
		imagettftext( $img, 30, 0, 10, 40, $black, $_font_path, $utf_text );
		imagepng( $img,  $_pic );
		imagedestroy( $img );
		return true;

	}


}

?>
