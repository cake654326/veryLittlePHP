<?php if ( ! defined('MVC_PATH')  ) exit('404');
//tasks indexController.php
class indexController extends baseController
{
   public function __construct($_core)
   {
      parent::__construct($_core);

   }

   /**
    * baseController
    ***/
   public function init( $aUrl ){
      $this->mCore->redirect( "tasks/main" ,true);
      exit();
      return true;
   }

   public function IndexAction( $aUrl ){
   		//echo "Index page";
   }



}