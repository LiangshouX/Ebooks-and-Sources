<?php

# mysqli查询
# 1、接收数据：查询条件，当前没有条件

# 2、连接认证数据库并处理可能出现的错误信息
$conn = @mysqli_connect('localhost','root','root') or die(iconv('gbk','utf-8',mysqli_connect_error()));

# 3、设置字符集
mysqli_set_charset($conn,'utf8') or die(mysqli_error($conn));

# 4、选择数据库
mysqli_select_db($conn,'db_2') or dir(mysqli_error($conn));

# 5、组织SQL指令
$sql = "select * from t_40";

# 6、执行SQL查出所有数据
$res = mysqli_query($conn,$sql);
if($res === false) die(mysqli_error($conn));

# 7、解析结果
# $one = mysqli_fetch_assoc($res);	# 取出一条记录

                                                          
# 取出全部：循环
$lists = [];
while($one = mysqli_fetch_assoc($res)){
    $lists[] = $one;
}
// var_dump($lists);exit;

# 8、释放结果集资源
mysqli_free_result($res);
                                                          
# 9、关闭连接
mysqli_close($conn);
                                                          
# 10、输出表格
echo '<table>';
echo '<tr><td>id</td><td>姓名</td><td>性别</td><td>年龄</td><td>班级</td></tr>';
foreach($lists as $one){
    echo "<tr><td>{$one['id']}</td><td>{$one['name']}</td><td>{$one['gender']}</td><td>{$one['age']}</td><td>{$one['class_name']}</td></tr>";
}
echo '</table>';