<?php if ( ! defined('MVC_PATH')  ) exit('404');
//indexController.php
class indexController extends baseController
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
   		echo "Index page";
      $mDb = new cx_db();
      $mDb->setTitle( "資料庫測試連接 TEMP " );
      $_sql = "select * from temp";
      $mDb->sqlExec($_sql,array() );

   }

   public function AddAction( $aUrl ){
   		echo "AddAction page";
      $mDb = new cx_db();
      $mDb->setTitle( "資料庫測試連接 TEMP " );
      $_sql = "select * from temp";
      $mDb->sqlExec($_sql,array() );
   }


}