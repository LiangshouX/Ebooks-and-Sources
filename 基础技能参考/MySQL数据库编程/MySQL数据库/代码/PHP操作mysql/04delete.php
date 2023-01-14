<?php

# mysqli删除数据
# 2、连接认证数据库并处理可能出现的错误信息
$conn = @mysqli_connect('localhost','root','root') or die(iconv('gbk','utf-8',mysqli_connect_error()));

# 3、设置字符集
mysqli_set_charset($conn,'utf8') or die(mysqli_error($conn));

# 4、选择数据库
mysqli_select_db($conn,'db_2') or dir(mysqli_error($conn));

# 5、组织SQL指令
$sql = "delete from t_45 where c_id is null";

# 6、执行SQL写入数据库
$res = mysqli_query($conn,$sql) or die(mysqli_error($conn));

# 7、返回结果
echo mysqli_affected_rows($conn);;

# 8、关闭连接
mysqli_close($conn);