<?php


# 显示新闻新增表单

# 数据库初始化操作
# 连接认证
$conn = @mysqli_connect('localhost','root','root','news','3306') or die(mysqli_connect_error());

# 设置字符集
mysqli_set_charset($conn,'utf8') or die(mysqli_error($conn));

# 获取所有数据
$res = mysqli_query($conn,'select id,name from author') or die(mysqli_error($conn));

# 取出数据
$authors = [];
while($row = mysqli_fetch_assoc($res)){
    $authors[] = $row;
}











# 加载模板文件add.html
include 'add.html';