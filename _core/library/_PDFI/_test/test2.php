<?php
$xPath_p= '../';
$vBase['menu'] = "xxx";
include_once $xPath_p."_base/init.php" ;
include_once $xPath_p."signup/lib_form.php" ;
check_nm_pw_admin();

//AJAX PAGE 模擬CONTROL

$_sno = $_GET['n_sid'] ;
$_xno = $_GET['x_no'] ;
$_func = $_GET['func'];
$mMod = '1';


// $Core->loadMod( 'mod_signUpData', true , "mSign");

class admin_func{
	public $mCore;
	public $mSign;
	public $mGet;
	public function __construct( $_core ) {
		// parent::__construct( );
		$this->mCore = &$_core;
		$this->mCore->loadMod( "mod_signUpData" , true );
		$this->mSign = &$this->mCore->mod_signUpData;
		$this->mGet = $_GET;
	}

	public function CreatePdf(){
		//func_studentNo.php?func=CreatePdf&x_no=2766
		$mLibForm = new lib_form();
		$mMod = '1';
		// $_sno = $_GET['n_sid'] ;
		$_xno = $this->mGet['x_no'] ;

		$__data = $this->mSign->getUser( "*" , " X_no='".$_xno."' " );
		$_data = $__data[0];
		$mLibForm->setSubj( $_data['exam_subj'] );

		// echo $mMod;
		$_std_no = $_data['Student_No'];
		$_name = $_data['name'];
		$_Id = strtoupper( $_data['Id'] ) ;
		// $_Esname = $_data['now_Esname'];
		// echo $_name;exit;

		$_type1 = $mLibForm->getAData( 'base_type' );
		$_type2 = $mLibForm->getAData( 'base_typeb' );


//----img -- model is ok to cx_pdfi the createAlphaTextImage function
// $text = "許公蓋烱測試堃墭爨伃";
// $_name_pic = "../_public/pdf1/name/name" . $_std_no . "_" . $_xno. '.png';
// $text = $_name;
// $img=ImageCreateTrueColor(400,80);
// ImageAlphaBlending($img, true);
// ImageAntiAlias($img, true);
// ImageSaveAlpha($img, true);
// $bgcolor = ImageColorAllocateAlpha($img,255,255,255,127); 
// ImageFill($img,0,0,$bgcolor);
// $black = imagecolorallocate($img, 0, 0, 0);
// // $utf_text = iconv('big5', 'utf-8', $text);
// $utf_text = $text;
// imagettftext($img, 30, 0, 10, 40, $black, "../_fonts/TW-Sung-98_1.ttf", $utf_text);
// imagepng($img,  $_name_pic );
// imagedestroy($img);



//----PDF
		$this->mCore->loadSysLib( "cx_pdfi" );
		$_pdfi = new cx_pdfi( $this->mCore );

//create text image
// $text = "許公蓋烱測試堃墭爨伃";
$_sFont_path =  "../_fonts/TW-Sung-98_1.ttf";

$_sName_pic = "../_public/pdf1/textImage/name_" . $_std_no . "_" . $_xno. '.png';
$_pdfi->createAlphaTextImage($_name, $_sName_pic ,450,100 ,$_sFont_path);

$_sStd_no = "../_public/pdf1/textImage/stdNo_" . $_std_no . "_" . $_xno. '.png';
$_pdfi->createAlphaTextImage($_std_no, $_sStd_no ,450,100 ,$_sFont_path);

$_sType1 = "../_public/pdf1/textImage/type1_" . $_std_no . "_" . $_xno. '.png';
$_pdfi->createAlphaTextImage($_type1, $_sType1 ,450,100 ,$_sFont_path);


if( $_type2 != '' ){
	$_sType2 = "../_public/pdf1/textImage/type2_" . $_std_no . "_" . $_xno. '.png';
	$_pdfi->createAlphaTextImage($_type2, $_sType2 ,450,100 ,$_sFont_path);

}

$_sID = "../_public/pdf1/textImage/id_" . $_std_no . "_" . $_xno. '.png';
$_pdfi->createAlphaTextImage(strtoupper( $_Id ), $_sID ,450,100 ,$_sFont_path);



		$pdf = $_pdfi->createPDF();
		// 載入現在 PDF 檔案
		$page_count = $pdf->setSourceFile( '../_view/PDF'.$mMod.'.pdf' );
		// 匯入現在 PDF 檔案的第一頁
		$tpl = $pdf->importPage( 1 );
		// 在新的 PDF 上新增一頁
		$pdf->addPage();
		// 在新增的頁面上使用匯入的第一頁
		$pdf->useTemplate( $tpl );

		$pdf->AddUniCNShwFont( 'uni' );
		$pdf->SetFont( 'uni', '', 10 );

		// $pdf->SetTextColor(255,0,0);
		// $pdf->SetXY(25, 25);
		// $pdf->Write(0, "許公蓋烱測試堃墭爨伃");
		$pdf->SetTextColor( 0, 0, 0 );
		if( $mMod == 1 ){


			$pdf->SetXY( 64, 42 );
			// $pdf->Write( 0, $_name  );
			$pdf->image($_sName_pic , 64, 39, 38);

			$pdf->SetXY( 64, 34 );
			// $pdf->Write( 0, $_std_no );
			$pdf->image($_sStd_no , 64, 31, 38);
			


			if( $_type2 == '' ){
				$pdf->SetXY( 150, 34 );
				// $pdf->Write( 0, $_type1 );
				$pdf->image($_sType1 , 150, 32, 38);

			}else{
				$pdf->SetXY( 150, 32 );
				// $pdf->Write( 0, $_type1 );
				$pdf->image($_sType1 , 150, 30, 38);

				$pdf->SetXY( 150, 36 );
				// $pdf->Write( 0, $_type2 );
				$pdf->image($_sType2 , 150, 34, 38);
			}


			$pdf->SetXY( 150, 42 );
			// $pdf->Write( 0, strtoupper( $_Id )  );
			$pdf->image($_sID , 150, 39, 37);
			//_sID

			// $page->drawText( strtoupper($_Id)  , 400, 715+6,'UTF-8');
			$_title ="初試";
		}else{

			$pdf->SetXY( 64, 42 );
			$pdf->Write( 0, $_name );
			// $pdf->image("final.png", 75, 85, 50);

			$pdf->SetXY( 64, 34 );
			$pdf->Write( 0, $_std_no );


			if( $_type2 == '' ){
				$pdf->SetXY( 150, 34 );
				$pdf->Write( 0, $_type1 );
			}else{
				$pdf->SetXY( 150, 32 );
				$pdf->Write( 0, $_type1 );

				$pdf->SetXY( 150, 36 );
				$pdf->Write( 0, $_type2 );
			}


			$pdf->SetXY( 150, 42 );
			$pdf->Write( 0, strtoupper( $_Id )  );

			// $page->drawText( $_name             , 158, 718+3,'UTF-8');
			// $page->drawText( $_std_no        , 158, 738+3,'UTF-8');

			// $page->drawText( $_type1           , 400, 747+3,'UTF-8');
			// $page->drawText( $_type2          , 400, 735+3,'UTF-8');

			// $page->drawText( strtoupper($_Id)   , 400, 718+3,'UTF-8');
			$_title ="複試";
		}


		$tpl2 = $pdf->importPage( 2 );
		$pdf->addPage();
		$pdf->useTemplate( $tpl2 );

		// //page 2
		// $tpl2 = $pdf->importPage(2);
		// $pdf->addPage();
		// $pdf->useTemplate($tpl2);


		$fileday = date( "Y-m-d" );

		// // 輸出成本地端 PDF 檔案
		$pdf->output(  "../_public/pdf1/" . $_std_no . "_" . $_xno. ".pdf", "F" );

		echo "ok";
	}

	public function reset(  ){
		//func_studentNo.php?func=reset&class=01
		//resetStudentNoByClass
		// echo "reset";exit;
		$_class = $this->mGet['class'] ;
		// $_xno = $this->mGet['x_no'] ;
		//$__data = $this->mSign->getUser( "*" , " X_no='".$_xno."' " );
		return $this->mSign->resetStudentNoByClass($_class);
	}

	public function CreateStdNo( ){
		//func_studentNo.php?func=CreateStdNo&class=01
		$_class = $this->mGet['class'] ;
		// $_xno = $this->mGet['x_no'] ;
		$_tag = $this->mSign->updateUserByClass($_class);
		$_m = ($_tag)?" OK!":" bad!";
		$msg = "組別：" . $_class . $_m;
		echo $msg;
		return $_tag;
	}

	public function CreateAllStdNo(){
		// $this->mSign->updateUserByClass($_class);
		$_EXsub = $this->mSign->getExam_subj();
		foreach($_EXsub as $_val ){
			$_d = $this->mSign->updateUserByClass( $_val['ESID'] );
		}
		echo  'ok' .  "請重新整理.";
		return true;
	}


}

$mFunc = new admin_func( $Core );
// $_func = 'CreateStdNo';
if( method_exists( $mFunc, $_func ) ){
	$mFunc->{$_func}();
}else{
	echo "bad";
}

