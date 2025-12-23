<?php
// db_connect.php dùng cho MySQL (phpMyAdmin)
$host = "localhost";
$dbname = "demo_quanlythuvien"; // Tên database viết thường như trong phpMyAdmin
$username = "root";    // Mặc định của XAMPP
$password = "";        // Mặc định của XAMPP để trống

try {
    // Chuyển sang driver MySQL (mysql:host)
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối database: " . $e->getMessage());
}
?>