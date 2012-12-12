<?php
/**
# demoController - 控制器 測試頁面
# CAKE X
# 繼承 baseController ,未來將建立 自定 Contoller 功能
# --------------------------------------------------------
# 2012/12/22 AM08:50 v1.2 : [cx] demo/demoController.php
# --------------------------------------------------------
**/
class demoController extends baseController
{
   //public $person = array();

   public function __construct($_core)
   {
      parent::__construct($_core);
      // $this->load->library('session');
      // $this->load->helper('url'); 
      // $CI =& get_instance();
   }

   public function init(){

   }

   public function IndexAction( $aUrl ){
   	echo "demo index";
   }

   public function AddAction( $aUrl ){
   		echo "<br> demo aUrl:";
   		print_cx($aUrl);
         echo "<br> demo this->mCore->Post:";
   		print_cx($this->mCore->Post());
         echo "<br> demo this->mCore->Get:";
   		print_cx($this->mCore->Get());
   		echo "<br> now getcwd: " . getcwd() . "<br>";
   		$this->mCore->loadLib("lib_demo");
   		$mLibDemo = new lib_demo();

   		$mLibDemo->test();
   }	

}
?>