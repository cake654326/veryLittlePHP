<?php
class mod_class extends cx_db{
	var $mTableName = 'Tb_Class';
	public function __construct( $_conn ) {
		parent::__construct( $_conn );
		//$this->mConn
	}

	/**
	 * tb_class object
	 *
	 * **/
	public function getClassMaxValue() {
		$this->mConn->setCxTitle( "mod_user getClassMaxValue " );
		$_sql = "SELECT Class_No, max(Class_Name) as Class_Name  from Tb_Class ".
			" group by Class_No ORDER BY Class_No DESC ";
		$_rs = $this->mConn->Execute( $_sql, array()  );
		if ( !$_rs )return false;
		return $_rs->GetArray();
	}

	public function getUserClass( $_class_no ) {
		$this->mConn->setCxTitle( "mod_user getUserClass" );
		$_sql = "select * from Tb_Class WHERE Class_No=?";
		$_rs = $this->mConn->Execute( $_sql, array( $_class_no ) );
		if ( !$_rs )return false;
		return $_rs->GetArray();
	}

	/**
	 *  取得所有 tb_class 資料
	 * */
	public function getAllClass(){
		$this->mConn->setCxTitle( "mod_user getAllClass 取得所有 tb_class 資料" );
		$_sql = "select * from Tb_Class where 1=1";
		$_rs = $this->mConn->Execute( $_sql );
		if ( !$_rs )return false;
		return $_rs->GetArray();
	}

	public function db_save($_data){
		$this->mConn->setCxTitle( "mod_class db_save " );
		$_rs = $this->setTable("Tb_Class")
					->setPk("Class_No")
					->updataWhere("Tb_Class.Class_No='" . $_data['Class_No'] ."'" )
					->save($_data,$_data['Class_No']);
		if ( !$_rs )return false;
		return $_rs;
	}


}
?>
