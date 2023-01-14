<?php

# 更新新闻

# 接收数据：数组接收可能修改的数据（下标与表字段名一致）
$id = $_POST['id'];
$data['title'] 	= $_POST['title'] ?? '';
$data['content']= $_POST['content'] ?? '';

# 判定
if(empty($data['title']) || empty($data['content'])){
	header('Refresh:3;url=edit.php?id=' . $id);
	echo '标题和内容都不能为空！';
	exit;
}

# 自动更新
include 'sql.php';
$conn = connect('root','root','news',$error);
# 判定结果
if(!$conn) {
    header("Refresh:3;url=index.php"); 
    echo $error;
    exit;
}

# 调用自动更新实现操作
$res = auto_update($conn,$data,'news',$error,$id);

# 结果判定
if($res){
	header('Refresh:3;url=detail.php?id='.$id);
	echo '更新成功！';
	exit;
}else{
    # 是没有要更新的数据
	header('Refresh:3;url=index.php');
	echo '没有要更新的数据！';
	exit;
}