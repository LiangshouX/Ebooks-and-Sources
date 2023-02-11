<?php

# 1、接收数据（通常外部传入，需要验证）
$name = '自来也';
$gender = '男';
$age = 30;
$class_name = '木叶0班';

# 2、连接认证数据库并处理可能出现的错误信息
$conn = @mysqli_connect('localhost','root','root') or die(iconv('gbk','utf-8',mysqli_connect_error()));

# 3、设置字符集
mysqli_set_charset($conn,'utf8') or die(mysqli_error($conn));

# 4、选择数据库
mysqli_select_db($conn,'db_2') or die(mysqli_error($conn));

# 5、组织SQL指令
$sql = "insert into t_40 values(null,'$name','$gender',$age,'$class_name')";
# 确定是否返回自增长id
// $auto_id = true;

# 6、执行SQL写入数据库
$res = mysqli_query($conn,$sql) or die(mysqli_error($conn));
# if($res === false) die(mysqli_error($conn));

# 7、返回结果
if(isset($auto_id)) echo mysqli_insert_id($conn);	# 自增长ID
else echo mysqli_affected_rows($conn);				# 受影响的行数

# 8、关闭连接
mysqli_close($conn);