<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

// 查找session_id对应的数据库id
$session_id = $_GET['session_id'];
$sql_find_id = "SELECT id FROM wx_info WHERE session_id='$session_id'";
$res = $mysql->db_select($sql_find_id);
$row_id = mysqli_fetch_assoc($res);
$user_id = $row_id['id'];

$s_time = time();
$province = $_GET['province'];
$city = $_GET['city'];
$area = $_GET['area'];
$address = $_GET['address'];
$phone = $_GET['phone'];
$kind = $_GET['kind'];
$weight = $_GET['weight'];
$other_info = $_GET['other_info'];

$sql_creat_order = "INSERT INTO order_info (`s_time`,`state`,`province`,`city`,`area`,`address`,`phone`,`user`) VALUES ('$s_time','0','$province','$city','$area','$address','$phone','$user_id')";
$res = $mysql->db_insert($sql_creat_order);

if ($res == 1) { // 插入订单数据成功
    $new_id = mysqli_insert_id($conn);
    $sql_creat_waste = "INSERT INTO waste_info (`kind`,`weight`,`other_info`,`belong`) VALUES ('$kind','$weight','$other_info','$new_id')";
    $res = $mysql->db_insert($sql_creat_waste);
    if ($res == 1) {
        $errcode = 100;
        $errmsg = "创建订单成功";
    } else {
        $errcode = 200;
        $errmsg = "创建waste_info失败";
    }
} else { // 创建订单失败
    $errcode = 200;
    $errmsg = "创建order_info失败";
}

$mysql->db_close();

$echo_data = array('errcode' => $errcode,
'errmsg' => $errmsg);
echo json_encode($echo_data);