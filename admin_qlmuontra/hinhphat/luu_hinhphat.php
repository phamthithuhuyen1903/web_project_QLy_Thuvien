<?php
require '../../connectDB.php'; // Sử dụng MySQLi và biến $conn

// Lấy dữ liệu từ form
$ma_hp     = $_POST['ma_hp'] ?? '';
$ma_sv     = $_POST['ma_sv'] ?? '';
$ly_do     = $_POST['ly_do'] ?? '';
$ngay_phat = $_POST['ngay_phat'] ?? '';
$hinh_thuc = $_POST['hinh_thuc'] ?? '';
$tinh_trang = $_POST['tinh_trang'];

// Kiểm tra dữ liệu đầu vào
if (empty($ma_hp) || empty($ma_sv) || empty($ly_do) || empty($ngay_phat) || empty($hinh_thuc)) {
    header("Location: form_xulyhp.php?error=1");
    exit;
}

// Chuẩn bị câu lệnh SQL
$sql = "INSERT INTO hinh_phat (ma_hp, ma_sv, ly_do, ngay_phat, hinh_thuc)
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// Gán dữ liệu vào câu lệnh
mysqli_stmt_bind_param($stmt, "sssss", $ma_hp, $ma_sv, $ly_do, $ngay_phat, $hinh_thuc);

// Thực thi và xử lý kết quả
if (mysqli_stmt_execute($stmt)) {
    header("Location: ../admin_giaodien.php?success=1");
    exit;
} else {
    header("Location: ../admin_giaodien.php?error=1");
    exit;
}
?>
