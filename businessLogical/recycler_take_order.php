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
$recycler_id = $row_id['id'];

// 更新接单订单状态，绑定接单回收员
$id = $_GET['id'];
$sql_takeorder = "UPDATE order_info SET state=1, recycler=$recycler_id WHERE id=$id";
$res = $mysql->db_update($sql_takeorder);
if ($res == 1) { // 更新订单信息成功
    $errcode = 100;
    $errmsg = "接单成功";
} else {
    $errcode = 200;
    $errmsg = "接单失败，请刷新页面以确定订单状态";
}

$mysql->db_close();

$echo_data = array('errcode' => $errcode,
'errmsg' => $errmsg);
echo json_encode($echo_data);
