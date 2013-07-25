<?php if ( ! defined('MVC_PATH')  ) exit('404');
//tasks mainController.php
class mainController extends baseController
{
   public function __construct($_core)
   {
      parent::__construct($_core);
      $this->showDebug(true);
   }

   /**
    * baseController
    ***/
   public function init( $aUrl ){

   }

   public function IndexAction( $aUrl ){

    $mDb = new cx_db();
    $mDb->setTitle( "資料庫測試連接 TEMP " );
    $_sql = "select * from temp2";
    $mDb->sqlExec($_sql,array() );

   		$aView = array();
      $aView['vLeft'] = $this->mCore->loadView( './_view/tasks/vLeft2.php', array() , true );
      echo $this->mCore->loadView( './_view/tasks/base2.php', $aView , true );
   }

   public function AddAction( $aUrl ){
   		echo "AddAction page";
   }


}