<?php
class demo2Controller extends baseController
{
   public function __construct($_core)
   {
      parent::__construct($_core);

   }

   /**
    * baseController
    ***/
   public function init(){

   }

   public function IndexAction(){
   		echo "Index page";
   }

   public function AddAction(){
   		echo "AddAction page";
   }


}