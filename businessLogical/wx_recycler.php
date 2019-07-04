<?php

/**
 * 引入数据库操作类，连接数据库
 */
include '../sql/dataaccess.php';
$mysql = new dataaccess('localhost', 'o2oGC', 'H5fHKXH4xG', 'o2oGC'); // 实例化对象
$conn = $mysql->db_conn(); // 连接数据库



/**
 * 服务器和微信官方服务器通讯，获取用户session_key，openid
 */

// 获取小程序传过来的code
$code = $_GET['code'];

// 拼接url
$host = "https://api.weixin.qq.com";
$path = "/sns/jscode2session";
$method = "GET";
$appId = "wx5a1000996dce91c7"; // 回收员端小程序id
$secret = "acce5bd89a69613ad33d8a2f9acddeac"; //小程序秘钥，记不住就得重置系列
$querys = "appid=$appId&secret=$secret&js_code=$code&grant_type=authorization_code";
$url = $host . $path . "?" . $querys;

// 发起请求
$curl = curl_init();
curl_setopt($curl, CURLOPT_HEADER, false); //不返回 http 头
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_FAILONERROR, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
if (1 == strpos("$" . $host, "https://")) //strpos是查找第一个字符串在第二个字符串中出现的位置，不知道干嘛用的先抄过来
{
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
}

// 收到的数据
$json = curl_exec($curl);
$recv_data = json_decode($json, true);
if ($recv_data['errcode'] == 0) {
    $openid = $recv_data['openid']; //获取openid（不会改变）
    $session_key = $recv_data['session_key']; //获取session_key,用于数据的加密，懒得加密了（md就是不会）
} else {
    die("请求微信服务器失败：" . $recv_data['errmsg']);
}

/**
 * 检查回收员，如果不存在则创建，如果存在则检查session_id
 */
$sql_find = "SELECT * FROM `wx_info` WHERE `openid` LIKE '$openid'";
$res = $mysql->db_select($sql_find);

// 如果查询结果影响了0行，代表无回收员
if (mysqli_num_rows($res) == 0) {
    // 创建用户
    // wx_info创建回收员
    // recycler_info的id跟随刚才创建的wx_info中的id
    session_start();
    $session_id = session_id(); //创建对话的sessionId，此id为客户端和开发者服务器通信id
    $sql_creatwx = "INSERT INTO `wx_info` (`openid`,`session_key`,`session_id`) VALUES ('$openid','$session_key','$session_id')";
    $res = $mysql->db_insert($sql_creatwx);
    if ($res == 1) { // 插入新用户成功
        // 获取最新AI的id
        $new_id = mysqli_insert_id($conn);
        $sql_creatuser = "INSERT INTO `recycler_info` (`id`,`evaluate`) VALUES ('$new_id','80')";
        $res = $mysql->db_insert($sql_creatuser);
        if ($res != 1) { // recycler_info 创建失败
            $errcode = 200;
            $errmsg = "recycler_info用户创建失败";
            $echo_data = array('errcode'=>$errcode,
            'errmsg'=>$errmsg);
            echo json_encode($echo_data);
            die("recycler_info用户创建失败");
        }
    } else { // wx_info创建user失败
        $errcode = 200;
        $errmsg = "wx_info创建用户失败";
        $echo_data = array('errcode'=>$errcode,
        'errmsg'=>$errmsg);
        echo json_encode($echo_data);
        die("wx_info创建用户失败");
    }
    
    // 若果创建回收员成功，返回session_id，errcode=50
    $errcode = 50;
    $errmsg = "创建recycler成功";
    $echo_data = array('errcode'=>$errcode,
    'errmsg'=>$errmsg,
    'session_id'=>$session_id);
    echo json_encode($echo_data); 

} else { // 否则用户已经存在
    // 更新session_key
    $sql_session_key = "UPDATE `wx_info` SET `session_key` = '$session_key' WHERE `openid` = '$openid'";
    $res = $mysql->db_update($sql_session_key);
    if ($res != 1) { // 如果更新session_key不成功
        $errcode = 200;
        $errmsg = "更新session_key失败";
        $echo_data = array('errcode'=>$errcode,
        'errmsg'=>$errmsg);
        echo json_encode($echo_data);
        die("更新session_key失败");
    }

    $session_id = $_GET['session_id'];  
    // 判断用户session_id是否过期，默认本地储存一天，如果过期，则小程序返回null，否则直接返回session_id
    if ($session_id == "null") { // 如果回收员本地储存的session_id过期
        session_start();
        $session_id = session_id(); //创建对话的sessionId，此id为客户端和开发者服务器通信id
        //将sessionId写入数据库
        $sql_session_id = "UPDATE `wx_info` SET `session_id` = '$session_id' WHERE `openid` = '$openid'";
        $res = $mysql->db_update($sql_session_id);
        if ($res != 1) { // 如果更新session_id不成功
            $errcode = 200;
            $errmsg = "更新session_id失败";
            $echo_data = array('errcode'=>$errcode,
            'errmsg'=>$errmsg);
            echo json_encode($echo_data);
            die("更新session_id失败");
        }
    }

    $errcode = 150;
    $errmsg = "更新session成功";
    $echo_data = array('errcode'=>$errcode,
    'errmsg'=>$errmsg,
    'session_id'=>$session_id);
    echo json_encode($echo_data);

    // 关闭数据库链接
    $mysql->db_close();
}
