<?php
session_start();
// Kết nối database bằng MySQLi
require_once __DIR__ . '/../../connect/connect.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['id'])) {
    header("Location: LOGIN.HTML");
    exit();
}

$userId = $_SESSION['id'];
$userRole = $_SESSION['role'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$data = [];

// === TRƯỜNG HỢP 1: ADMIN (Xem tất cả phiếu mượn) ===
if ($userRole == 'admin') {
    if (!empty($search)) {
        // Tìm kiếm theo mã SV hoặc Họ tên SV
        $sql = "SELECT p.*, s.ten_sach, sv.ho_ten 
                FROM phieu_muon p 
                LEFT JOIN sach s ON p.ma_sach = s.ma_sach 
                LEFT JOIN sinh_vien sv ON p.ma_sv = sv.ma_sv
                WHERE p.ma_sv LIKE ? OR sv.ho_ten LIKE ?
                ORDER BY p.ngay_muon DESC";

        $stmt = mysqli_prepare($conn, $sql);
        $searchParam = "%$search%";
        mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);
    } else {
        // Lấy toàn bộ không tìm kiếm
        $sql = "SELECT p.*, s.ten_sach, sv.ho_ten 
                FROM phieu_muon p 
                LEFT JOIN sach s ON p.ma_sach = s.ma_sach 
                LEFT JOIN sinh_vien sv ON p.ma_sv = sv.ma_sv
                ORDER BY p.ngay_muon DESC";
        $stmt = mysqli_prepare($conn, $sql);
    }
} else {
    // === TRƯỜNG HỢP 2: SINH VIÊN (Chỉ xem của bản thân) ===
    $sql = "SELECT p.*, s.ten_sach, sv.ma_sv 
            FROM phieu_muon p 
            INNER JOIN sinh_vien sv ON p.ma_sv = sv.ma_sv 
            LEFT JOIN sach s ON p.ma_sach = s.ma_sach 
            WHERE sv.id = ? 
            ORDER BY p.ngay_muon DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userId);
}

// Thực thi câu lệnh và lấy dữ liệu
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        die("Lỗi lấy dữ liệu: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);
} else {
    die("Lỗi chuẩn bị câu lệnh: " . mysqli_error($conn));
}

// Đóng kết nối
// mysqli_close($conn);

// Gọi file giao diện để hiển thị
include 'lsmt_view.php';
