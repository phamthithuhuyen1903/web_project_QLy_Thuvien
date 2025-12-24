<?php
// db_connect.php dùng cho MySQL (phpMyAdmin) bằng MySQLi
$host = "localhost";
$dbname = "demo_quanlythuvien"; // Tên database
$username = "root";    // Mặc định của XAMPP
$password = "";        // Mặc định của XAMPP để trống

// 1. Tạo kết nối
$conn = mysqli_connect($host, $username, $password, $dbname);

// 2. Kiểm tra kết nối
if (!$conn) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}

// 3. Thiết lập bảng mã utf8 để không bị lỗi font tiếng Việt
mysqli_set_charset($conn, "utf8");
?>