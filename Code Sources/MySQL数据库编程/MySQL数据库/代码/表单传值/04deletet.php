<?php

// $id = $_GET['id'];	# 不考虑安全的接收
# 安全接收方式
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

echo $id;