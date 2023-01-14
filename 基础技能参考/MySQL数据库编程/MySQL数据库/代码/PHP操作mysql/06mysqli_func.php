<?php

# 基于MySQLi的二次封装


# 初始化操作：成功返回连接，失败返回false
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
            $lists[] = $row;
        }
        return $lists;
    }else{
        # 获取一条结果
        return mysqli_fetch_assoc($res);
    }  
}



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

