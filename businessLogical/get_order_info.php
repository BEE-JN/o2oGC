<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

// 通过订单号查询废品信息
$id = $_GET['id'];
$sql_find_waste = "SELECT * FROM waste_info WHERE belong=$id";
$res = $mysql->db_select($sql_find_waste);
$row_waste = mysqli_fetch_assoc($res);
if ($row_waste) {
    $errcode = 100;
    $errmsg = '查询订单成功';
    $kind = $row_waste['kind'];
    $weight = $row_waste['weight'];
    $other_info = $row_waste['other_info'];
} else {
    $errcode = 200;
    $errmsg = '查询失败';
    $kind = null;
    $weight = null;
    $other_info = null;
}

$sql_user_id = "SELECT user FROM order_info WHERE id=$id";
$res = $mysql->db_select($sql_user_id);
$row_userid = mysqli_fetch_assoc($res);
$user_id = $row_userid['user'];
$sql_user_info = "SELECT name, sex FROM user_info WHERE id=$user_id";
$res = $mysql->db_select($sql_user_info);
$row_userinfo = mysqli_fetch_assoc($res);
$user_name = $row_userinfo['name'];
$user_sex = $row_userinfo['sex'];
if ($user_sex == 1) { // 用户为男
    $sex = '先生';
} elseif ($user_sex == 2) { // 用户为女
    $sex = '女士';
} else { // 默认为0
    $sex = '保密';
}

$echo_data = array('errcode' => $errcode,
'errmsg' => $errmsg,
'name' => $user_name,
'sex' => $sex,
'kind' => $kind,
'weight' => $weight,
'other_info' => $other_info
);

$mysql->db_close();

echo json_encode($echo_data);