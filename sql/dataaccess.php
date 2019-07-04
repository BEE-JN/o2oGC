<?php
    /**
     * 数据库操作类
     * @version 1.1
     * @author GCS
     * @date 20190628
     */
    class dataaccess {

        var $servername; // 服务名称
        var $username; // 用户名
        var $password; // 密码
        var $db; // 数据库名
        var $conn; // 数据库连接对象

        /**
         * 构造函数，初始化对象，用于赋值
         */
        function __construct($servername, $username, $password, $db) {
            $this->servername = $servername;
            $this->username = $username;
            $this->password = $password;
            $this->db = $db;
        }
 
        /**
         * 数据库连接
         * @return $retu 返回数据库连接对象
         */
        function db_conn() {
            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->db);
            if ($conn->connect_error) {
                die("连接失败: " . $conn->connect_error);
            }
            else {
                $this->conn = $conn;
                return $conn;
            }
        }

        /**
         * 查询
         * @param str $sql sql操作语句
         * @return $result 返回mysqli_query对象
         */
        function db_select($sql) {
            $result = mysqli_query($this->conn, $sql);
            return $result;
        }

        /**
         * 更新
         * @param str $sql sql操作语句
         * @return $ret 1表示成功，0表示失败
         */
        function db_update($sql) {
            $result = mysqli_query($this->conn, $sql);
            if(!$result) { // 更新失败
                return 0;
            } else { // 更新成功
                return 1;
            }
        }

        /**
         * 插入
         * @param $sql sql操作语句
         * @return $ret 1表示插入成功，0表示失败
         */
        function db_insert($sql) {
            $result = mysqli_query($this->conn, $sql);
            if (!$result) {
                // 更新失败
                return 0;
            } else {
                // 更新成功
                return 1;
            }
        }

        /**
         * 删除
         * @param $sql
         * @return $ret 1表示删除成功，0表示删除失败
         */
        function db_delete($sql) {
            $result = mysqli_query($this->conn, $sql);
            if (!$result) {
                // 删除失败
                return 0;
            } else {
                // 更新成功
                return 1;
            }
        }

        /**
         * 关闭数据库连接
         */
        function db_close() {
            mysqli_close($this->conn);
        }
    }
?>