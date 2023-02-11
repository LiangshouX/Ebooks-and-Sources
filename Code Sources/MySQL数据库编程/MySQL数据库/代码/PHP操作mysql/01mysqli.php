<?php

# mysqli扩展使用
# 扩展加载检查
# phpinfo();
header('Content-type:text/html;charset=utf-8');

# 1、连接认证
$conn = @mysqli_connect('localhost:3306','root','root');

# 2、错误检查：mysqli_connect_error报的错误返回的字符集是GBK
if(!$conn) die(iconv('gbk','utf-8',mysqli_connect_error()));

# 3、数据库操作


# 4、关闭连接
mysqli_close($conn);