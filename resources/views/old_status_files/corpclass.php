<?php
//define("ROOT", $_SERVER['DOCUMENT_ROOT']);
/**
* 年报业户基础类，提供企业信息，地址，电话，法人，联络员，联络员电话信息的查询，打电话日期、时间、跟踪情况的录入。
*/
class CorpForNianBao
{
	public $mysql_table;
	// public $personel;
	// public $div_list;
	
	public function __construct($mysql_table)
	{
		$this->mysql_table = $mysql_table;
		// $this->personel= array('zsr'=>'钟思燃', 'ljw'=>'刘健文', 'lxm'=>'李小明', 'hyj'=>'何奕江', 'zzh'=>'钟振辉', 'qzf'=>'秦志峰', 'bst'=>'毕树添');
		// $this->div_list = array('c1'=>'中心一区', 'c2'=>'中心二区', 'c3'=>'中心三区', 'c4' =>'中心四区', 'c5' =>'中心五区', 'c6' =>'中心六区','w1'=>'西一片区', 'w2'=>'西二片区', 'w3'=>'西三片区', 'w4'=>'西四片区', 'n1'=>'北一片区', 'n2'=>'北二片区', 'n3'=>'北三片区', 'dcl'=>'待处理片区');
 		// $this->person_with_div_normal = array('指定'=>'指定');
		// $this->person_with_div_new = array('指定'=>'指定');
		$this->type_list = array('一般企业' => '一般企业', '新办' => '新办', '去年未报' => '去年未报');
	}

    public function SearchDB($mysql_state)
    {
        include_once ('myconf/dbconfig.php');
        $con = mysqli_connect(MYSQLHOST, MYSQLUSER, MYSQLPASSWORD, MYSQLDATABASE,MYSQLPORT);        
        if (!$con){
            die('Could not connect: ' . mysql_error());
        }
        $con->query("SET NAMES 'UTF8'");
        if(!$con->query($mysql_state)){
            $result = printf("Error: %s\n", $con->error);
        }else{
            $result = $con->query($mysql_state);
        }
        $con->close();
        return $result;    
        
    }

	function dif_type_corp_status($type)
	{
		//$type_or_person = array('17年新办' => '负责人员', '一般企业'=>'负责人员', '总表'=>'企业类型' );
		if ($type == '17年新办') {
			// $field = "`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江', '窗口', '邱诗婷', '徐燕君', '冯玲', '温翠怡','骆所','待处理'";
			$group = '`division`';
			$where_clause = "`type` ='". $type ."' AND `active_state` = 1";
		}elseif($type == '一般企业'){
			$field = "`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江','李小明', '刘健文', '钟思燃','窗口', '副所长','骆所','待处理'";
			$group = '`designated_person`';
			$where_clause = "`type` ='". $type ."'  AND `designated_person` !='骆所' AND `active_state` = 1";
		}elseif ($type == '总表'){
			$field = "`type`, '17年新办', '一般企业','16年未报'";
			$group = '`type`';
			$where_clause = "`active_state`";
		}elseif ($type == '各人情况') {
			$field = "`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江','李小明', '刘健文', '钟思燃','窗口', '副所长','骆所','待处理'";
			$group = '`division`';
			$where_clause = "`active_state` = 1";
		}


		$mysql_state = "SELECT 
		`type` as 企业类型,
		`division` as 片区,
		count(`nian_bao_status`='未填报' OR NULL) as `未报数量`,
		count(`nian_bao_status`='已公示' OR NULL) as `已报数量`,
		count(`nian_bao_status`) as 总数,
		round(count(`nian_bao_status`='已公示' OR NULL)/count(`nian_bao_status`)*100,2) as 年报率
		FROM `".$this->mysql_table."`
		where ".$where_clause."
		group by ".$group;



		$result = $this->SearchDB($mysql_state);
		$whole_code ='';
		while ($row = $result->fetch_assoc()) {
			switch ($type) {
			 	case '总表':
			 		$name = $this->type_list[$row['企业类型']];
			 		break;
			 	default:
			 		$name = $row['片区'];
			 		break;
			 	}
			$row_code =  "<tr text-align='center'>";
			$row_code .= "<td>".$name."</td>";  
			$row_code .= "<td>".$row['总数']."</td>";  
			$row_code .= "<td style=''>".$row['未报数量']." </td>";  //显示姓名
			$row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //显示邮箱
			$row_code .= "<td style='background-color:#F3F781'> ".$row['年报率']." </td>";  //显示电话
			$row_code .= "</tr>";
			$whole_code .=$row_code;
		}
		$mysql_state = "SELECT '全所' as 全所,
		count(`nian_bao_status`='未填报' OR NULL) as 未报数量,
		count(`nian_bao_status`='已公示' OR NULL) as 已报数量,
		count(`nian_bao_status`) as 总数,
		round(count(`nian_bao_status`='已公示' OR NULL)/count(`nian_bao_status`)*100,2) as 年报率
		FROM `".$this->mysql_table."`
		where ".$where_clause;
		$result = $this->SearchDB($mysql_state);
		while ($row = $result->fetch_assoc()) {
			 $row_code =  "<tr text-align='center'>";
			 $row_code .= "<td>".$row['全所']."</td>";   //显示ID
			 $row_code .= "<td>".$row['总数']."</td>";  
			 $row_code .= "<td style=''>".$row['未报数量']." </td>";  //显示姓名
			 $row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //显示邮箱
			 $row_code .= "<td style='background-color:#F3F781'> ".$row['年报率']." </td>";  //显示电话
			 $row_code .= "</tr>";
			 $whole_code .=$row_code;
		}
		return $whole_code;
	}


	function new_corp_status()
	{
		
		$mysql_state = "SELECT 
		`division` as 片区,
		count(`nian_bao_status`='未填报' OR NULL) as `未报数量`,
		count(`nian_bao_status`='已公示' OR NULL) as `已报数量`,
		count(`nian_bao_status`) as 总数,
		round(count(`nian_bao_status`='已公示' OR NULL)/count(`nian_bao_status`)*100,2) as 年报率
		FROM `".$this->mysql_table."`
		where `type` = '新办'
		group by `division`
		";

		$result = $this->SearchDB($mysql_state);
		$whole_code ='';
		while ($row = $result->fetch_assoc()) {
			$name = $row['片区'];
			$row_code =  "<tr text-align='center'>";
			$row_code .= "<td>".$name."</td>";  
			$row_code .= "<td>".$row['总数']."</td>";  
			$row_code .= "<td style=''>".$row['未报数量']." </td>";  //显示姓名
			$row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //显示邮箱
			$row_code .= "<td style='background-color:#F3F781'> ".$row['年报率']." </td>";  //显示电话
			$row_code .= "</tr>";
			$whole_code .=$row_code;
		}
		$mysql_state = "SELECT '全所' as 全所,
		count(`nian_bao_status`='未填报' OR NULL) as 未报数量,
		count(`nian_bao_status`='已公示' OR NULL) as 已报数量,
		count(`nian_bao_status`) as 总数,
		round(count(`nian_bao_status`='已公示' OR NULL)/count(`nian_bao_status`)*100,2) as 年报率
		FROM `".$this->mysql_table."`
		where  `type` = '新办'";
		$result = $this->SearchDB($mysql_state);
		while ($row = $result->fetch_assoc()) {
			 $row_code =  "<tr text-align='center'>";
			 $row_code .= "<td>".$row['全所']."</td>";   //显示ID
			 $row_code .= "<td>".$row['总数']."</td>";  
			 $row_code .= "<td style=''>".$row['未报数量']." </td>";  //显示姓名
			 $row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //显示邮箱
			 $row_code .= "<td style='background-color:#F3F781'> ".$row['年报率']." </td>";  //显示电话
			 $row_code .= "</tr>";
			 $whole_code .=$row_code;
		}
		return $whole_code;
	}
	function normal_corp_status()
	{
		
		$mysql_state = "SELECT 
		`division` as 片区,
		count(`nian_bao_status`='未填报' OR NULL) as `未报数量`,
		count(`nian_bao_status`='已公示' OR NULL) as `已报数量`,
		count(`nian_bao_status`) as 总数,
		round(count(`nian_bao_status`='已公示' OR NULL)/count(`nian_bao_status`)*100,2) as 年报率
		FROM `".$this->mysql_table."`
		where `type` = '一般企业'
		group by `division`
		";

		$result = $this->SearchDB($mysql_state);
		$whole_code ='';
		while ($row = $result->fetch_assoc()) {
			$name = $row['片区'];
			$row_code =  "<tr text-align='center'>";
			$row_code .= "<td>".$name."</td>";  
			$row_code .= "<td>".$row['总数']."</td>";  
			$row_code .= "<td style=''>".$row['未报数量']." </td>";  //显示姓名
			$row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //显示邮箱
			$row_code .= "<td style='background-color:#F3F781'> ".$row['年报率']." </td>";  //显示电话
			$row_code .= "</tr>";
			$whole_code .=$row_code;
		}
		$mysql_state = "SELECT '全所' as 全所,
		count(`nian_bao_status`='未填报' OR NULL) as 未报数量,
		count(`nian_bao_status`='已公示' OR NULL) as 已报数量,
		count(`nian_bao_status`) as 总数,
		round(count(`nian_bao_status`='已公示' OR NULL)/count(`nian_bao_status`)*100,2) as 年报率
		FROM `".$this->mysql_table."`
		where  `type` = '一般企业'";
		$result = $this->SearchDB($mysql_state);
		while ($row = $result->fetch_assoc()) {
			 $row_code =  "<tr text-align='center'>";
			 $row_code .= "<td>".$row['全所']."</td>";   //显示ID
			 $row_code .= "<td>".$row['总数']."</td>";  
			 $row_code .= "<td style=''>".$row['未报数量']." </td>";  //显示姓名
			 $row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //显示邮箱
			 $row_code .= "<td style='background-color:#F3F781'> ".$row['年报率']." </td>";  //显示电话
			 $row_code .= "</tr>";
			 $whole_code .=$row_code;
		}
		return $whole_code;
	}

	function new_corp_phone_call_status()
	{
		$mysql_state = "SELECT `division` as 片区,
		count(`division`) as 分配数量,
		count(`phone_status` != '' OR NULL) as 已拨打数量,
		round(count(`phone_status` != '' OR NULL)/count(`division`) * 100) as 完成率
		FROM `" .$this->mysql_table."`
		WHERE `type` = '新办' AND `active_state` = '1'  AND `nian_bao_status` = '未填报'
		GROUP BY `division`";
		$result = $this->SearchDB($mysql_state);
		$whole_code = '';
		while ($row = $result->fetch_assoc()) {
			 $row_code =  "<tr text-align='center'>";
			 $row_code .= "<td>".$row['片区']."</td>";   //
			 $row_code .= "<td style=''>".$row['分配数量']." </td>";  //
			 $row_code .= "<td style='background-color:#00ff00'>".$row['已拨打数量']." </td>";   //
			 $row_code .= "<td style='background-color:#F3F781'> ".$row['完成率']." </td>";  //
			 $row_code .= "</tr>";
			 $whole_code .=$row_code;
		}
		return $whole_code;
	}

	function normal_corp_phone_call_status()
	{
		$mysql_state = "SELECT `division` as 片区,
		count(`division`) as 分配数量,
		count(`phone_status` != '' OR NULL) as 已拨打数量,
		round(count(`phone_status` != '' OR NULL)/count(`division`) * 100) as 完成率
		FROM `" .$this->mysql_table."`
		WHERE `type` = '一般企业' AND `active_state` = '1' AND `nian_bao_status` = '未填报'
		GROUP BY `division`";
		$result = $this->SearchDB($mysql_state);
		$whole_code = '';
		while ($row = $result->fetch_assoc()) {
			 $row_code =  "<tr text-align='center'>";
			 $row_code .= "<td>".$row['片区']."</td>";   //
			 $row_code .= "<td style=''>".$row['分配数量']." </td>";  //
			 $row_code .= "<td style='background-color:#00ff00'>".$row['已拨打数量']." </td>";   //
			 $row_code .= "<td style='background-color:#F3F781'> ".$row['完成率']." </td>";  //
			 $row_code .= "</tr>";
			 $whole_code .=$row_code;
		}
		return $whole_code;
	}
}

?>