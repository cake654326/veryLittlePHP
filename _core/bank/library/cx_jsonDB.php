<?php
abstract class cx_jsonDB extends cx_db{
	private $mBaseDB = array();
	private $mTableData = array();
	private $mTableKey = array();
	private $mTableJson = array();
	private $mSC = 'ASC';
	private $mSearchKey1 = 0;
	private $mSearchKey2 = 0;
	private $mPK = 0;
	public function __construct( $_conn ) {
		parent::__construct( $_conn );
		//$this->mConn
		$this->mBaseDB = $this->buildTableData();

	}

	abstract public function buildTableData();



	public function sort_tableKey( $_key , $_sc = 'ASC' ) {
		//usort( $list_data, array( $mQry, 'searchSort' ) );
		$this->mSearchKey1 = $_key;
		usort( $this->mTableData, array( $this, '_sort_oneVal' ) );
		return $this->mTableData;
	}


	/*
	 * 多組排序 入口
	 * 用法
	 * $_list_data = $mTest->setJsonData($testData);
	 * $list_data = $mTest->sort_table_dev(
	 *				array(
	 *					 array("no"=>6,'sc'=>'ASC'),
	 *					 array("no"=>4,'sc'=>'ASC')
	 *					 ,array("no"=>5,'sc'=>'ASC')
	 *				 	)
	 *			 );
	 */
	public function sort_table_dev( $_sort_key , $_table_val = null) {
		$tmpData = array();
		$sort_data = array();


		($_table_val == null) and $_table_val = $this->mTableData;
		//debug
		/*
		$_table = array();
		foreach($this->mTableData as $key => $val){
			$_table[$key][0] = $val[1];
			$_table[$key][1] = $val[2];
			$_table[$key][2] = $val[3];
			$_table[$key][4] = $val[4];
			$_table[$key][5] = $val[5];
			$_table[$key][6] = $val[6];
		}*/


		$_ttt = $this->_sort_table_set_data( $_table_val, $_sort_key );
		return $_ttt;
	}

	/*
	 * 多組排序 函數
	 */
	public function _sort_oneVal_dev( $ar1, $ar2 ) {
		//print_cx($ar1);
		//exit(0);

		$_tmp1 = (is_float($ar1[0][$this->mSearchKey1]))?$ar1[0][$this->mSearchKey1]:ord( $ar1[0][$this->mSearchKey1] );
		$_tmp2 = (is_float($ar2[0][$this->mSearchKey1]))?$ar2[0][$this->mSearchKey1]:ord( $ar2[0][$this->mSearchKey1] );
		//$_tmp1 = ord( $ar1[$this->mSearchKey1] );
		//$_tmp2 = ord( $ar2[$this->mSearchKey1] );
		//ChromePhp::log("mSearchKey1:" ,$this->mSearchKey1);
		//ChromePhp::log("_tmp1:" ,$ar1[0][$this->mSearchKey1]);
		//ChromePhp::log("cake" ,"t1:" . $ar1[$this->mSearchKey1] . " ,t2:" . $ar2[$this->mSearchKey1]);
		$_t = ( $this->mSC == 'ASC' )? 1:-1;
		if ( $_tmp1 < $_tmp2 )
			return -1*$_t;
		else if ( $_tmp1 > $_tmp2 )
				return 1*$_t;
			return 0;

	}

	/*
	 * 多組排序 建立
	 */
	private function _sort_table_set_data( $_table_data , $_sort_key ) {

		// print_cx($_table_data);
		$_ans = array();

		$_now_key = $_sort_key[0]['no'];

		$this->mSC = $_sort_key[0]['sc'];
		$this->mSearchKey1 =  $_now_key;

		$_tarr = $_sort_key;
		if ( count( $_sort_key ) == 0 ) {
			return $_table_data ;
		}
		unset( $_tarr[0] );
		$_tarr = array_values( $_tarr );
		//create group $_table_data
		$_group = array();
		foreach ( $_table_data as $val ) {
			$_group[ $val[$_now_key] ][] = $val;
		}
		usort( $_group, array( $this, '_sort_oneVal_dev' ) );
		foreach ( $_group as $val ) {
			$__tmp = $this->_sort_table_set_data( $val , $_tarr );
			foreach ( $__tmp as $val ) {
				array_push( $_ans, $val );
			}
		}
		return $_ans;
	}

	public function sort_tableArray( $_key1 , $_key2 , $_sc = 'ASC' ) {
		$tmpData = array();
		$sort_data = array();
		$this->mSC = $_sc;
		$this->mSearchKey1 = $_key2;
		$this->mSearchKey2 = $_key1;
		foreach ( $this->mTableData as $val ) {
			$tmpData[$val[$this->mSearchKey2]][] = $val;
		}
		//ChromePhp::log("cake", $tmpData);
		ksort( $tmpData );
		//array_reverse
		if ( $_sc != 'ASC' ) {
			//echo "de";
			$tmpData = array_reverse( $tmpData );
		}
		//print_cx($tmpData);
		//exit(0);
		foreach ( $tmpData as $key => $val ) {
			usort( $val, array( $this, '_sort_oneVal' ) );
			foreach ( $val as $_v ) {
				$sort_data[] = $_v;
			}

			//print_cx($val);
		}
		//print_cx($sort_data);
		$this->mTableData = $sort_data;
		return $this->mTableData;
	}

	public function _sort_oneVal( $ar1, $ar2 ) {
		$_tmp1 = ord( $ar1[$this->mSearchKey1] );
		$_tmp2 = ord( $ar2[$this->mSearchKey1] );
		//ChromePhp::log("mSearchKey1:" ,$this->mSearchKey1);
		//ChromePhp::log("cake" ,"t1:" . $ar1[$this->mSearchKey1] . " ,t2:" . $ar2[$this->mSearchKey1]);
		$_t = ( $this->mSC == 'ASC' )? -1:1;
		if ( $_tmp1 < $_tmp2 )
			return -1*$_t;
		else if ( $_tmp1 > $_tmp2 )
				return 1*$_t;
			return 0;

	}

	public function setJsonData( $_arr ) {
		$_table = array();
		$_i = 0;
		foreach ( $this->mBaseDB as $val ) {
			$_table['keys'][$_i] = $val;
			//$_temp[] = "'" . $_a[$val] . "'";
			$_i++;
		}
		$_i = 0;

		foreach ( $_arr as $val ) {
			foreach ( $_table['keys'] as $_k => $_v ) {
				//$_temp[] = "'" . $_a[$val] . "'";
				$_table['data'][$val[$this->mPk]][$_k] = $val[$_v];
			}
			//$_sqlData[] = "(" . implode( ",", $_temp ) .")";
			//$_temp = array();
			$_i++;
		}
		$this->mTableJson = $_table;
		$this->mTableKey = $_tablse['keys'];
		$this->mTableData = $_table['data'];
		return $_table;
	}

	public function saveJsonFile( $_file ) {
		$_json = json_encode( $this->mTableJson );
		$fp = fopen( $_file, 'w' );
		fputs( $fp, $_json );
		fclose( $fp );
		return true;
	}

}
?>
