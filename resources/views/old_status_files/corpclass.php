<?php
//define("ROOT", $_SERVER['DOCUMENT_ROOT']);
/**
* 年报业户基础类，提供企业信息，地址，电话，法人，联络员，联络员电话信息的查询，打电话日期、时间、跟踪情况的录入。
*/
class CorpForNianBao
{
	public $mysql_table;
	public $total_num;
	public $row_length;
	public $personel;
	public $div_list;
	public $identifier_list;
	
	public function __construct($mysql_table)
	{
		$this->mysql_table = $mysql_table;
		$this->personel= array('zsr'=>'钟思燃', 'ljw'=>'刘健文', 'lxm'=>'李小明', 'hyj'=>'何奕江', 'zzh'=>'钟振辉', 'qzf'=>'秦志峰', 'bst'=>'毕树添');
		$this->div_list = array('c1'=>'中心一区', 'c2'=>'中心二区', 'c3'=>'中心三区', 'c4' =>'中心四区', 'c5' =>'中心五区', 'c6' =>'中心六区','w1'=>'西一片区', 'w2'=>'西二片区', 'w3'=>'西三片区', 'w4'=>'西四片区', 'n1'=>'北一片区', 'n2'=>'北二片区', 'n3'=>'北三片区', 'dcl'=>'待处理片区');
		$this->identifier_list = array('new_corp'=>'17年新办', 'dead_corp'=>'16年未报', 'normal_corp'=>'一般企业');
 		$this->person_with_div_16 = array('钟振辉'=>'钟振辉', '秦志峰'=>'秦志峰', '毕树添'=>'毕树添', '何奕江'=>'何奕江(中一四区)', '刘健文'=>'刘健文(中心六区)', '李小明'=>'李小明(中心三区)', '钟思燃'=>'钟思燃(西三四区)','待处理'=>'待处理','窗口'=>'窗口组(中心二区)', '副所长'=>'副所组(中心五区)');
		$this->person_with_div_17 = array('钟振辉'=>'钟振辉', '秦志峰'=>'秦志峰', '毕树添'=>'毕树添', '钟思燃'=>'钟思燃', '窗口'=>'窗口（一组片区）', '待处理'=>'待处理', '邱诗婷'=>'邱诗婷(中二三区)', '徐燕君'=>'徐燕君(中心六区)',  '冯玲'=>'冯　玲(中心五区)', '温翠怡'=>'温翠怡(中一四区)', '何奕江' => '温翠怡(中心五区)');
		$this->type_list = array('一般企业' => '2016年已报', '17年新办' => '2017年新增', '16年未报' => '2016年未报', '17年应报' => '17年应报');
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
    function get_specific_todo_num($identifier, $division)
    {
		// $identifier_list = ['new_corp'=>'17年新办', 'dead_corp'=>'16年未报', 'normal_corp' = '一般企业'];
		// $real_person = $this->personel[$person];
		$real_division = $this->div_list[$division];
		$real_identifier = $this->identifier_list[$identifier];
		$mysql_state = 'SELECT * FROM `'.$this->mysql_table.'` WHERE `type` =  "'.$real_identifier.'" AND `Division`="'.$real_division.'" AND `nian_bao_status`="未填报" AND `active_state` = 1';
		
		$result = $this->SearchDB($mysql_state);
		return $result->num_rows;
    }

	function list_specific_todo_corp($identifier, $division)
	{
		//$real_person = $this->personel[$person];
		$real_division = $this->div_list[$division];
		$real_identifier = $this->identifier_list[$identifier];
		$mysql_state = 'SELECT * FROM `'.$this->mysql_table.'` 
		WHERE 
		`type` =  "'.$real_identifier.'" 
		AND `Division`="'.$real_division.'" 
		AND `nian_bao_status`="未填报" 
		AND `active_state` = 1
		ORDER by `div_corp_index`';
		
		$result = $this->SearchDB($mysql_state);
		if ($result->num_rows > 0) {
			$whole_contentstr = '';
			$contentstr = '';
			$regnum = '';

			while ($row = $result->fetch_assoc()) 
			{
				$regnum = $row['RegNum'];
				$td_tpl='<tr> %s %s %s %s %s %s %s %s %s %s </tr>';
				$dgy = $this->ps($row['duan_guan_yuan'], 'close','dgy') ;
				$div_and_id = '<td align="center">' .$row['Division'] .'<br><div did="'.$row['div_corp_index'].'">'.$row['div_corp_index'] .'</div></td>';
				$corp_info = '<td><div class="corpname" style="font-size:200%;">'.$row['CorpName'].'</div><br>'. $this->ps($regnum, 'continue_go_on') . $this->ps($row['Addr'], 'continue_final');
				$phone_td = $this->ps_with_select($row['Phone'], 'phone', $row['phone_status'], $regnum);
				$rp_cp = '<td class="person" style="font-size:130%;width:35px;">法:'.$row['RepPerson'].'<br>联:'.$row['ContactPerson'].'</td>';
				$cphone_td = $this->ps_with_select($row['ContactPhone'], 'cphone', $row['cphone_status'], $regnum);
				$beizhu_text = $this->ps($row['InspectionStatus'], 'textarea', 'beizhu').
				'<br>
				<a class="weui-btn weui-btn_mini weui-btn_primary update_inspect" href=javascript: regnum="'.$regnum.'">保存备注</a>

				</td>
				';
				$pc_text = $this->ps($row['PhoneCallRecord'], 'textarea', 'phcall').
				'
				<div class="weui-flex">
    				<div class="weui-flex_item">
    					<a class="weui-btn weui-btn_mini weui-btn_primary update_phonecall" regnum="'.$regnum.'" href=javascript: >保存电联</a> 
    				</div>
                </div>';
				$type = $this->ps($row['type'], 'close', 'type');
				$operation_btn = $this->print_operation_btn($regnum);
				$contentstr = sprintf($td_tpl, $dgy, $div_and_id, $corp_info, $phone_td, $rp_cp, $cphone_td, $beizhu_text, $pc_text, $type, $operation_btn);
				$whole_contentstr .= $contentstr;
        	}
        	return $whole_contentstr;
    	}
	}

	function ps($content, $trigger, $indentifier = 'noclass')
	{
		$result = '';
		switch ($trigger) {
			case 'close':
				$td_tpl = '<td class="'.$indentifier.'">%s<br></td>';
				break;
			case 'open':
				$td_tpl = '<td class="'.$indentifier.'">%s';
				break;
			case 'continue_final':
				$td_tpl = '%s</td>';
				break;
			case 'continue_go_on':
				$td_tpl = '<br>%s<br>';
				break;
			case 'textarea':
				$td_tpl = '
				<td class="'.$indentifier.'_td">
					<!-- <div class="weui-flex">
						<div class="weui-flex_item"> -->
							<textarea class="'.$indentifier.'">%s
							</textarea>
						<!--</div>
					</div>-->';
				break;
			default:
				$td_tpl = '<td>%s<br></td>';
				break;
		}
		$td = sprintf($td_tpl, $content);
		$result .= $td;
		return $result;
	}

	function ps_with_select($content, $type, $selected, $regnum)
	{
		$result = '<td><p style="font-size:150%;">上一次拨打结果记录:'.$selected.'</p>';
		$td_tpl = '
						<div class="' . $type . '_val">%s</div>
						<select  class="'.$type.'_select">
						  <option value="0">请选择</option>							
						  <option value="1">1.空号</option>
						  <option value="2">2.停机</option>
						  <option value="3">3.无人接听</option>
						  <option value="4">4.表示已不为该企业工作</option>
						  <option value="5">5.表示与该企业从来没有关系</option>
						  <option value="6">6.提供了另一个联系电话</option>
						  <option value="7">7.承诺会报送</option>
						  <option value="use_input">8.其他</option>
						</select>
						<input type="text" placeholder="在这里填写其他情况" class="'.$type.'_input"></input>
					';
		$td = sprintf($td_tpl, $content);
		$result .= $td;
		if ($type == 'phone') {
			$result .= '<a class="weui-btn weui-btn_mini weui-btn_primary generate_phonecall" href="javascript:" regnum="'.$regnum.'">生成拨打注册电话情况</a></td>';
		} elseif ($type == 'cphone') {
			$result .= '<a class="weui-btn weui-btn_mini weui-btn_primary generate_cphonecall" href="javascript:" regnum="'.$regnum.'">生成拨打联络员电话情况</a></td>';
		}
		return $result;
	}

	function print_operation_btn($regnum)
	{
		$result = '';
		$mysql_state = "SELECT `Status` FROM `".$this->mysql_table."` WHERE `RegNum` = '".$regnum ."' ";
		$search = $this->SearchDB($mysql_state);
		$row = $search->fetch_assoc();
		switch ($row['Status']) {
		 	case '需要跟进':
				$status_bg_color = '#FFFF00';
		 		break;
		 	case '已经通知':
		 		$status_bg_color = '#00FF00';
		 		break;
		 	case '无可救药':
		 		$status_bg_color = '#FF0000';
		 		break;
		 	default:
		 		$status_bg_color = '#FFFF00';
		 		break;
		 	}
		$result .= '<td class="operation_btn" align="center">
						<p class="status_text" align="center" style="background-color:'.$status_bg_color.'">'
							.$row['Status'].
						'</p>
						<br>		    			
                        <div class="weui-flex">
		    				<div class="weui-flex_item">
		    				<a class="weui-btn weui-btn_mini weui-btn_primary update_success" regnum="'.$regnum.'" href=javascript: >已通知</a> 
		    				</div>
                         </div>
		    			
                        <div class="weui-flex">
		    				<div class="weui-flex_item">
		    				<a class="weui-btn weui-btn_mini weui-btn_primary update_follow" regnum="'.$regnum.'" href=javascript: >需跟进</a> 
		    				</div>
                         </div>
		    			
                        <div class="weui-flex">
		    				<div class="weui-flex_item">
		    				<a class="weui-btn weui-btn_mini weui-btn_primary update_nohope" regnum="'.$regnum.'" href=javascript: >无希望</a> 
		    				</div>
                         </div>					
					</td>';
		return $result;
	}

	function dif_type_corp_status($type)
	{
		//$type_or_person = array('17年新办' => '负责人员', '一般企业'=>'负责人员', '总表'=>'企业类型' );
		if ($type == '17年新办') {
			$field = "`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江', '窗口', '邱诗婷', '徐燕君', '冯玲', '温翠怡','骆所','待处理'";
			$group = '`designated_person`';
			$where_clause = "`type` ='". $type ."'  AND `designated_person` !='骆所' AND `active_state` = 1";
		}elseif($type == '一般企业'){
			$field = "`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江','李小明', '刘健文', '钟思燃','窗口', '副所长','骆所','待处理'";
			$group = '`designated_person`';
			$where_clause = "`type` ='". $type ."'  AND `designated_person` !='骆所' AND `active_state` = 1";
		}elseif ($type == '总表'){
			$field = "`type`, '17年新办', '一般企业','16年未报'";
			$group = '`type`';
			$where_clause = "`active_state` = 1 AND `designated_person` != '骆所'";

		}elseif ($type == '各人情况') {
			$field = "`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江','李小明', '刘健文', '钟思燃','窗口', '副所长','骆所','待处理'";
			$group = '`designated_person`';
			$where_clause = "`designated_person` !='骆所' AND `active_state` = 1";
		}


		$mysql_state = "SELECT `designated_person` as 负责人员,
		`type` as 企业类型,
		count(`nian_bao_status`='未填报' OR NULL) as `未报数量`,
		count(`nian_bao_status`='已公示' OR NULL) as `已报数量`,
		count(`nian_bao_status`) as 总数,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-01-01' and '2018-01-31') OR NULL) as 一月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-02-01' and '2018-02-28') OR NULL) as 二月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-03-01' and '2018-03-31') OR NULL) as 三月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-04-01' and '2018-04-30') OR NULL) as 四月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-05-01' and '2018-05-31') OR NULL) as 五月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-06-01' and '2018-06-30') OR NULL) as 六月报送,
		round(count(`nian_bao_status`='已公示' OR NULL)/count(`nian_bao_status`)*100,2) as 年报率
		FROM `".$this->mysql_table."`
		where ".$where_clause."
		group by ".$group."
		ORDER BY Field(".$field.")";



		$result = $this->SearchDB($mysql_state);
		$whole_code ='';
		while ($row = $result->fetch_assoc()) {
			switch ($type) {
			 	case '总表':
			 		$name = $this->type_list[$row['企业类型']];
			 		break;
			 	case '17年新办':
			 		$name = $this->person_with_div_17[$row['负责人员']];
			 		break;
			 	case '一般企业':
			 		$name = $this->person_with_div_16[$row['负责人员']];
			 	default:
			 		$name = $row['负责人员'];
			 		break;
			 	}
			$row_code =  "<tr text-align='center'>";
			$row_code .= "<td>".$name."</td>";  
			$row_code .= "<td>".$row['总数']."</td>";  
			$row_code .= "<td style=''>".$row['未报数量']." </td>";  //显示姓名
			$row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //显示邮箱
			$row_code .= "<td>".$row['一月报送']." </td>";   //显示邮箱
			$row_code .= "<td>".$row['二月报送']." </td>";   //显示邮箱
			$row_code .= "<td>".$row['三月报送']." </td>";   //显示邮箱
			$row_code .= "<td>".$row['四月报送']." </td>";   //显示邮箱
			$row_code .= "<td>".$row['五月报送']." </td>";   //显示邮箱
			$row_code .= "<td>".$row['六月报送']." </td>";   //显示邮箱
			$row_code .= "<td style='background-color:#F3F781'> ".$row['年报率']." </td>";  //显示电话
			$row_code .= "</tr>";
			$whole_code .=$row_code;
		}
		$mysql_state = "SELECT '全所' as 全所,
		count(`nian_bao_status`='未填报' OR NULL) as 未报数量,
		count(`nian_bao_status`='已公示' OR NULL) as 已报数量,
		count(`nian_bao_status`) as 总数,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-01-01' and '2018-01-31') OR NULL) as 一月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-02-01' and '2018-02-28') OR NULL) as 二月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-03-01' and '2018-03-31') OR NULL) as 三月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-04-01' and '2018-04-30') OR NULL) as 四月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-05-01' and '2018-05-31') OR NULL) as 五月报送,
		count((`nian_bao_status`='已公示' AND `UploadDate` between '2018-06-01' and '2018-06-30') OR NULL) as 六月报送,
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
			 $row_code .= "<td>".$row['一月报送']." </td>";   //显示邮箱
			 $row_code .= "<td>".$row['二月报送']." </td>";   //显示邮箱
			 $row_code .= "<td>".$row['三月报送']." </td>";   //显示邮箱
			 $row_code .= "<td>".$row['四月报送']." </td>";   //显示邮箱
			 $row_code .= "<td>".$row['五月报送']." </td>";   //显示邮箱
			 $row_code .= "<td>".$row['六月报送']." </td>";   //显示邮箱
			 $row_code .= "<td style='background-color:#F3F781'> ".$row['年报率']." </td>";  //显示电话
			 $row_code .= "</tr>";
			 $whole_code .=$row_code;
		}
		return $whole_code;
	}

	function custom_search_status($type, $startdate='', $enddate='', $trigger='')
	{
		if ($startdate =="") {
			$startdate = date("Y-m-d", strtotime("-7 Days"));
			$startdate_2w = date("Y-m-d", strtotime("-14 Days"));
		}
		if ($enddate == "") {
			$enddate = date("Y-m-d");
		}
		$whole_code ='';

		switch ($trigger) {
			case 'custom':
				# code...
				break;
			
			default:
				$mysql_state = "SELECT `designated_person` as 负责人员,
				count(`nian_bao_status`='未填报' OR NULL) as 未报数量,
				count(`nian_bao_status`='已公示' OR NULL) as 已报数量,
				count(`nian_bao_status`) as 总数,
				count((`nian_bao_status`='已公示' AND `UploadDate` between '". $startdate ." 00:00:00' and '". $enddate ." 23:59:59') OR NULL) as 一周情况,
				round(count((`nian_bao_status`='已公示' AND `UploadDate` between '". $startdate ." 00:00:00' and '". $enddate ." 23:59:59') OR NULL)/count(`nian_bao_status`)*100,2) as 一周提升,
				count((`nian_bao_status`='已公示' AND `UploadDate` between '". $startdate_2w ." 00:00:00' and '". $enddate ." 23:59:59') OR NULL) as 二周情况,
				round(count((`nian_bao_status`='已公示' AND `UploadDate` between '". $startdate_2w ." 00:00:00' and '". $enddate ." 23:59:59') OR NULL)/count(`nian_bao_status`)*100,2) as 二周提升
				FROM `".$this->mysql_table."`
				where `type` ='". $type ."'  AND `active_state` = 1 AND `designated_person` != '骆所'
				group by `designated_person`
				ORDER BY Field(`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江', '窗口', '邱诗婷', '徐燕君', '冯玲', '温翠怡','骆所','待处理')";
				$result = $this->SearchDB($mysql_state);
				while ($row = $result->fetch_assoc()) {
					 $row_code =  "<tr text-align='center'>";
					 $row_code .= "<td>".$this->person_with_div_17[$row['负责人员']]."</td>";
					 $row_code .= "<td>".$row['总数']."</td>";
					 $row_code .= "<td style=''>".$row['未报数量']." </td>";  //
					 $row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //
					 $row_code .= "<td>".$row['一周情况']." </td>";   //
					 $row_code .= "<td style='background-color:#F3F781'> ".$row['一周提升']." </td>";  //
 					 $row_code .= "<td>".$row['二周情况']." </td>";   //
					 $row_code .= "<td style='background-color:#F3F781'> ".$row['二周提升']." </td>"; 
					 $row_code .= "</tr>";
					 $whole_code .=$row_code;
				}
				$mysql_state = "SELECT '全所' as 全所,
				count(`nian_bao_status`='未填报' OR NULL) as 未报数量,
				count(`nian_bao_status`='已公示' OR NULL) as 已报数量,
				count(`nian_bao_status`) as 总数,
				count((`nian_bao_status`='已公示' AND `UploadDate` between '". $startdate ." 00:00:00' and '". $enddate ." 23:59:59') OR NULL) as 一周情况,
				round(count((`nian_bao_status`='已公示' AND `UploadDate` between '". $startdate ." 00:00:00' and '". $enddate ." 23:59:59') OR NULL)/count(`nian_bao_status`)*100,2) as 一周提升,
				count((`nian_bao_status`='已公示' AND `UploadDate` between '". $startdate_2w ." 00:00:00' and '". $enddate ." 23:59:59') OR NULL) as 二周情况,
				round(count((`nian_bao_status`='已公示' AND `UploadDate` between '". $startdate_2w ." 00:00:00' and '". $enddate ." 23:59:59') OR NULL)/count(`nian_bao_status`)*100,2) as 二周提升
				FROM `".$this->mysql_table."`
				where `type` ='". $type ."'  AND `active_state` = 1 AND `designated_person` != '骆所'";
				$result = $this->SearchDB($mysql_state);
				while ($row = $result->fetch_assoc()) {
					 $row_code =  "<tr text-align='center'>";
					 $row_code .= "<td>".$row['全所']."</td>";
					 $row_code .= "<td>".$row['总数']."</td>";   //
					 $row_code .= "<td style=''>".$row['未报数量']." </td>";  //
					 $row_code .= "<td style='background-color:#00ff00'>".$row['已报数量']." </td>";   //
					 $row_code .= "<td>".$row['一周情况']." </td>";   //
					 $row_code .= "<td style='background-color:#F3F781'> ".$row['一周提升']." </td>";  //
 					 $row_code .= "<td>".$row['二周情况']." </td>";   //
					 $row_code .= "<td style='background-color:#F3F781'> ".$row['二周提升']." </td>"; 
					 $row_code .= "</tr>";
					 $whole_code .=$row_code;
				}
				break;
		}
		return $whole_code;
	}
	function new_corp_phone_call_status()
	{
		$mysql_state = "SELECT `designated_person` as 拨打人员,
		count(`designated_person`) as 分配数量,
		count(`phone_status` != '' OR NULL) as 已拨打数量,
		round(count(`phone_status` != '' OR NULL)/count(`designated_person`) * 100) as 完成率
		FROM `" .$this->mysql_table."`
		WHERE `type` = '17年新办' AND `active_state` = '1' AND `designated_person` != '骆所' AND `nian_bao_status` = '未填报'
		GROUP BY `designated_person`
		ORDER BY Field(`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江', '窗口', '邱诗婷', '徐燕君', '冯玲', '温翠怡','骆所','待处理')";
		$result = $this->SearchDB($mysql_state);
		$whole_code = '';
		while ($row = $result->fetch_assoc()) {
			 $row_code =  "<tr text-align='center'>";
			 $row_code .= "<td>".$this->person_with_div_17[$row['拨打人员']]."</td>";   //
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
		$mysql_state = "SELECT `designated_person` as 拨打人员,
		count(`designated_person`) as 分配数量,
		count(`phone_status` != '' OR NULL) as 已拨打数量,
		round(count(`phone_status` != '' OR NULL)/count(`designated_person`) * 100) as 完成率
		FROM `" .$this->mysql_table."`
		WHERE `type` = '一般企业' AND `active_state` = '1' AND `designated_person` != '骆所' AND `nian_bao_status` = '未填报'
		GROUP BY `designated_person`
		ORDER BY Field(`designated_person`, '钟振辉', '秦志峰', '毕树添', '何奕江', '窗口', '邱诗婷', '徐燕君', '冯玲', '温翠怡','骆所','待处理')";
		$result = $this->SearchDB($mysql_state);
		$whole_code = '';
		while ($row = $result->fetch_assoc()) {
			 $row_code =  "<tr text-align='center'>";
			 $row_code .= "<td>".$this->person_with_div_16[$row['拨打人员']]."</td>";   //
			 $row_code .= "<td style=''>".$row['分配数量']." </td>";  //
			 $row_code .= "<td style='background-color:#00ff00'>".$row['已拨打数量']." </td>";   //
			 $row_code .= "<td style='background-color:#F3F781'> ".$row['完成率']." </td>";  //
			 $row_code .= "</tr>";
			 $whole_code .=$row_code;
		}
		return $whole_code;
	}

	function update_phcall_record($regnum, $record, $acp)
	{
		//$ori_record_result = $this->SearchDB('SELECT `PhoneCallRecord` FROM `'.$this->mysql_table.'` WHERE `RegNum` = "'.$regnum .'"');
		//$row = $ori_record_result->fetch_assoc();
		//$ori_record = $row['PhoneCallRecord'];
		//$new_record = $ori_record.$record;
		$last_call_time = date('Y-m-d');
		$result = $this->SearchDB('UPDATE `'.$this->mysql_table.'` SET `PhoneCallRecord` ="'.$record.'" WHERE `RegNum` ="'.$regnum.'"');
		$result = $this->SearchDB('UPDATE `'.$this->mysql_table.'` SET `actual_phone_call_person` ="'.$acp.'" WHERE `RegNum` ="'.$regnum.'"');
		$result = $this->SearchDB('UPDATE `'.$this->mysql_table.'` SET `call_date` ="'.$last_call_time.'" WHERE `RegNum` ="'.$regnum.'"');
		if ($result == 1 ) {
			return 'success';
		}else{
			return 'fail';
		}
	}

	function update_beizhu_record($regnum, $record, $acp)
	{
		//$ori_record_result = $this->SearchDB('SELECT `PhoneCallRecord` FROM `'.$this->mysql_table.'` WHERE `RegNum` = "'.$regnum .'"');
		//$row = $ori_record_result->fetch_assoc();
		//$ori_record = $row['PhoneCallRecord'];
		//$record = $ori_record.$record;
		$result = $this->SearchDB('UPDATE `'.$this->mysql_table.'` SET `InspectionStatus` ="'.$record.'" WHERE `RegNum` ="'.$regnum.'"');
		$result = $this->SearchDB('UPDATE `'.$this->mysql_table.'` SET `actual_phone_call_person` ="'.$acp.'" WHERE `RegNum` ="'.$regnum.'"');
		if ($result == 1 ) {
			return 'success';
		}else{
			return 'fail';
		}
	}

	function update_hope_nohope($regnum, $record)
	{
		$result = $this->SearchDB('UPDATE `'.$this->mysql_table.'` SET `Status` ="'.$record.'" WHERE `RegNum` ="'.$regnum.'"');
		if ($result == 1 ) {
			return 'success';
		}else{
			return 'fail';
		}
	}

	function update_phone_status($regnum, $record)
	{
		$result = $this->SearchDB("UPDATE `".$this->mysql_table."` SET `phone_status` = '".$record."' WHERE `RegNum` = ".$regnum);
		return $result;	
	}

	function update_cphone_status($regnum, $record)
	{
		$result = $this->SearchDB("UPDATE `".$this->mysql_table."` SET `cphone_status` = '".$record."' WHERE `RegNum` = ".$regnum);
		return $result;	
	}
}



?>