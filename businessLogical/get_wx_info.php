<?php
// 连接数据库
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库

$session_id = $_GET['session_id'];
$nick_name = $_GET['nick_name'];
$avatar_url = $_GET['avatar_url'];

$sql_nick_name = "UPDATE wx_info SET nick_name='$nick_name', avatar_url='$avatar_url' WHERE session_id='$session_id'";
$res = $mysql->db_update($sql_nick_name);
if ($res == 0) {
    $echo_info = array('errcode' => 200,
    'errmsg' => '更新昵称失败');
} else {
    $echo_info = array('errcode' => 100,
    'errmsg' => '更新昵称成功');
}

echo json_encode($echo_info);
$mysql->db_close();