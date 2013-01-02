<?php
/**
# CX.php 
# CAKE X
# 使用router 自動導入管理器,用於MVC入口點 初始類別.
# --------------------------------------------------------
# 2012/12/07 AM  :   v1.2.0 : [cx] 傳統 MVC 入口點 index.php 
#							  [cx] 相容之前非使用 入口點 專案(v1.1) 以及 傳統寫法專案(v1.1)，不相容 舊版 v1.0框架專案
# 2012/12/22 PM17:59 v1.2.1 : [cx] 增加取得 baseUrl 網址
# --------------------------------------------------------
**/
class CX {
	public static function getCore() {
		global $Core;
		return $Core;
	}

	public static function getDB() {
		global $CONN;
		return $CONN;
	}

	public static function run(){
		global $MVC_PATH; 
		$mCore = &self::getCore();
		$mCore->loadSysLib("cx_router");

		$_host = ( !$mCore->config("base_host" ) )?null:$mCore->config("base_host" );

		$_router = new cx_router();
		$_path = $_router->init( $MVC_PATH . "_controllers" ,$_host , $mCore->config("INDEX") )->getPath();
		

		// $_url = $_router->aUrl;//BUG
		$_url = $_router->aVal_URI;
		$mCore->mBackUrl = $_router->aVal_URI;
		// print_cx($mCore->mBackUrl);

		$mCore->mBaseUrl = ( !$mCore->config("base_base_url" ) )?$_router->getBaseUrl():$mCore->config("base_base_url" );
		// echo $mCore->mBaseUrl;
		if( $_url[0] == '' || !isset($_url[0])){//!isset($_url[0]) || 
			$_base_ctrl = $mCore->config("baseController");
			header("Location: "  ."./index.php" .$_base_ctrl );
			exit;
		}
		$mCTRL = self::_getController( $_path , $_router->getControllerName()  );

		$sAction = self::_getAction($mCTRL, $_router->sAction );
		$mCTRL->init();//load init()
		return $mCTRL->{$sAction}($_router->aData);

	}


	protected static function _getController($_path,$_controller )
    {

    	$mCore = &self::getCore();
         $userNamespace = '';//My_
        // if (array_key_exists('userNamespace', $this->_config['bootstrap'])) {
        //     $userNamespace = rtrim(ucfirst($this->_config['bootstrap']['userNamespace']), '_') . '_';
        // }
		$controllerName = $userNamespace . $_controller . 'Controller';

		try {
			if(file_exists($_path)){
				require $_path;
			}else{
				//run404 page
				self::page404("path is error");
				exit(0);
			}
			
			return new $controllerName( $mCore );
		} catch (Exception $e) {

			self::page404("controllerName 不存在。");
			exit(0);
			throw new Exception("Controller \"$controllerName\" 不存在。");
		}
    }

    protected static function page404( $msg ){
    	$mCore = &self::getCore();
    	//echo "<br> now getcwd: " . getcwd() . "<br>";  
    	echo $mCore->loadView('./_view/404.php' , array('msg' => $msg) ,true);
    	return ;
    }

    protected function _getAction($_crtl,$_action)
    {
    	($_action == '') and $_action = 'index';
        $action = ucfirst($_action . 'Action');
        if (method_exists($_crtl, $action)) {
            return $action;
        } else {
            $controllerName = get_class($_crtl);
            self::page404("Action \"$controllerName::$action\" 不存在。");
			exit(0);
            throw new Exception("Action \"$controllerName::$action\" 不存在。");
        }
    }




}


?>