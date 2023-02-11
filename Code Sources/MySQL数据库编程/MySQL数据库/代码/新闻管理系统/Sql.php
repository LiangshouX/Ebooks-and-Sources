<?php

# 封装各类操作函数：初始化，自动更新、自动删除、自动查询、普通查询

# 初始化功能：连接认证、选择数据库、设定字符集
# 成功返回连接对象，失败返回false，错误记录在错误参数中
function connect($user,$pass,$dbname,&$error,$host = 'localhost',$port = '3306',$charset = 'utf8'){
    # 连接认证
    $conn = @mysqli_connect($host,$user,$pass,$dbname,$port);
    
    # 验证错误
    if(!$conn){
        $error = iconv('gbk','utf-8',mysqli_connect_error());
        return false;
    }
    
    # 设置字符集
    if(!mysqli_set_charset($conn,$charset)){
        $error = mysqli_error($conn);
        return false;
    }
    
    # 返回对象
    return $conn;
}


# 外部传入SQL，负责执行也验证SQL语法问题，成功返回结果，失败返回false，错误记录在错误参数中
function query($conn,$sql,&$error){
    # 执行SQL
    $res = mysqli_query($conn,$sql);
    
    # 判定
    if($res === false){
        $error = mysqli_error($conn);
        return false;
    }
    
    # 返回执行的正确结果
    return $res;
}

# 用户提供SQL指令，可以查询一条或者多条记录
function read($conn,$sql,&$error,$all = false){
    # 执行SQL，并判定结果
    $res = query($conn,$sql,$error);
    if($res === false) return false;
    
    # 解析结果集
    $lists = [];
    if($all){
        # 多条数据：二维数组
        while($row = mysqli_fetch_assoc($res)){
            $lists[] = $row;
        }
    }else{
        # 一条数据：一维数组
        $lists = mysqli_fetch_assoc($res);
    }
    
    # 释放资源，返回结果
    mysqli_free_result($res);
    return $lists;;
}


# 用户提供要更新的数据和主键id，自动组装SQL
# 成功返回受影响的行数，失败返回false（0表示没有更新）
function auto_update($conn,$data,$table,&$error,$id = 0){
    # 组织更新部分数据：字段名 = 值
    $set = '';
    foreach($data as $k => $v){
        $set .= $k . "='{$v}',";
    } # title = 'title',content = 'content',
    
  
    # 清除多余的右侧逗号
    $set = rtrim($set,',');	# $set : title = 'title',content = 'content'
    
    # 组织更新指令
    $sql = "update {$table} set {$set} ";
    
    # 组装where条件（id不为0才组织）：要求主键字段名字必须为ID
    if($id) $sql .= ' where id = ' . $id;	
    # update 表名 set title = 'title',content = 'content' where id = 1
    
    # 执行
    if(query($conn,$sql,$error))
        return mysqli_affected_rows($conn);
    else return false;
}


# 系统提供查询条件（只能是=比较和and逻辑运算），可以查询一条记录或者多条记录
# 成功返回数组（多条二维数组，一条一维数组）失败返回false，错误记录在参数中
function auto_read($conn,$table,&$error,$where = [],$all = false){
    # 组装查询条件：默认永远为真
    $where_clause = ' where 1 ';	# where 1
    if($where){		# 空数组自动转换成布尔false
        # 解析条件
        foreach($where as $k => $v){
            $where_clause .= ' and ' . $k . " = '$v' ";
        }  # where 1 and title = 'news' ...      
    }
    
    # 组织完整SQL
    $sql = "select * from {$table} {$where_clause}";
    $res = query($conn,$sql,$error);
    
    # 判定执行结果
    if($res === false) return $res;
    
    # 判定获取一条还是多条
    $lists = [];
    if($all){
        # 获取多条，二维数组存储
        while($row = mysqli_fetch_assoc($res)){
            $lists[] = $row;
        }
    }else{
        # 获取一条，一维数组存储
        $lists = mysqli_fetch_assoc($res);
    }
    
    # 释放资源，返回结果
    mysqli_free_result($res);
    return $lists;
}
