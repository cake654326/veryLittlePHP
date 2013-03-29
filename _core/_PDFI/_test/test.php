<?php
error_reporting (E_ALL);
define('FPDF_FONTPATH','../font/');
require_once ('../fpdf.php');
require_once ('../fpdf_tpl.php');
require_once ('../chinese-unicode.php');
require_once ('../fpdi.php');


//font image
$_fontPath = "./fonts/TW-Sung-95_1_2.ttf";
$text = "image: 許公蓋烱測試堃墭爨 !@#$%^&*()";

$image=ImageCreateTrueColor(360,200);
// ImageAlphaBlending($image, true);//启用Alpha合成
// ImageAntiAlias($image, true);//启用抗锯齿
// ImageSaveAlpha($image, true);//启用Alpha通道
//创建透明颜色（最后一个参数0不透明，127完全透明）
$bgcolor = ImageColorAllocateAlpha($image,255,255,255,127); 
ImageFill($image,0,0,$bgcolor);//使图片底色透明
$black = ImageColorAllocate($image,0,0,0);
imagettftext($image, 18, 0, 10, 40, $black, $_fontPath, $text);
imagepng($image, 'final.png');
imagedestroy($image);




// $img = imagecreatefrompng('test.png');
// $black = imagecolorallocate($img, 0, 0, 0);
// // $background_color = ImageColorAllocate($img, 0,255,0);
// // $utf_text = iconv('big5', 'utf-8', $text);
// imagettftext($img, 18, 0, 10, 40, $background_color, $_fontPath, $text);
// imagepng($img, 'final.png');
// imagedestroy($img);





// 建立 FPDI 物件
$pdf = new FPDI();

// 載入現在 PDF 檔案
$page_count = $pdf->setSourceFile("PDF1.pdf");

// 匯入現在 PDF 檔案的第一頁
$tpl = $pdf->importPage(1);

// 在新的 PDF 上新增一頁
$pdf->addPage();

// 在新增的頁面上使用匯入的第一頁
$pdf->useTemplate($tpl);

$pdf->AddUniCNShwFont('uni'); 
$pdf->SetFont('uni','',12); 

//$pdf->SetFont('Arial'); 
$pdf->SetTextColor(255,0,0); 
$pdf->SetXY(25, 25); 
$pdf->Write(0, "許公蓋烱測試堃墭爨伃"); 

$pdf->SetTextColor(255,0,0); 
$pdf->SetXY(50, 50); 
$pdf->Write(0, "陳品睿"); 

$pdf->image("final.png", 75, 85, 50);


//page 2
$tpl2 = $pdf->importPage(2);
$pdf->addPage();
$pdf->useTemplate($tpl2);



// 輸出成本地端 PDF 檔案
$pdf->output("final.pdf", "D");

// 結束 FPDI 剖析器
$pdf->closeParsers();