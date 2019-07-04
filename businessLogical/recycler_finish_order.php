<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

// 更新已完成订单状态，积分+=20，写入订单完成时间
$id = $_GET['id'];
$e_time = time();
$sql_checktime = "SELECT e_time FROM order_info WHERE id=$id";
$res = $mysql->db_select($sql_checktime);
$row_time = mysqli_fetch_assoc($res);
if ($row_time['e_time'] != null) { // 如果有完成时间说明订单已接受
    $errcode = 300;
    $errmsg = "订单已经完成！请刷新页面查看";
} else {
    $sql_finishorder = "UPDATE order_info SET state=2, e_time=$e_time, score=score+20 WHERE id=$id";
    $res = $mysql->db_update($sql_finishorder);
    if ($res == 1) { // 更新订单信息成功
        $errcode = 100;
        $errmsg = "订单已完成";
    } else {
        $errcode = 200;
        $errmsg = "网络错误请重新确定订单";
}
}

$mysql->db_close();

$echo_data = array('errcode' => $errcode,
'errmsg' => $errmsg);
echo json_encode($echo_data);