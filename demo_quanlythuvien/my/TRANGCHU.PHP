<?php
session_start();
// var_dump($_SESSION);
// die();

// 1. Kết nối database bằng MySQLi để đồng bộ hệ thống
require_once __DIR__ . '/../../connect/connect.php';

// 2. Kiểm tra nếu chưa đăng nhập thì bắt quay lại trang login
if (!isset($_SESSION['id'])) {
    header("Location: LOGIN.HTML");
    exit();
}

// 3. Lấy dữ liệu từ session để truyền vào file giao diện (view)
// Dữ liệu này đã được lưu từ file login_process.php
$userName = $_SESSION['username'];
$userRole = $_SESSION['role']; // 'admin' hoặc 'sinh_vien'

// 5. Nạp giao diện hiển thị
include 'trangchu_view.php';
