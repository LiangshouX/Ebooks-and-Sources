# 一、PHP操作MySQL数据库

> 学习目标：了解PHP操作MySQL数据库的原理，掌握PHP扩展的应用以及MySQLi扩展实现数据库的增删改查操作

* PHP扩展
* PHP加载MySQLi
* MySQLi实现数据库操作



## 1、PHP扩展

> 目标：了解扩展对于PHP的作用，以及PHP如何实现扩展加载使用



> 概念

**PHP扩展**：PHP中提供了一些PHP本身做不到，但是通过更底层的实现可以帮助PHP解决需求的外部支持

* PHP的外部支持在PHP安装目录下的ext文件夹下（extension扩展）
* PHP的外部支持通常以dll（Windows下，动态链接库）结尾
* PHP需要应用扩展就需要在配置文件中（php.ini）加载对应的动态链接库
  * 扩展路径：extension_dir
  * 扩展名称：extension



> 步骤

1、确定需要使用的外部扩展的名字

2、确定外部扩展所在的路径（通常是统一放到ext目录下）

3、修改配置文件

* 扩展路径（一次性修改）
* 扩展名称（按需加载）



> 示例

在PHP中开启MySQLi数据库扩展，允许PHP实现数据库操作

```ini
;配置路径
extension_dir = "D:/server/php7/ext"

;开启mysqli扩展，将mysqli前面的注释去掉
extension=php_mysqli
```

* PHP的配置文件已经加载到Apache，要生效，需要重启Apache



> 小结

1、扩展是PHP用来实现一些复杂功能时所要用到的其他技术体系

* 扩展在必要时才加载
* 扩展加载分为两个部分
  * 扩展路径
  * 扩展名字

2、PHP配置文件的修改要及时的重启服务器才会生效



## 2、MySQLi扩展

> 目标：了解MySQLi扩展的作用，掌握MySQLi扩展实现数据库的连接认证



> 概念

**MySQLi扩展**：PHP提供的一套包含面向过程和面向对象两种方式实现的MySQL数据库操作

* PHP加载了MySQLi扩展后，PHP就可以充当MySQL的客户端
  * 连接认证服务器
  * 发送SQL指令
  * 接收SQL执行结果
  * 解析结果



> 步骤

1、测试PHP加载MySQLi是否成功（PHPinfo()函数）

2、使用MySQLi提供的连接认证函数连接认证（mysqli_connect()函数）

3、检查连接信息（mysqli_connect_error()函数）

4、执行SQL操作

* 新增操作
* 删除操作
* 查询操作
* 修改操作

5、关闭连接（mysqli_close()函数）



> 示例

PHP操作MySQLi实现数据库的连接认证和关闭

```php
phpinfo();	# 查看PHP是否加载mysqli成功（测试使用，非正式代码）

# 1、连接认证
$conn = @mysqli_connect('localhost:3306','root','root');

# 2、错误检查
if(!$conn) die(mysqli_connect_error());
# iconv函数可以实现字符集转换

# 3、其他操作

# 4、关闭连接
mysqli_close($conn);
```



> 小结

1、mysqli提供的扩展里有很多函数可以帮助我们解决相应问题

2、PHP充当了mysql的客户端来操作服务器

* 连接认证：错误检查
* 数据操作：错误检查
* 关闭连接



## 3、MySQLi常用函数

> 目标：对一些数据库操作所用到的常见函数有一些了解



* mysqli_connect：连接认证，正确返回连接对象，失败返回false
* mysqli_connect_error：连接时错误获取错误信息
* mysqli_select_db：选择数据库，失败返回false，成功返回true
* mysqli_set_charset：设置客户端字符集
* mysqli_query：执行SQL指令，第一个参数为连接对象，第二个参数为SQL指令
  * 失败返回false
  * 成功
    * 写操作返回true
    * 读操作返回一个结果集对象
* mysqli_insert_id：上一步新增操作产生的自增长id
* mysqli_affected_rows：上一个操作（写）受影响的行数
* mysqli_num_rows：当前结果集对象（读）中记录数
* mysqli_fetch_assoc：从当前结果集中查询出一条记录，返回一个关联数组
  * 结果集指针与数组指针一样移动
* mysqli_fetch_row：从当前结果集中查询出一条记录，返回一个索引数组
* mysqli_fetch_all：从结果集中取出所有记录，返回二维数组
* mysqli_errno：上述所有操作（读写）出现错误的编号
* mysqli_error：上述所有操作出现错误的信息
* mysqli_free_result：释放当前查询得到的结果集
* mysqli_close：断开当前连接



## 4、新增数据

> 目标：掌握Mysqli扩展实现数据的新增入库



> 概念

**新增数据**：利用mysqli扩展将数据写入到数据库



> 步骤

1、收集用户数据和新增需求

2、连接认证数据库

3、设置字符集

4、选择数据库

5、组织新增SQL指令，确认是否需要获取自增长id

6、发送给数据库（mysqli_query函数）

7、判定执行指令的执行情况

* mysqli_errno：错误编号
* mysqli_error：错误信息

8、获取自增长id（确定需要获取），否则返回受影响的行数

9、新增完成，关闭连接



> 示例

新增一个学生信息入库到db_2表中的t_40表中

```php
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
$auto_id = true;

# 6、执行SQL写入数据库
$res = mysqli_query($conn,$sql) or die(mysqli_error($conn));
# if($res === false) die(mysqli_error($conn));

# 7、返回结果
if(isset($auto_id)) echo mysqli_insert_id($conn);	# 自增长ID
else echo mysqli_affected_rows($conn);				# 受影响的行数

# 8、关闭连接
mysqli_close($conn);

```



> 小结

1、新增数据主要是利用mysqli_query执行写操作，然后得出受影响行数，必要时进行自增长id获取（在执行mysqli_query之后）

2、无论何时，我们都需要对进行的操作进行结果验证（凡是涉及到的数据存在外部来源时）



## 5、更新数据

> 目标：掌握Mysqli扩展实现数据的更新入库



> 概念

**更新数据**：利用MySQLi扩展，将已有数据进行更新入库



> 步骤

1、收集用户更新的数据信息并验证

2、连接认证数据库

3、设置字符集

4、选择数据库

5、组织更新SQL指令

6、发送给数据库（mysqli_query函数）

7、判定执行指令的执行情况

- mysqli_errno：错误编号
- mysqli_error：错误信息

8、返回受影响的行数

9、更新完成，关闭连接



> 示例

将所有db_2库中t_40表中所有人的年龄都+1

```php
# 1、接收数据（通常外部传入，需要验证）
# 当前需求来自内部，不需要外部数据

# 2、连接认证数据库并处理可能出现的错误信息
$conn = @mysqli_connect('localhost','root','root') or die(iconv('gbk','utf-8',mysqli_connect_error()));

# 3、设置字符集
mysqli_set_charset($conn,'utf8') or die(mysqli_error($conn));

# 4、选择数据库
mysqli_select_db($conn,'db_2') or dir(mysqli_error($conn));

# 5、组织SQL指令
$sql = "update t_40 set age = age + 1";

# 6、执行SQL写入数据库
$res = mysqli_query($conn,$sql);
if($res === false) die(mysqli_error($conn));

# 7、返回结果
echo mysqli_affected_rows($conn);

# 8、关闭连接
mysqli_close($conn);
```



> 小结

1、更新操作通常是用户已有数据上的编辑后提交实现

2、更新操作较少出现全部更新，一般都是个别数据更新或者批量更新，都是需要进行where条件指定的



## 6、删除数据

> 目标：掌握Mysqli扩展实现数据的删除



> 概念

**删除数据**：利用MySQLi扩展，将已有数据从数据表删除



> 步骤

1、收集用户删除的数据信息并验证（通常有权限验证）

2、连接认证数据库

3、设置字符集

4、选择数据库

5、如果有必要，需要验证要删除的数据在数据库是否存在

6、组织删除SQL指令

7、发送给数据库（mysqli_query函数）

8、判定执行指令的执行情况

- mysqli_errno：错误编号
- mysqli_error：错误信息

9、返回受影响的行数

10、删除完成，关闭连接



> 示例

删除db_2库里t_45表中没有班级的学生

```php
# 1、接收数据（通常外部传入，需要验证）
# 当前需求来自内部，不需要外部数据

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
```



> 小结

1、删除操作与更新操作本质是一样的，都是写操作（包括新增），只是业务层面上有一些区别

* 新增：用户需要提交全部数据（数据最多，无主键，自增长）
* 更新：用户提交部分更新数据（数据较少，有主键，更新条件）
* 删除：用户一般是点击或者选择执行（只有主键）

2、很多时候，重要的业务数据一般对用户提供删除操作，但是实际并不删除：设置一个字段，用来记录是否删除（最终删除演变成更新操作）



## 7、查询数据

> 目标：理解PHP操作数据的查询逻辑，掌握MySQLi扩展实现数据的查询



> 概念

**查询数据**：利用MySQLi扩展，从数据库查询出数据，并进行浏览器显示（变成浏览器能够识别的格式）

* 数据查询逻辑
  * mysqli_query将数据查询出来，此时是一个结果集对象（PHP和浏览器都不可识别）
  * 利用结果集查询mysqli_fetch系列函数将结果集翻译出来，此时是一个数组（PHP能识别）
  * PHP将数组按照指定格式解析到HTML中（浏览器能识别）



> 步骤

1、根据需求确定要获取的数据来源

2、连接认证数据库

3、设置字符集

4、选择数据库

5、组织查询SQL指令

6、发送给数据库（mysqli_query函数）

7、判定执行指令的执行情况

- mysqli_errno：错误编号
- mysqli_error：错误信息

8、解析查询结果集

* 索引数组：mysqli_fetch_row()：不包含字段名
* 关联数组：mysqli_fetch_assoc()：包含字段名（数组下标：使用较多）

9、释放结果集资源：mysqli_free_result()

10、实现数据输出

11、查询完成，关闭连接



> 示例

获取db_2库里t_40表中所有学生信息并显示在表格里

```php
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
$one = mysqli_fetch_assoc($res);	# 取出一条记录
                                                          
# 取出全部：循环
$lists = [];
while($one = mysqli_fetch_assoc($res)){
    $lists[] = $one;
}

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
```



> 小结

1、查询操作是数据库操作最常见的操作

* 查询得到的结果不能直接被PHP接续，是一种结果集
* 结果集需要通过MySQLi提供的函数进行解析，取出里面的数据变成PHP可以理解的数据



## 8、总结



1、PHP要操作数据库是通过扩展实现的，MySQLi扩展是目前通用的一种面向过程的MySQL扩展（也支持面向对象）

2、扩展的使用本质是使用扩展里提供的函数来帮助我们解决需求问题

* 连接认证数据库：mysqli_connect
* 设置字符集：mysqli_set_charset
* 选择数据库：mysqli_select_db
* 执行SQL指令：mysqli_query
* 判定执行结果，获取错误信息：mysqli_errno/mysqli_error
* 新增获取自增长id：mysqli_insert_id
* 写操作获取受影响的行数：mysqli_affected_rows
* 解析查询结果：**mysqli_fetch_assoc**/mysqli_fetch_row
* 释放结果集资源：mysqli_free_result
* 关闭连接资源：mysqli_close

3、不管是写操作还是读操作，每一次SQL操作有一些过程是相同的，应该进行封装实现代码复用

* 数据库的连接认证以及错误处理（包括字符集和数据库选择）

```php
# 成功返回连接，失败返回false
function connect(&$error,$username,$password,$dbname,$host = 'localhost',$port = '3306',$charset = 'utf8'){
    # 连接认证
    $conn = @mysqli_connect($host,$username,$password,$dbname,$port);
    
    # 判定：连接失败，将错误信息记录下来，并返回false
    if(!$conn) {
        $error = iconv('gbk','utf-8',mysqli_connect_error());
        return false;
    }
    
    # 设置字符集
    if(!mysqli_set_charset($conn,$charset)){
        $error = mysqli_error($conn);
        return false;
    }
    
    # 返回正确结果
    return $conn;
}
```

* SQL的执行和错误检测，以及错误处理

```php
# SQL执行，返回执行结果，执行失败返回false，并记录错误信息
function execute($conn,$sql,&$error){
    $res = mysqli_query($conn,$sql);
    
    # 执行失败
    if(!$res)
    {
        $error = mysqli_error($conn);
        return false;
    }
    
    # 返回执行结果
    return $res;
}
```

* 数据的查询操作（一条记录和多条记录）

```php
# 查询：分为一条记录查询或者多条记录查询
function read($conn,$sql,&$error,$all = false){
    # 调用执行函数
    $res = execute($conn,$sql,$error);
    
    # 判定结果
    if(!$res){
        return false;
    }
    
    # 解析结果
    if($all){
        # 获取全部结果
        $lists = [];
        
        while($row = mysqli_fetch_assoc($res)){
            $lists[] = $res;
        }
        return $lists;
    }else{
        # 获取一条结果
        return mysqli_fetch_assoc($res);
    }  
}
```

* 写操作封装

```php
# 写操作：考虑是否需要获取自增长id
function write($conn,$sql,&$error,$insert = false){
    # 默认操作为删改操作（不需要自增长ID的操作）
    $res = execute($conn,$sql,$error);
    
    # 判定结果
    if(!$res){
        return false;
    }
    
    # 针对用户需求判定
    if($insert) return mysqli_insert_id($conn);
    else return mysqli_affected_rows($conn);
}
```

