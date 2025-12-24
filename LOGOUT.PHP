<?php
session_start();
require_once 'db_connect.php'; // Kết nối MySQLi

// (Tùy chọn) Ghi log thời gian thoát nếu cần thiết tại đây bằng mysqli_query

session_unset();
session_destroy();

// Đóng kết nối MySQLi
if (isset($conn)) {
    mysqli_close($conn);
}

header("Location: LOGIN.HTML");
exit();
?>