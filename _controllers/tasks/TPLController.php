<?php if ( ! defined('MVC_PATH')  ) exit('404');
//tasks mainController.php
class TPLController extends baseController
{
   public function __construct($_core)
   {
      parent::__construct($_core);

   }

   /**
    * baseController
    ***/
   public function init( $aUrl ){

   }

   public function IndexAction( $aUrl ){
   		$aView = array();
      $aView['vLeft'] = $this->mCore->loadView( './_view/tasks/vLeft2.php', array() , true );
      echo $this->mCore->loadView( './_view/tasks/base2.php', $aView , true );
   }

   public function AddAction( $aUrl ){
   		echo "AddAction page";
   }


}