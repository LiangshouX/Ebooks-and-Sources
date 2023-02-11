<?php

# 编辑新闻：显示要编辑的新闻

# 接收要获取的新闻id
$id = $_GET['id'] ?? 0;
if(!$id) {
    header("Refresh:3;url={$_SERVER['HTTP_REFERER']}");
    echo '当前要编辑的新闻不存在！';
    exit;
}

# 获取新闻数据
include 'sql.php';

$conn = connect('root','root','news',$error);
# 判定结果
if(!$conn) {
    header("Refresh:3;url=index.php"); 
    echo $error;
    exit;
}

# 自动查询
$news = auto_read($conn,'news',$error,['id' => $id]);
if(!$news){
    header("Refresh:3;url=index.php");
    echo '当前要编辑的新闻不存在！';
    exit;
}

# 加载模板
include 'edit.html';