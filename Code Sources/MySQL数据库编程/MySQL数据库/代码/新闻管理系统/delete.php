<?php

# 删除新闻
# 接收数据并判定数据有效性
$id = $_GET['id'] ?? 0;
if(!$id){
    header('Refresh:3;url=index.php');
    echo '当前要删除的新闻不存在！';
    exit;
}

# 实现数据库操作（初始化）
include 'sql.php';
$conn = connect('root','root','news',$error);
# 判定结果
if(!$conn) {
    header("Refresh:3;url=index.php"); 
    echo $error;
    exit;
}

# 删除操作
$sql = "delete from news where id = {$id}";
$res = query($conn,$sql,$error);

# 判定下执行结果
if(!$res){
    header("Refresh:3;url=index.php"); 
    echo $error;
    exit;
}

# 判定删除是否真实成功
if(mysqli_affected_rows($conn)){
    header("Refresh:3;url=index.php"); 
    echo '删除成功！';
    exit;
}else{
    header("Refresh:3;url=index.php"); 
    echo '删除失败！';
    exit;
}

