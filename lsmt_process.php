<?php
session_start();
require_once 'db_connect.php';

// Kiểm tra quyền truy cập (Ví dụ chỉ cho phép người đã đăng nhập)
if (!isset($_SESSION['user_id'])) {
    header("Location: LOGIN.HTML");
    exit();
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

try {
    // Truy vấn kết hợp bảng 'lsmt' và 'sach' để lấy tên sách
    if ($userRole == 'admin') {
        // Admin xem được tất cả
        $sql = "SELECT l.*, s.TENSACH 
                FROM lsmt l 
                LEFT JOIN sach s ON l.MASACH = s.MASACH 
                ORDER BY l.NGAYMUON DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    } else {
        // Sinh viên chỉ xem được lịch sử của mình
        $sql = "SELECT l.*, s.TENSACH 
                FROM lsmt l 
                LEFT JOIN sach s ON l.MASACH = s.MASACH 
                WHERE l.MASV = :MASV 
                ORDER BY l.NGAYMUON DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['MASV' => $userId]);
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
include 'lsmt_view.php';
?>