<?php
# 1、接收数据（通常外部传入，需要验证）
# 当前需求来自内部，不需要外部数据

# 2、连接认证数据库并处理可能出现的错误信息
$conn = @mysqli_connect('localhost','root','root') or die(iconv('gbk','utf-8',mysqli_connect_error()));

# 3、设置字符集
mysqli_set_charset($conn,'utf8') or die(mysqli_error($conn));

# 4、选择数据库
mysqli_select_db($conn,'db_2') or dir(mysqli_error($conn));

# 5、组织SQL指令
$sql = "update t_40 set age = age + 1 where id = 100";

# 6、执行SQL写入数据库
$res = mysqli_query($conn,$sql);
if($res === false) die(mysqli_error($conn));

# 7、返回结果
$rows = mysqli_affected_rows($conn);
# 针对受影响的行数来解决判定是否更新成功
if($rows) echo $rows;
else echo '没有要更新的数据！';

# 8、关闭连接
mysqli_close($conn);