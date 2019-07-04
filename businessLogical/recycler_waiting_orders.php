<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

$session_id = $_GET['session_id'];
$city = $_GET['city'];
$area = $_GET['area'];
$now = time();

// 查看所有未接单订单，订单为期两天
$sql_waitingorder = "SELECT * FROM order_info WHERE city='$city' AND area='$area' AND state=0 AND $now-s_time<2*24*60*60";
$res = $mysql->db_select($sql_waitingorder);
$all_data = mysqli_fetch_all($res, MYSQLI_NUM);
if ($all_data) { // 如果取出了数据
    $errcode = 100;
    $errmsg = '查询订单成功';
    $data = $all_data;
} else {
    $errcode = 200;
    $errmsg = '订单池为空';
    $data = null;
}
$echo_data = array('errcode' => $errcode,
'errmsg' => $errmsg,
'data' => $data);

$mysql->db_close();

echo json_encode($echo_data);