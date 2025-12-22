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

// Lấy từ khóa tìm kiếm từ phương thức GET
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    // Truy vấn kết hợp bảng 'lsmt' và 'sach' để lấy tên sách
    if ($userRole == 'admin') {
        // ADMIN: Join thêm bảng sinhvien để lấy HOTEN và tìm kiếm
        $sql = "SELECT l.*, s.TENSACH, sv.HOTEN 
                FROM lsmt l 
                LEFT JOIN sach s ON l.MASACH = s.MASACH 
                LEFT JOIN sinhvien sv ON l.MASV = sv.MASV";
        
        // Nếu có nhập nội dung tìm kiếm
        if (!empty($search)) {
            $sql .= " WHERE l.MASV LIKE :search OR sv.HOTEN LIKE :search";
            $sql .= " ORDER BY l.NGAYMUON DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $sql .= " ORDER BY l.NGAYMUON DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
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