<?php 
define("ROOT", $_SERVER['DOCUMENT_ROOT']);
header("Content-type:text/html;charset=utf-8;");
require ('corpclass.php');
$division = "合益所";
$tablename = "2019年度年报进度统计";
$corp = new CorpForNianBao("2019_nianbao_corp");
// $corp = new CorpForNianBao("2019_nianbao_corp_copy");
$new_corp_status = $corp->new_corp_status();
$normal_corp_status = $corp->normal_corp_status();
$zongbiao_status = $corp->dif_type_corp_status('总表');
// $custom_corp_status = $corp->custom_search_status('17年新办');
$all_person_ststus = $corp->dif_type_corp_status('各人情况');

$new_corp_phone_call_status = $corp->new_corp_phone_call_status();
$normal_corp_phone_call_status = $corp->normal_corp_phone_call_status();

//$work_nums = $corp->get_specific_todo_num($person, $indentifier, $div);

echo <<< EOT

<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name=”viewport” width= device-width, initial-scale=1, maximum-scale=1> 
<title>年报进度统计</title>
<link rel="stylesheet" href="/jqueryui/jquery-ui.css">
<link rel="stylesheet" href="/css/status.css">
<script type="text/javascript" src="/js/jquery3.3.1.js"></script>
<script type="text/javascript" src="/js/status.js"></script>
<script src="/jqueryui/jquery-ui.js"></script>
</head>

<body style="background:grey">

<table align='center' border='1' width='1280' >
<caption><h1>$division $tablename</h1></caption>
<tr>
	<td align="center" class="main-frame">
		<div><h1>全所年报进度统计表</h1></div>
		<div>
		<table class="sub-table" border='1' align="center">
			<tr>
			<th>企业类型</th>
			<th>总数</th>
			<th style="">未报数量</th>
			<th style="background-color:#00ff00">已报数量</th>
			<th style="background-color:#F3F781">年报率</th>
			</tr>
			$zongbiao_status
		</table>
		</div>
		<a href="/" class="ui-button ui-widget ui-corner-all">返回入口</a>
	</td>
	<td align="center" class="main-frame">
		<div><h1>各片区年报率明细表</h1></div>
		<div>
		<table class="sub-table" border='1' align="center">
			<tr>
			<th>片区</th>
			<th>总数</th>
			<th style="">未报数量</th>
			<th style="background-color:#00ff00">已报数量</th>
			<th style="background-color:#F3F781">年报率</th>
			</tr>
			$all_person_ststus
		</table>
		<p>
		</p>

		</div>
		<a href="/" class="ui-button ui-widget ui-corner-all">返回入口</a>
	</td>
</tr>

<tr>
	<td align="center" class="main-frame">
		<div><h1>2018年新增企业 - 分月统计表</h1></div>
		<div>
		<table class="sub-table" border='1' align="center">
			<tr>
			<th>片区</th>
			<th>总数</th>
			<th style="">未报数量</th>
			<th style="background-color:#00ff00">已报数量</th>
			<th style="background-color:#F3F781">年报率</th>
			</tr>
			$new_corp_status
		</table>
		</div>
		<a href="/" class="ui-button ui-widget ui-corner-all">返回入口</a>
	</td>
	<td align="center" class="main-frame">
		<div><h1>2017年度正常企业 - 分月统计表</h1></div>
		<div>
		<table class="sub-table" border='1' align="center">
			<tr>
			<th>片区</th>
			<th>总数</th>
			<th style="">未报数量</th>
			<th style="background-color:#00ff00">已报数量</th>
			<th style="background-color:#F3F781">年报率</th>
			</tr>
			$normal_corp_status
		</table>
		</div>
		<a href="/" class="ui-button ui-widget ui-corner-all">返回入口</a>
	</td>
	<td class="main-frame">
	</td>
</tr>
<tr>
	<td align="center" class="main-frame">
		<div><h1>2017正常企业 - 拨打电话进度统计表</h1></div>
		<div>
		<table class="sub-table" border='1' align="center">
			<tr>
			<th>片区</th>
			<th style="">负责数量</th>
			<th style="background-color:#00ff00">已打数量</th>
			<th style="background-color:#F3F781">完成率</th>
			</tr>
			<tr>
			$normal_corp_phone_call_status
			</tr>
		</table>
		</div>
		<a href="/" class="ui-button ui-widget ui-corner-all">返回入口</a>
	</td>

	<td align="center" class="main-frame">
		<div><h1>2018新增企业 - 拨打电话进度统计表</h1></div>
		<div>
		<table class="sub-table" border='1' align="center">
			<tr>
			<th>片区</th>
			<th style="">负责数量</th>
			<th style="background-color:#00ff00">已打数量</th>
			<th style="background-color:#F3F781">完成率</th>
			</tr>
			<tr>
			$new_corp_phone_call_status
			</tr>
		</table>
		</div>
		<a href="/" class="ui-button ui-widget ui-corner-all">返回入口</a>
	</td>
</tr>
</table>
</body>
</html>
EOT;

 ?>