<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

$session_id = $_GET['session_id'];
$sql_find_id = "SELECT id FROM wx_info WHERE session_id='$session_id'";
$res = $mysql->db_select($sql_find_id);
$row_id = mysqli_fetch_assoc($res);
$user_id = $row_id['id'];

$now = time();
$sql_find_finished = "SELECT * FROM order_info WHERE user=$user_id AND $now-s_time<30*24*60*60 AND (state=2 OR state=3)";
$res = $mysql->db_select($sql_find_finished);
$all_data = mysqli_fetch_all($res, MYSQLI_NUM);
if ($all_data) { // 如果取出了数据
    $errcode = 100;
    $errmsg = '查询订单成功';
    $data = $all_data;
} else {
    $errcode = 200;
    $errmsg = '无已完成订单';
    $data = null;
}
$echo_data = array('errcode' => $errcode,
'errmsg' => $errmsg,
'data' => $data);

$mysql->db_close();

echo json_encode($echo_data);