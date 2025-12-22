<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    try {
        // Kiểm tra bảng admin (tên bảng viết thường theo phpMyAdmin)
        $sqlAdmin = "SELECT * FROM admin WHERE MANV = :user AND MATKHAU = :pass";
        $stmt = $conn->prepare($sqlAdmin);
        $stmt->execute(['user' => $user, 'pass' => $pass]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            $_SESSION['user_id'] = $admin['MANV'];
            $_SESSION['name'] = $admin['HOTEN'];
            $_SESSION['role'] = 'admin';
            header("Location: TRANGCHU.PHP"); // Chuyển đến file xử lý trang chủ
            exit();
        }

        // Kiểm tra bảng sinhvien
        $sqlSV = "SELECT * FROM sinhvien WHERE MASV = :user AND MATKHAU = :pass";
        $stmt = $conn->prepare($sqlSV);
        $stmt->execute(['user' => $user, 'pass' => $pass]);
        $sv = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sv) {
            $_SESSION['user_id'] = $sv['MASV'];
            $_SESSION['name'] = "Sinh viên " . $sv['HOTEN'];
            $_SESSION['role'] = 'sinhvien';
            header("Location: TRANGCHU.PHP");
            exit();
        } else {
            header("Location: LOGIN.HTML?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
}
?>