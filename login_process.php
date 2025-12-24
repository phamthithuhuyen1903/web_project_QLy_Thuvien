<?php
session_start();
require_once 'db_connect.php'; // Đảm bảo file này đã chuyển sang mysqli_connect

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // BƯỚC 1: Kiểm tra tài khoản trong bảng 'user'
    // Sử dụng Prepared Statement của MySQLi để chống SQL Injection
    $sqlAuth = "SELECT * FROM user WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $sqlAuth);
    mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
    mysqli_stmt_execute($stmt);
    $resultAuth = mysqli_stmt_get_result($stmt);
    $account = mysqli_fetch_assoc($resultAuth);

    if ($account) {
        // Đăng nhập thành công
        $id_link = $account['username'];
        $role = $account['role'];

        // BƯỚC 2: Kiểm tra Role để lấy thông tin chi tiết
        // Role = 0 là Admin, Role = 1 là Sinh viên
        if ($role == 0) { 
            // --- TRƯỜNG HỢP ADMIN ---
            $sqlProfile = "SELECT * FROM admin WHERE ma_admin = ?";
            $stmtProfile = mysqli_prepare($conn, $sqlProfile);
            mysqli_stmt_bind_param($stmtProfile, "s", $id_link);
            mysqli_stmt_execute($stmtProfile);
            $resultProfile = mysqli_stmt_get_result($stmtProfile);
            $profile = mysqli_fetch_assoc($resultProfile);

            if ($profile) {
                $_SESSION['user_id'] = $profile['ma_admin'];
                $_SESSION['name'] = $profile['ho_ten']; 
                $_SESSION['role'] = 'admin';
            } else {
                $_SESSION['user_id'] = $id_link;
                $_SESSION['name'] = "Admin (Chưa cập nhật tên)";
                $_SESSION['role'] = 'admin';
            }

        } else { 
            // --- TRƯỜNG HỢP SINH VIÊN ---
            $sqlProfile = "SELECT * FROM sinh_vien WHERE ma_sv = ?";
            $stmtProfile = mysqli_prepare($conn, $sqlProfile);
            mysqli_stmt_bind_param($stmtProfile, "s", $id_link);
            mysqli_stmt_execute($stmtProfile);
            $resultProfile = mysqli_stmt_get_result($stmtProfile);
            $profile = mysqli_fetch_assoc($resultProfile);

            if ($profile) {
                $_SESSION['user_id'] = $profile['ma_sv'];
                $_SESSION['name'] = $profile['ho_ten'];
                $_SESSION['role'] = 'sinh_vien';
            } else {
                $_SESSION['user_id'] = $id_link;
                $_SESSION['name'] = "Sinh viên (Chưa cập nhật tên)";
                $_SESSION['role'] = 'sinh_vien';
            }
        }

        // Chuyển hướng vào trang chủ
        header("Location: TRANGCHU.PHP");
        exit();

    } else {
        // Sai tên đăng nhập hoặc mật khẩu
        header("Location: LOGIN.HTML?error=1");
        exit();
    }
}
?>