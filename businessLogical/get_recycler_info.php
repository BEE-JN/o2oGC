<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

$session_id = $_GET['session_id'];

$sql_find_id = "SELECT id FROM wx_info WHERE session_id='$session_id'";
$res = $mysql->db_select($sql_find_id);
if (mysqli_num_rows($res) == 0) {
    $echo_info = array('errcode' => '200',
    'errmsg' => 'session_id验证失败，请清空缓存后重试');
    echo json_encode($echo_info);
    die("session_id验证失败，请清空缓存后重试");
} else {
    $row_id = mysqli_fetch_assoc($res);
    $id = $row_id['id'];
    $sql_get_info = "SELECT * FROM recycler_info WHERE id='$id'";
    $res = $mysql->db_select($sql_get_info);
    $row_info = mysqli_fetch_assoc($res);
    $name = $row_info['name'];
    $phone = $row_info['phone'];
    $ID_number = $row_info['ID_number'];
    $evaluate = $row_info['evaluate'];
}

$echo_info = array('errcode' => '100',
'name' => $name,
'phone' => $phone,
'ID_number' => $ID_number,
'evaluate' => $evaluate);
echo json_encode($echo_info);