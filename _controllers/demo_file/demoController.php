<?php
//abstract class CX_Controller extends CI_Controller
class demoController extends baseContoller
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
   		echo "<br> demo add";
   		print_cx($aUrl);
   		print_cx($this->mCore->Post());
   		print_cx($this->mCore->Get());
   		echo "<br> now getcwd: " . getcwd() . "<br>";
   		$this->mCore->loadLib("lib_demo");
   		$mLibDemo = new lib_demo();

   		$mLibDemo->test();
   }	

}
?>