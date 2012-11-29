<?php
	/**
	* @author      Cake X
	* @link
	* @version     1.3.2
	* @history:
	*   2012 10 24  + save()          = 重寫之前版本的SAVE , 無法相容 以前專案
	*        		+ setTable
	*
	*	2012 11 06  + Execute()       = 簡化 execute 長度并且建立錯誤簡化 ADODB
	*	2012 11 08  + sqlExec()       = 使 EXECUTE 回傳 this
	*				+ getArray()      = 簡化 Adodb  GetArray
	*				+ getCout()       = 取得資料數量 簡化 adodb 函數名
	*
	*	2012 11 14  + autoSave        = 簡化SAVE 采固定PK的方式
	*				+ checkTableArray = 為save 增加自動化 欄位過濾
	*
	*	2012 11 15  ? setTableData    = 設定 INSERT OR UPDATE 預設載入的 欄位名稱，將會自動檢查。
	*
	* 	2012 11 29  + __construct = 增加 CORE DB 自動加載
	* **/
class cx_db {
	var $mConn;
	var $mTable;
	var $mWhere;
	var $mPk;
	var $mRs;

	public function __construct( $_conn = null ) {
		//parent::__construct();
		if($_conn == null){
			global $Core;
			if($Core){
				$_conn = &$Core->getDB();
			}else{
				echo "ERROR:[cx_db] don't have Core OR __construct( $_conn ) val adodb loading";
				exit(0);
			}
		}
		$this->mConn = &$_conn;
	}

	public function clear() {
		$this->mConn = '';
		$this->mTable = '';
		$this->mWhere = '';
		$this->mPk = '';
		$this->mRs = '';
	}

	public function setTitle( $_str ) {
		$this->mConn->setCxTitle( $_str );
		return true;
	}

	public function setTable( $_table ) {
		$this->mTable = $_table;
		return $this;
	}

	public function getError() {
		return $this->mConn->ErrorMsg();
	}

	/**
	 * 取得資料數量 簡化 adodb 函數名
	 * */
	public function getCout() {
		//echo "c:" . $this->mRs->RecordCount();
		if( !$this->mRs ) return false;

		return $this->mRs->RecordCount();
	}

	/**
	 * 簡化 Adodb  GetArray
	 * **/
	public function getArray() {
		//echo "run array()";
		$_cout = $this->getCout();

		if( !$_cout ) return false;
		if ( $_cout > 0 ) {
			return $this->mRs->GetArray();
		}
		if ( $_cout == 0 ) return 0;

		return false;
	}

	/**
	 * 使 EXECUTE 回傳 this
	 ***/
	public function sqlExec($_sql,$_arr){
		$this->Execute($_sql,$_arr);
		//print_cx($this->mRs );
		return $this;
	}

	/**
	 * 簡化 execute 長度
	 * 并且建立錯誤簡化 ADODB
	 * **/
	public function Execute( $_sql, $_arr ) {
		$this->mRs  = $this->mConn->Execute( $_sql , $_arr );
		if ( !$this->mRs  ) {
			$_error =   $this->getError();
			$this->setTitle( "CX_DB Execute Error:" . $_error );
			return false;
		}
		return $this->mRs;
	}

	public function selectPkCount( $_table, $_pk_val, $_pk_key = "id" ) {
		$this->mConn->setCxTitle( "cx_db selectPK " );
		$_sql = "Select ". $_pk_key . " from " . $_table . " Where " . $_pk_key . "=?";
		$_rs = $this->mConn->Execute( $_sql, array( $_pk_val ) );
		if ( !$_rs )return false;
		return $_rs->RecordCount();

	}
	/**
	 * string where
	 * **/
	public function updateWhere( $_w ) {
		$this->mWhere = $_w;
		return $this;
	}

	public function setPk( $_pk ) {
		$this->mPk = $_pk;
		return $this;
	}

	/**
	 * 簡化 SAVE 函數 ，采用固定PK 和 WHERE 固定為pK數值為條件。
	 ***/
	public function autoSave($_table,$_pk,$_data){
		$this->mConn->setCxTitle( "cx_db autoSave: " .$_table );
		$_rs = $this->setTable( $_table )
					->setPk( $_pk )
					->updateWhere( $_pk."='" . $_data[$_pk] . "'")
					->save( $_data , $_data[$_pk]);
		return $_rs;
	}


	public function checkTableArray($_table,$_input_arr){
		// 自動化 ARRAY檢查
		//exec sp_columns Tb_EndUser
		$this->mConn->setCxTitle( "cx_db checkTableArray: " .$_table);
		$_sql = "exec sp_columns " . $_table;
		$_tb = $this->sqlExec($_sql,array())->getArray();
		$_output_arr = array();

		foreach($_tb as $key => $val){
			if(array_key_exists($val['COLUMN_NAME'],$_input_arr)){
				$_output_arr[$val['COLUMN_NAME']] = $_input_arr[$val['COLUMN_NAME']];
			}
		}

		if(count($_output_arr) == 0){
			error_log("count($_output_arr) is 0", 3, "../_log/db.log");
			return false;
		}

		//print_cx($_output_arr);
		//exit(0);
		return $_output_arr;
	}

	public function save( $_input_arr , $_pk_val ) {
		$_table = $this->mTable;
		$_where = $this->mWhere;
		$_pk_key = $this->mPk;

		// 自動化 ARRAY檢查
		$_inputarr = $this->checkTableArray($_table,$_input_arr);
		if(!$_inputarr)return false;

		$this->mConn->setCxTitle( "cx_db save " );
		if ( $this->selectPkCount( $_table, $_pk_val, $_pk_key ) >0 ) {
			//updata
			if ( array_key_exists( $_pk_key, $_inputarr ) ) {
				unset( $_inputarr[$_pk_key] );
			}
			$_rs = $this->mConn->AutoExecute( $_table, $_inputarr, "UPDATE", $_where );
		}else {
			//insert
			$_rs = $this->mConn->AutoExecute( $_table, $_inputarr, "INSERT" );
		}
		if ( !$_rs )return false;
		return true;

	}

}

?>
