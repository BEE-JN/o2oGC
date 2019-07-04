<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

// 获取前台传来的session_id作为会话身份认证
$session_id = $_GET['session_id'];

// 获取前台传来的个人信息
$name = $_GET['name'];
$phone = $_GET['phone'];
$province = $_GET['province'];
$city = $_GET['city'];
$area = $_GET['area'];
$address = $_GET['address'];

// 查找session_id对应的数据库id
$sql_find_id = "SELECT id FROM wx_info WHERE session_id='$session_id'";
$res = $mysql->db_select($sql_find_id);
$row_id = mysqli_fetch_assoc($res);
$id = $row_id['id'];

$sql_write_info = "UPDATE user_info SET name='$name', phone='$phone', province='$province', city='$city', area='$area', address='$address' WHERE id='$id'";
$res = $mysql->db_update($sql_write_info);
if ($res == 0) { // 更新用户信息失败
    $errcode = 200;
    $errmsg = "注册用户个人信息失败";
    $echo_data = array('errcode'=>$errcode,
    'errmsg'=>$errmsg);
    echo json_encode($echo_data);
    die("注册用户个人信息失败");
} else {
    $errcode = 100;
    $errmsg = "用户信息注册成功";
    $echo_data = array('errcode'=>$errcode,
    'errmsg'=>$errmsg);
    echo json_encode($echo_data);
}

$mysql->db_close();