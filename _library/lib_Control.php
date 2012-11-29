<?php
// lib_Control.php _library/lib_COntrol.php
include "../_model/mod_departmentSeat.php";
class lib_Control {
	var $sub_name = array( 0=> "總級分", 1=>"國文" , 2=>"英文" , 3=>"數學" , 4=>"社會" , 5=>"自然" );
	var $mDepar = null;
	var $mDepar_arr = null;
	var $mCore = null;
	var $mScore = array();

	public function __construct( $Core ) {
		//parent::__construct( $_conn );
		$this->mCore = &$Core;
		$this->mDepar= new mod_departmentSeat( $Core->getDB() );

	}

	public function run() {
		$_dr = $this->search1();
		//print_cx($_dr);
		echo "run run1 ";
		$_seat = array_merge( $_dr, $this->search2( $_dr ) );

		//exit( 0 );
		return $_seat;
	}
	/*
  [0] => Array
        (
            [Dep_Code] => 001432
            [Sch_Code] => 001
            [Sch_Name] => 國立臺灣大學
            [Dep_Name] =>
            [Dep_Name_Samp] => 生物產業機電工程學系
            [Comp_Sub1] => 0
            [Comp_Sub2] => 0
            [Comp_Sub3] => 7
            [Comp_Sub4] => 0
            [Comp_Sub5] => 0
            [DepAmount] => 18
            [Score_Bef] =>
            [Per] =>
            [Area_Code] => 1
            [Kind_Code] => B
            [Pub_Code] => 1
        )

    [3] => Array
        (
            [Dep_Code] => 001042
            [Filter_Field1] => 國文+社會
            [Filter_Score1] => 28
            [Filter_MG1] => variant Object

            [Filter_Field2] => 英文
            [Filter_Score2] => 14
            [Filter_MG2] => variant Object

            [Filter_Field3] =>
            [Filter_Score3] =>
            [Filter_MG3] =>
            [Filter_Field4] =>
            [Filter_Score4] =>
            [Filter_MG4] =>
            [Filter_Field5] =>
            [Filter_Score5] =>
            [Filter_MG5] =>
            [Filter_Field6] =>
            [Filter_Score6] =>
            [Filter_MG6] =>
        )

*/
	private function search1() {
		echo "run search1 ";
		//print_r($this->mCore->Post());
		for ( $_i =1;$_i <6;$_i++ ) {
			$this->mScore[$_i] = $this->mCore->mPost['Comp_Score' . $_i];
			$this->mScore[0] += $this->mScore[$_i];
		}

		$_department_arr = $this->mDepar->getSeat(
			array(  $this->mScore[1],
				$this->mScore[2],
				$this->mScore[3],
				$this->mScore[4],
				$this->mScore[5] )
		);
		//print_r($mPost);
		return $_department_arr;
	}
	private function search2( $_search1 ) {
		$_sea2 = array();
		$_s2 = $this->mDepar->getStep2();
		//print_cx( $_s2 );
		foreach ( $_s2 as $val ) {
			$_sea2[$val['Dep_Code']] = $val;
		}
		//print_cx( $_sea2 );
		//001012 001022 001032 WenXue
		$step_data =array();
		//測試用 - 因SEA2資料 不足。
		foreach ( $_search1 as $val ) {
			$_tmp = $_sea2[$val['Dep_Code']] ;
			if ( is_array( $_tmp ) ) {
				// $step_data[$val['Dep_Code']] = $_tmp;
				$step_data[$val['Dep_Code']] = array_merge( $val, $_tmp );
			}
		}
		//print_cx($step_data);

		/*
[Dep_Code] => 001052
            [Filter_Field1] => 英文
            [Filter_Score1] => 15
            [Filter_MG1] => variant Object

            [Filter_Field2] => 總級分
            [Filter_Score2] => 69
            [Filter_MG2] => variant Object
*/
		$_temp_arr = array();
		$_temp_arr2 = array();
		foreach ( $step_data as $key => $val ) {
			for ( $_i = 1;$_i < 7; $_i++ ) {

				$_temp_arr = $this->checkFilter( $_i, $val );

				//echo "[debug] check [" . $val['Dep_Code'] . "] [" . $_i . "]: " . print_cx($_arr,true) . " <br>";
				$_temp_arr2 = array_merge( $_temp_arr2, $_temp_arr );

			}
			$step_data[$key] = array_merge( $val, $_temp_arr2 );

		}

		//print_cx( $step_data );
		//exit( 0 );
		return $step_data;
	}

	/*
            [Dep_Code] => 001052
            [Filter_Field1] => 英文
            [Filter_Score1] => 15
            [Filter_MG1] => variant Object
*/
	/***
	 * 拆解 並且輸出 分析結果
	 * mg : 被率
     ***/
	private function checkFilter( $sub_key, $_step ) {
		$_field = $_step['Filter_Field'. $sub_key];
		$_score = $_step['Filter_Score'. $sub_key];
		$_mg = $_step['Filter_MG'. $sub_key];
		$mFilter = array();
		//if($_field == '')return false;
		$aField = explode( "+", $_field );
		//array_search
		foreach ( $aField as $_val ) {
			$_key[] = array_search( $_val, $this->sub_name );

			//$_key['ans'] = $this->control_score( $sub_key, $_key,$_score,$mg);
			$_fl = $this->control_score( $sub_key, $_key, $_score, $_mg );//取得關卡條件 RISK
			//!* 5.計算機率：將各階段的@Risk相加 + (@OprRisk-1)*125)≦@Risk<@OprRisk*125


			$mFilter = array_merge( $mFilter, $_fl );
		}
		//print_cx( $_key );
		//print_cx($mFilter);
		//return print_cx($aField,true) . "key:" . print_cx($_key,true);
		return $mFilter;
	}

	/* ***
	 * 為 checkFileter 內部函數：分析 分數加總並且 計算幾率...等
	 * 計算第二階段計算 條件
	 *
	 * **/
	private function control_score( $_sub_key , $_afield_key, $_db_score , $_mg ) {
		$_score = 0;
		//print_cx($_afield_key);
		if ( is_numeric( $_afield_key[0] ) ) {
			foreach ( $_afield_key as $val ) {
				if ( is_numeric( $val ) ) {
					$_score +=  $this->mScore[$val];
				}

			}
		}else {
			$_score = '';
		}

		// !*  幾率 計算 RISK 。。。。。
		$_stud_power = 0;
		$nRisk = 0;//Risk
		//_pro_CalcProb_risk($_StepProb , $_LastProb ,$_PAR == null)

		$Step2  = array();
		$Step2['Stud_Score' . $_sub_key ] = $_score;
		$Step2['Filter_Score' . $_sub_key ] = $_db_score;
		$Step2['Stud_Power' . $_sub_key ] = $_stud_power; // remove
		$Step2['RISK_' . $_sub_key ] = $nRisk;
		$Step2['step_sub_key'] = $_sub_key;
		return $Step2;
	}

	/* **
	 * 計算 幾率 的 RISk [為文件 15,處理程序-各階段機率預估[Pro_CalcProb] - 參數]
	 *  	參數說明
	 * @StepProb = 關卡原始機率
	 * @LastProb = 最後關卡原始機率
	 * @PAR = 預設 1.25
	 *
	 * @return = RISK
	 */
	private function _pro_CalcProb_risk( $_StepProb , $_LastProb , $_PAR == null ) {
		if ( $_PAR == NULL ) {
			$_PAR = $Core->config( 'PAR' );
		}
		/*
參數
計算說明
1. [@OPR]  if ( @LastProb>=1 ) @OPR = 1 else @OPR = -1
2. [@VALProb][@VALOPR]
	+ if ( @LastProb >= @PAR ) @VALProb =1
	+ elseif( 1 < @LastProb <= @PAR ) @VALProb = 5
	+ elseif( (@PAR/5)*3  < @LastProb <= 1 ) @VALProb = 3
	+ elseif(  @LastProb<=(@PAR/5)*3 ) @CalcReturn=0
3.[@CalcReturn] = 1+ |(@LastProb*4)- @VALProb| * (@StepProb+@OPR)
			//((PS. 1加 @LastProb*4 - @VALProb 取絕對值 乘上(@StepProb+@OPR)
4.[@Risk]
	+ if( @CalcReturn>=@PAR ) @Risk=0
	+ elseif( 1 <= @CalcReturn <= @PAR ) @OPR2=100
	+ elseif( @CalcReturn < 1 ) @OPR2=200
	+ @Risk = (@PAR- @CalcReturn) *@OPR2 取平方值
*/
		$_opr = ( $_LastProb >= 1 )?1:-1;
		$_valprob = 0;
		$_CalcReturn = null;
		if ( $_LastProb >= $_PAR ) {
			$_valprob = 1;
		}elseif ( 1 < $_LastProb && $_LastProb <= $_PAR ) {
			$_valprob = 5;
		}elseif ( ( ( $_PAR/5 )*3 ) < $_LastProb && $_LastProb <=1 ) {
			$_valprob = 3;
		}elseif ( $_LastProb <= ( ( $_PAR/5 )*3 ) ) {
			$_CalcReturn = 0;
		}
		if($_CalcReturn != null){
			$_CalcReturn = abs( ( $_LastProb*4 ) - $_valprob ) * ( $_StepProb + $_opr );
		}
		if ( $_CalcReturn >= $_PAR ) {
			return $_risk = 0;
		}elseif ( 1 <= $_CalcReturn && $_CalcReturn <= $_PAR ) {
			$_opr2 = 100;
		}elseif ( $_CalcReturn < 1 ) {
			$_opr2 = 200;
		}
		$_risk = ( $_PAR - $_CalcReturn ) * pow( $_opr2, 2 );
		return $_risk;
	}



}
?>
