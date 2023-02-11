<?php

echo '<pre>';

// var_dump($_GET,$_POST,$_REQUEST);
# 取出数据加工（外部数据永远不安全）
$username = $_POST['title'];
$password = $_POST['author'];

echo $username,$password;