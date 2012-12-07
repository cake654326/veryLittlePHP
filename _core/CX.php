<?php
/**
 * 2012 12 07  - v1.2
 *			+ 傳統 MVC 入口點 index.php 
 *			+ 相容之前非使用 入口點 專案(v1.1) 以及 傳統寫法專案(v1.1)，不相容 舊版 v1.0框架專案
 * 
 ***/
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
		$_router = new cx_router();
		$_path = $_router->init( $MVC_PATH . "_controllers" )->getPath();
		//echo "path:" . $_path;
		//echo "<br> controller:" . $_router->getControllerName();
		//print_cx($_router->aData);
		$mCTRL = self::_getController( $_path , $_router->getControllerName()  );
		$sAction = self::_getAction($mCTRL, $_router->sAction );
		//echo "<br> action:" . $sAction;
		return $mCTRL->{$sAction}($_router->aData);
		//echo "<br> controller:" . $_router->getControllerName();

		//$_par = $_router->_parseUrl();
		//print_cx($_par);

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

    public static function page404( $msg ){
    	echo "404" . $msg;
    	return ;
    }

     /**
     * 
     *
     * @return 
     * @throws Excetion
     */
    protected function _getAction($_crtl,$_action)
    {
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