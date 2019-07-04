<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

$id = $_GET['id'];
$session_id = $_GET['session_id'];
$now = time();
$sql_cancel = "UPDATE order_info SET state=3, e_time='$now' WHERE id=$id";
$res = $mysql->db_update($sql_cancel);
if ($res == 1) {
    $errcode = 100;
    $errmsg = "取消订单成功";
} else {
    $errcode = 200;
    $errmsg = "取消订单失败";
}

$mysql->db_close();

$echo_data = array('errcode' => $errcode,
'errmsg' => $errmsg);
echo json_encode($echo_data);