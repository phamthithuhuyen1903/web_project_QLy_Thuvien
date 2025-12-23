<?php
session_start();
require_once 'db_connect.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: LOGIN.HTML");
    exit();
}

$userId = $_SESSION['user_id']; // Ví dụ: '74dctt22444' hoặc 'utt1'
$userRole = $_SESSION['role'];   // 'admin' hoặc 'sinh_vien'
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    // === TRƯỜNG HỢP ADMIN: Xem tất cả ===
    if ($userRole == 'admin') {
        // Nối bảng phieu_muon với sinh_vien để lấy ho_ten người mượn
        // Nối với sach để lấy ten_sach
        $sql = "SELECT p.*, s.ten_sach, sv.ho_ten 
                FROM phieu_muon p 
                LEFT JOIN sach s ON p.ma_sach = s.ma_sach 
                LEFT JOIN sinh_vien sv ON p.ma_sv = sv.ma_sv";
        
        // Logic tìm kiếm cho Admin
        if (!empty($search)) {
            $sql .= " WHERE p.ma_sv LIKE :search OR sv.ho_ten LIKE :search";
            $sql .= " ORDER BY p.ngay_muon DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $sql .= " ORDER BY p.ngay_muon DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }

    } else { 
    // === TRƯỜNG HỢP SINH VIÊN: Chỉ xem của mình ===
        $sql = "SELECT p.*, s.ten_sach 
                FROM phieu_muon p 
                LEFT JOIN sach s ON p.ma_sach = s.ma_sach 
                WHERE p.ma_sv = :my_id 
                ORDER BY p.ngay_muon DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute(['my_id' => $userId]);
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

// Gọi file giao diện để hiển thị
include 'lsmt_view.php'; 
?>