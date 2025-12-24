<?php
session_start();

// 1. Kết nối database bằng MySQLi để đồng bộ hệ thống
require_once 'db_connect.php'; 

// 2. Kiểm tra nếu chưa đăng nhập thì bắt quay lại trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: LOGIN.HTML");
    exit();
}

// 3. Lấy dữ liệu từ session để truyền vào file giao diện (view)
// Dữ liệu này đã được lưu từ file login_process.php
$userName = $_SESSION['name']; 
$userRole = $_SESSION['role']; // 'admin' hoặc 'sinh_vien'


// 5. Nạp giao diện hiển thị
include 'trangchu_view.php';
?>