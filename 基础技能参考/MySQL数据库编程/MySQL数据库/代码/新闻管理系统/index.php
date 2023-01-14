<?php


# 新闻列表（显示所有新闻）
# 接收页码信息
$page = $_GET['page'] ?? 1;
$pagecount = 4;

# 加载封装的数据库操作文件
include 'sql.php';

# 完成初始化操作
$conn = connect('root','root','news',$error);
# 判定结果
if(!$conn) die($error);

# 获取满足条件的数据的总记录数（在获取列表之前：因为不需要获取全部数据了）
$count_sql = 'select count(*) as total from news';
$res = read($conn,$count_sql,$error);		# 执行
$count = $res['total'] ?? 0;				# 取出记录（如果没有那就是0）
$pages = ceil($count / $pagecount);			# 总页数

# 计算页码数据：使用limit限制
$offset = ($page - 1) * $pagecount;
$limit = " limit $offset,$pagecount";

# 组织数据SQL，获取数据（手动组织：需要连表）
$sql = "select n.*,a.name from news n left join author a on n.a_id = a.id  order by n.id "  . $limit;
$news = read($conn,$sql,$error,true);

// var_dump($news);

# 产生分页链接信息
$pageinfo = '';

# 首页
$pageinfo .= "<a href='index.php?page=1'>首页</a>";

# 判定是否需要上一页
if($page != 1) {
    $prev = $page - 1;
    $pageinfo .= "<a href='index.php?page={$prev}'>上一页</a>";
}

# 拼凑数字逻辑部分：以页码总数先划分
if($pages <= 7){
    # 显示所有页码，也不需要...
    for($i = 1;$i <= $pages;$i++){
        # 判定当前页码是否被选中：增加样式
        if($page == $i) $pageinfo .= "<a class='current' href='index.php?page={$i}'>{$i}</a>";        
        else $pageinfo .= "<a href='index.php?page={$i}'>{$i}</a>";
    }
}else{
    # 当前页码在前5页：显示前7页，外加...
    if($page <= 5){
        for($i = 1;$i <= 7;$i++){
            # 判定当前页是否被选中：选中需要增加css样式
            if($page == $i) $pageinfo .= "<a class='current' href='index.php?page={$i}'>{$i}</a>";
            else $pageinfo .= "<a href='index.php?page={$i}'>{$i}</a>";
        }
        
        # 追加...
        $pageinfo .= "<a href ='javascript:return false;' onclick='return false;'>...</a>";
    }else{
        # 当前页码大于5：显示前2页和...
        $pageinfo .= "<a href='index.php?page=1'>1</a>";
    	$pageinfo .= "<a href='index.php?page=2'>2</a>";
        $pageinfo .= "<a href ='javascript:return false;' onclick='return false;'>...</a>";
        
        # 判定当前页码是否已经到达最后三页：不需要后序...
        if($page > $pages - 3){
            # 显示最后5页
            for($i = $pages - 4;$i <= $pages;$i++){
                # 判定当前页是否被选中：选中需要增加css样式
            	if($page == $i) $pageinfo .= "<a class='current' href='index.php?page={$i}'>{$i}</a>";
            	else $pageinfo .= "<a href='index.php?page={$i}'>{$i}</a>";
            }
        }else{
            # 显示中间5页，并追加...
            for($i = $page - 2;$i <= $page + 2;$i++){
                # 判定当前页是否被选中：选中需要增加css样式
            	if($page == $i) $pageinfo .= "<a class='current' href='index.php?page={$i}'>{$i}</a>";
            	else $pageinfo .= "<a href='index.php?page={$i}'>{$i}</a>";
            }
            # 追加...
            $pageinfo .= "<a href ='javascript:return false;' onclick='return false;'>...</a>";
        }    
    }
}

# 判定是否需要增加下一页
if($page != $pages){
    $next = $page + 1;
    $pageinfo .= "<a href='index.php?page={$next}'>下一页</a>";
} 

# 末页
$pageinfo .= "<a href='index.php?page={$pages}'>末页</a>";

# 加载模板
include 'list.html';