<?php if (!defined('MVC_PATH'))exit('404');
class aboutController extends baseController{

    public function __construct($_core){
        parent::__construct($_core);
        $this->showDebug(false);
    }

    public function init($aUrl)
    {
        // parent::init( $aUrl );
    }

    public function IndexAction( $aUrl ){
        $this->mCore->redirect( "about/show" ,true);
        exit();
    }

    // --- 自定方法 ---
    public function ShowAction( $aUrl ){
        echo "<br/> this->mCore->Url():" . $this->mCore->Url() . "<br/>";
        echo "<br/> this->mCore->Url(\"about/show\",false):" . $this->mCore->Url("about/show",false) . "<br/>";
        echo "<br/> this->mCore->Url(\"about/show\",true):" . $this->mCore->Url("about/show",true). "<br/>";


        echo "<br/> this->mCore->getUrl():" . $this->mCore->getUrl() . "<br/>";
        echo "<br/> this->mCore->getBaseUrl() :" . $this->mCore->getBaseUrl() . "<br/>";
        echo "<br/> this->mCore->getBaseUrl(\"about/show\",true) :" . $this->mCore->getBaseUrl("about/show",true) . "<br/>";
        echo "<br/> this->mCore->getBaseUrl(\"about/show\",false):" . $this->mCore->getBaseUrl("about/show",false) . "<br/>";

        echo "<br/> this->mCore->getBaseUrl(\"about/show\",true , true) :" . $this->mCore->getBaseUrl("about/show",true , true) . "<br/>";


 
/*

this->mCore->Url():./../../index.php/

this->mCore->Url("about/show",false):./../../about/show

this->mCore->Url("about/show",true):./../../index.php/about/show

this->mCore->getUrl():http://172.16.44.80/120903/wish103/

this->mCore->getBaseUrl() :172.16.44.80/120903/wish103/

this->mCore->getBaseUrl("about/show",true) :172.16.44.80/120903/wish103/index.php/about/show

this->mCore->getBaseUrl("about/show",false):172.16.44.80/120903/wish103/about/show

this->mCore->getBaseUrl("about/show",true , true) :http://172.16.44.80/120903/wish103/index.php/about/show
*/


        echo $this->mCore->Url("abc",true);
        echo gethostbyname($_SERVER['SERVER_NAME']);
        echo " ,host: " .  $_SERVER['HTTP_HOST'];
        echo " , " . $_SERVER['SERVER_NAME'];

        $aView['vTitle'] = "---關於---";
        $aView['container'] = "內容";
        $aView['vFooter'] = '';
        echo $this->mCore->loadView('./_view/base_demo.php',$aView , true);
    }


}

