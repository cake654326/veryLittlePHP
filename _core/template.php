<?php
$TPL = array();
class template
{
	var $_data = "";
	public function __construct() {
		$this->clear();
	} 
	
	public function loadView($_path,$_arr,$_b = false){
		$this->view($_path,$_arr,$_b);
		$this->clear();
	}

	public function view($_path,$_arr,$_b = false)
	{
		$TPL = $_arr;
		ob_start();
		include $_path;
		
		$this->_data = ob_get_clean();
		if($_b)
		{
			return $this->_data;
		}
		//echo $this->_data;
		return ;
	}
	
	public function clear(){
		$TPL = array();
		$this->_data ="";
	}
	
}
?>