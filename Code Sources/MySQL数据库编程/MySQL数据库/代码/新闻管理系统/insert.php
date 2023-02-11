<?php

# 新增入库
header('Content-type:text/html;charset=utf-8');

# 接收数据
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$a_id = $_POST['author'] ?? 0;

# 安全判定
if(empty($title) || empty($content)){
    # 错误跳转重来
    header('refresh:3;url=add.php');
    echo '新闻标题和内容都不能为空！';
    exit;
}

# 连接认证
$conn = @mysqli_connect('localhost','root','root','news','3306') or die(mysqli_connect_error());

# 设置字符集
mysqli_set_charset($conn,'utf8') or die(mysqli_error($conn));

# 数据入库：时间戳可以使用mysql自动生成，也可以使用PHP生成好放进去（建议生成好）
$publish = time();
$sql = "insert into news values(null,'{$title}','{$content}',{$a_id},{$publish})";
$res = mysqli_query($conn,$sql) or die(mysqli_error($conn));

# 判定数据
if($res){
    header('refresh:2;url=index.php');
    echo '新闻：' . $title . ' 新增成功！';
}else{
    header('refresh:3;url=add.php');
    echo '新闻：' . $title . '新增失败！';
}