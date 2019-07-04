<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

$id = $_GET['id'];
$score = $_GET['score'];

$sql_orderscore = "UPDATE order_info SET score=$score WHERE id=$id";
$res = $mysql->db_update($sql_orderscore);
if ($res == 1) {
    $sql_find_user = "SELECT user FROM order_info WHERE id=$id";
    $res = $mysql->db_select($sql_find_user);
    $row_id = mysqli_fetch_assoc($res);
    $user_id = $row_id['user'];
    $sql_update_userscore = "UPDATE user_info SET score=score+$score WHERE id=$user_id";
    $res = $mysql->db_update($sql_update_userscore);
    if (res == 1) {
        $errcode = 100;
        $errmsg = "更新积分成功";
    } else {
        $errcode = 200;
        $errmsg = "网络错误，无此订单信息！";
    }
} else {
    $errcode = 200;
    $errmsg = "网络错误，无此订单信息！";
}

$echo_data = array('errcode' => $errcode,
'errcode' => $errmsg);