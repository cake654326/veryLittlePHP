<?php
class mod_class extends cx_db{
	var $mTableName = 'user';
	public function __construct( $_conn = null  ) { // $_conn = null 在1.3.7版 已經無功用
		$sData_base = "MY_DATABASE_Data_DB";//資料庫別名 若不使用 則設 null 
		parent::__construct( $sData_base );
		//$this->mConn
		$this->setDrives('mssql');
	}

	public function finds( $_where = " 1=1 " , $_arr = array() ){
		$this->setTitle( "mod_user::finds ,where => ".$_where );
		return $this->sqlExec("SELECT * FROM user WHERE ".$_where ,$_arr)->getArray();
	}

	public function find( $_where = " 1=1 " , $_arr = array() ){
		$this->setTitle( "mod_user::find ,where => ".$_where );
		return $this->sqlExec("SELECT * FROM user WHERE ".$_where ,$_arr);
	}


}
?>
