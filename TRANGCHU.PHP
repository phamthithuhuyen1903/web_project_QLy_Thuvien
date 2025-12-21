<?php
session_start();

// Kiểm tra nếu chưa đăng nhập thì bắt quay lại trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: LOGIN.HTML");
    exit();
}

// Lấy dữ liệu từ session để truyền vào file view
$userName = $_SESSION['name'];
$userRole = $_SESSION['role'];

// Nạp giao diện
include 'trangchu_view.php';
?>