<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $user = $_POST['username'];
    $pass = $_POST['password'];

    try {
        // BƯỚC 1: Kiểm tra tài khoản trong bảng 'user' (Chứa username, password, role)
        //
        $sqlAuth = "SELECT * FROM user WHERE username = :user AND password = :pass";
        $stmt = $conn->prepare($sqlAuth);
        $stmt->execute(['user' => $user, 'pass' => $pass]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account) {
            // Đăng nhập thành công, lấy thông tin liên kết
            $id_link = $account['username']; // Đây là mã liên kết (ví dụ: 'utt1' hoặc '74dctt22444')
            $role = $account['role'];

            // BƯỚC 2: Kiểm tra Role để lấy thông tin chi tiết
            // Yêu cầu: 0 là Admin, 1 là Sinh viên

            if ($role == 0) { 
                // --- TRƯỜNG HỢP ADMIN (Role = 0) ---
                // Truy vấn bảng 'admin' dựa trên ma_admin
                $sqlProfile = "SELECT * FROM admin WHERE ma_admin = :id";
                $stmtProfile = $conn->prepare($sqlProfile);
                $stmtProfile->execute(['id' => $id_link]);
                $profile = $stmtProfile->fetch(PDO::FETCH_ASSOC);

                if ($profile) {
                    $_SESSION['user_id'] = $profile['ma_admin'];
                    $_SESSION['name'] = $profile['ho_ten']; 
                    $_SESSION['role'] = 'admin';
                } else {
                    // Dự phòng nếu không tìm thấy trong bảng admin
                    $_SESSION['user_id'] = $id_link;
                    $_SESSION['name'] = "Admin (Chưa cập nhật tên)";
                    $_SESSION['role'] = 'admin';
                }

            } else { 
                // --- TRƯỜNG HỢP SINH VIÊN (Role = 1 hoặc khác 0) ---
                // Truy vấn bảng 'sinh_vien' dựa trên ma_sv
                $sqlProfile = "SELECT * FROM sinh_vien WHERE ma_sv = :id";
                $stmtProfile = $conn->prepare($sqlProfile);
                $stmtProfile->execute(['id' => $id_link]);
                $profile = $stmtProfile->fetch(PDO::FETCH_ASSOC);

                if ($profile) {
                    $_SESSION['user_id'] = $profile['ma_sv'];
                    $_SESSION['name'] = $profile['ho_ten'];
                    $_SESSION['role'] = 'sinh_vien';
                } else {
                    // Dự phòng nếu không tìm thấy trong bảng sinh_vien
                    $_SESSION['user_id'] = $id_link;
                    $_SESSION['name'] = "Sinh viên (Chưa cập nhật tên)";
                    $_SESSION['role'] = 'sinh_vien';
                }
            }

            // Chuyển hướng vào trang chủ
            header("Location: TRANGCHU.PHP");
            exit();

        } else {
            // Sai tên đăng nhập hoặc mật khẩu -> Trả về lỗi
            header("Location: LOGIN.HTML?error=1");
            exit();
        }

    } catch (PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
}
?>