<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');

require('chinese-unicode.php'); 
$pdf=new PDF_Unicode(); 
$pdf->FPDF('P', 'mm', 'A4');
/* $pdf->Open(); 
$pdf->AddPage(); 

$pdf->AddUniCNShwFont('uni'); 
$pdf->SetFont('uni','',20); 

$pdf->Write(10, "1234學生名字\n伃綉堃亘");
$pdf->Ln();
$pdf->MultiCell (120, 10, "をホームページに！");
$pdf->Cell (240, 10, "本文用UTF8做為中文字編碼, 在這裡還是呼叫同樣的FPDF函數");
$pdf->Ln();

$pdf->Output();
 */

$pdf->Open(); 
$pdf->AddPage(); 
$pdf->SetAutoPageBreak(false,0); 
$pdf->SetMargins(0,0,0);
$pdf->AddUniCNShwFont('uni');
$pdf->SetFont('uni','',8); 
//左一
$pdf->SetXY(23,21); 
$pdf->Cell(50,6.5,"XXX",0,1,"L");

$pdf->SetXY(23,27); 
$pdf->Cell(50,8.5,"台中市XX路66巷66號6F",0,1,"L");
$pdf->SetXY(8,39); 
$pdf->Cell(32,5,"機車/輕型機車/光陽",0,0,"C"); 
$pdf->SetXY(40,39); 
$pdf->Cell(11,5,"96",0,0,"C"); 
$pdf->SetXY(51,39); 
$pdf->Cell(35,5,"ABC-123/引擎/車身號碼",0,1,"C"); 



$pdf->SetXY(32,45); 
$pdf->Cell(8,4,"96",0,0,"C"); 
$pdf->SetXY(40,45); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(50,45); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(77,45); 
$pdf->Cell(7,4,"12",0,0,"C"); 

$pdf->SetXY(32,49); 
$pdf->Cell(8,4,"97",0,0,"C"); 
$pdf->SetXY(40,49); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(50,49); 
$pdf->Cell(10,4,"12",0,0,"C"); 


$pdf->SetXY(31,62); 
$pdf->Cell(8,7,"零",0,0,"C"); 
$pdf->SetXY(39,62); 
$pdf->Cell(10,7,"壹",0,0,"C"); 
$pdf->SetXY(49,62); 
$pdf->Cell(10,7,"貳",0,0,"C"); 
$pdf->SetXY(59,62); 
$pdf->Cell(10,7,"叁",0,0,"C"); 
$pdf->SetXY(71,62); 
$pdf->Cell(10,7,"伍",0,0,"C"); 

 //左二
$pdf->SetXY(23,133); 
$pdf->Cell(50,6.5,"XXX",0,1,"L");

$pdf->SetXY(23,140); 
$pdf->Cell(50,8.5,"台中市XX路66巷66號6F",0,1,"L");
$pdf->SetXY(8,153); 
$pdf->Cell(15,5,"機車輕型機車",0,0,"C"); 
$pdf->SetXY(23,153); 
$pdf->Cell(21,5,"光陽",0,0,"C"); 

$pdf->SetXY(47,153); 
$pdf->Cell(11,5,"96",0,0,"C"); 
$pdf->SetXY(55,153); 
$pdf->Cell(42,5,"ABC-123/引擎/車身號碼",0,1,"C"); 



$pdf->SetXY(32,158); 
$pdf->Cell(10,4,"96",0,0,"C"); 
$pdf->SetXY(45,158); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(57,158); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(98,158); 
$pdf->Cell(7,4,"12",0,0,"C"); 

$pdf->SetXY(32,162); 
$pdf->Cell(10,4,"97",0,0,"C"); 
$pdf->SetXY(45,162); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(57,162); 
$pdf->Cell(10,4,"12",0,0,"C"); 


$pdf->SetXY(31,175); 
$pdf->Cell(8,7,"零",0,0,"C"); 
$pdf->SetXY(39,175); 
$pdf->Cell(10,7,"壹",0,0,"C"); 
$pdf->SetXY(49,175); 
$pdf->Cell(10,7,"貳",0,0,"C"); 
$pdf->SetXY(60,175); 
$pdf->Cell(10,7,"叁",0,0,"C"); 
$pdf->SetXY(69,175); 
$pdf->Cell(10,7,"伍",0,0,"C"); 

//右一
//身分證
$pdf->SetXY(114,22); 
$pdf->Cell(4,6,"A",0,0,"C"); 
$pdf->SetXY(119,22); 
$pdf->Cell(4,6,"1",0,0,"C"); 
$pdf->SetXY(124,22); 
$pdf->Cell(4,6,"2",0,0,"C"); 
$pdf->SetXY(129,22); 
$pdf->Cell(4,6,"3",0,0,"C"); 
$pdf->SetXY(134,22); 
$pdf->Cell(4,6,"4",0,0,"C"); 
$pdf->SetXY(139,22); 
$pdf->Cell(4,6,"5",0,0,"C"); 
$pdf->SetXY(144,22); 
$pdf->Cell(4,6,"6",0,0,"C"); 
$pdf->SetXY(149,22); 
$pdf->Cell(4,6,"7",0,0,"C"); 
$pdf->SetXY(154,22); 
$pdf->Cell(4,6,"8",0,0,"C"); 
$pdf->SetXY(159,22); 
$pdf->Cell(4,6,"9",0,0,"C");
//出生日期
$pdf->SetXY(113,34); 
$pdf->Cell(10,6,"66",0,0,"C");
$pdf->SetXY(122,34); 
$pdf->Cell(10,6,"12",0,0,"C");
$pdf->SetXY(133,34); 
$pdf->Cell(10,6,"12",0,0,"C");
//男女
$pdf->SetXY(143,34); 
$pdf->Cell(10,6,"",0,0,"C");
$pdf->SetXY(153,34); 
$pdf->Cell(10,6,"v",0,0,"C");

//婚姻
$pdf->SetXY(163,34); 
$pdf->Cell(13,6,"",0,0,"C");
$pdf->SetXY(175,34); 
$pdf->Cell(13,6,"v",0,0,"C");
//係數
$pdf->SetXY(189,34); 
$pdf->Cell(15,6,"4",0,0,"C");
//要保人簽章
$pdf->SetXY(130,42); 
$pdf->Cell(50,4,"XXX",0,0,"L");
//住址
$pdf->SetXY(130,46); 
$pdf->Cell(50,4,"台中市XX路66巷66號6F",0,1,"L");
//電話
$pdf->SetXY(129,51); 
$pdf->Cell(8,5,"02",0,1,"C");
$pdf->SetXY(140,51); 
$pdf->Cell(20,5,"22123456",0,1,"L");


//傳真
$pdf->SetXY(176,51); 
$pdf->Cell(5,5,"02",0,1,"L");
$pdf->SetXY(185,51); 
$pdf->Cell(20,5,"22123456",0,1,"L");

//收件單位及日期
$pdf->SetXY(170,58); 
$pdf->Cell(33,13,"台灣省農會 ".(date("Y")-1911)." /".date("n")." /".date("j"),0,1,"C");
//專戶代號
$pdf->SetXY(127,75); 
$pdf->Cell(17,10,"AAA",0,1,"C");
//業務來源
$pdf->SetXY(145,75); 
$pdf->Cell(20,10,"BBB",0,1,"C");

//右末
$pdf->SetXY(130,216); 
$pdf->Cell(50,6.5,"XXX",0,1,"L");

$pdf->SetXY(130,222); 
$pdf->Cell(50,8.5,"台中市XX路66巷66號6F",0,1,"L");
$pdf->SetXY(114,234); 
$pdf->Cell(32,5,"機車/輕型機車/光陽",0,0,"C"); 
$pdf->SetXY(148,234); 
$pdf->Cell(11,5,"96",0,0,"C"); 
$pdf->SetXY(157,234); 
$pdf->Cell(35,5,"ABC-123/引擎/車身號碼",0,1,"C"); 



$pdf->SetXY(138,238); 
$pdf->Cell(8,4,"96",0,0,"C"); 
$pdf->SetXY(148,238); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(158,238); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(185,238); 
$pdf->Cell(7,4,"12",0,0,"C"); 

$pdf->SetXY(138,242); 
$pdf->Cell(8,4,"97",0,0,"C"); 
$pdf->SetXY(148,242); 
$pdf->Cell(10,4,"12",0,0,"C"); 
$pdf->SetXY(158,242); 
$pdf->Cell(10,4,"12",0,0,"C"); 


$pdf->SetXY(138,255); 
$pdf->Cell(8,7,"零",0,0,"C"); 
$pdf->SetXY(147,255); 
$pdf->Cell(10,7,"壹",0,0,"C"); 
$pdf->SetXY(159,255); 
$pdf->Cell(10,7,"貳",0,0,"C"); 
$pdf->SetXY(169,255); 
$pdf->Cell(10,7,"叁",0,0,"C"); 
$pdf->SetXY(179,255); 
$pdf->Cell(10,7,"伍",0,0,"C"); 

//左末
$pdf->SetXY(23,224); 
$pdf->Cell(50,6,"XXX",0,1,"L");

$pdf->SetXY(32,231); 
$pdf->Cell(8,4,"96",0,1,"L");
$pdf->SetXY(42,231); 
$pdf->Cell(8,4,"12",0,1,"L");
$pdf->SetXY(52,231); 
$pdf->Cell(8,4,"12",0,1,"L");
$pdf->SetXY(78,231); 
$pdf->Cell(7,4,"12",0,1,"L");
$pdf->SetXY(32,233); 
$pdf->Cell(50,8.5,"96",0,1,"L");
$pdf->SetXY(42,233); 
$pdf->Cell(50,8.5,"96",0,1,"L");
$pdf->SetXY(52,233); 
$pdf->Cell(50,8.5,"96",0,1,"L");

$pdf->SetXY(9,242); 
$pdf->Cell(21,6,"機車/輕型機車",0,0,"C"); 
$pdf->SetXY(36,242); 
$pdf->Cell(6,6,"96",0,0,"C"); 
$pdf->SetXY(45,242); 
$pdf->Cell(35,6,"ABC-123",0,0,"C"); 
$pdf->SetXY(11,251); 
$pdf->Cell(21,6,"光陽",0,0,"C"); 
$pdf->SetXY(32,251); 
$pdf->Cell(11,6,"50cc",0,0,"C"); 
$pdf->SetXY(45,251); 
$pdf->Cell(35,6,"車身號碼/引擎號碼",0,0,"C"); 
$pdf->SetXY(79,251); 
$pdf->Cell(7,6,"4",0,0,"C"); 
 //建立日期
$pdf->SetXY(67,76); 
$pdf->Cell(4,7,(date("Y")-1911),0,0,"C"); 
$pdf->SetXY(74,76); 
$pdf->Cell(4,7,date("n"),0,0,"C"); 
$pdf->SetXY(81,76); 
$pdf->Cell(4,7,date("j"),0,0,"C"); 


$pdf->SetXY(56,191); 
$pdf->Cell(5,7,(date("Y")-1911),0,0,"C"); 
$pdf->SetXY(64,191); 
$pdf->Cell(5,7,date("n"),0,0,"C"); 
$pdf->SetXY(74,191); 
$pdf->Cell(5,7,date("j"),0,0,"C"); 

$pdf->SetXY(175,271); 
$pdf->Cell(5,7,(date("Y")-1911),0,0,"C"); 
$pdf->SetXY(181,271); 
$pdf->Cell(5,7,date("n"),0,0,"C"); 
$pdf->SetXY(189,271); 
$pdf->Cell(5,7,date("j"),0,0,"C"); 


$pdf->SetXY(67,261); 
$pdf->Cell(5,7,(date("Y")-1911),0,0,"C"); 
$pdf->SetXY(74,261); 
$pdf->Cell(5,7,date("n"),0,0,"C"); 
$pdf->SetXY(81,261); 
$pdf->Cell(5,7,date("j"),0,0,"C"); 

$pdf->Output();
 ?> 