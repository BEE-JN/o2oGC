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

$sql_find_wait = "SELECT * FROM order_info WHERE user=$user_id AND (state=0 OR state=1)";
$res = $mysql->db_select($sql_find_wait);
$row_all = mysqli_fetch_all($res, MYSQLI_NUM);
if ($row_all) {
    $errcode = 100;
    $errmsg = '查询订单成功';
    $data = $row_all;
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