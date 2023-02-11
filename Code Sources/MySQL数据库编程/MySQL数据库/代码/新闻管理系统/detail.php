<?php

# 查看行文详情
header('Content-type:text/html;charset=utf-8');

# 接收新闻id
$id = $_GET['id'] ?? 0;
if(!$id){
	header('Refresh:3;url=index.php');
	echo '非法访问！';
	exit;
}

# 获取新闻数据
include 'Sql.php';

$conn = connect('root','root','news',$error);
# 判定结果
if(!$conn){
	header('Refresh:3;url=index.php');
	echo $error;
	exit;
}

# 查询数据
$sql = "select n.*,a.name from news n left join author a on n.a_id = a.id where n.id = {$id}";
$news = read($conn,$sql,$error);
if(!$news){
	header('Refresh:3;url=index.php');
	echo '您所访问的新闻不存在！';
	exit;
}

# 加载模板
include 'detail.html';