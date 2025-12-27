<?php
session_start();

include '../../Connect/connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'sinh_vien') {
    header("Location: ../../login_process.php");
    exit;
}

$ma_sv = $_SESSION['user_id'];

if (isset($_GET['ms'])) {
    $ma_sach = $_GET['ms'];
} else {
    $ma_sach = null;
}

if (isset($_GET['ml'])) {
    $ma_loai = $_GET['ml'];
} else {
    $ma_loai = null;
}

if (!$ma_sach) {
    die("Không tìm thấy mã sách");
}

$from = $_GET['from'] ?? '';

// kiểm tra đã yêu thích chưa
$check_sql = "SELECT 1 FROM yeu_thich WHERE ma_sv = '$ma_sv' and ma_sach='$ma_sach'";
$check = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check) > 0) {
    $sql = "DELETE FROM yeu_thich WHERE ma_sv = '$ma_sv' AND ma_sach='$ma_sach'";
    // mysqli_query($conn, $sql);
} else {
    $sql = "INSERT INTO yeu_thich(ma_sv, ma_sach) VALUES('$ma_sv', '$ma_sach')";
    // mysqli_query($conn, $sql);
}

if (mysqli_query($conn, $sql)) {
    header("Location: chitiet_yeuthich.php?ml=$ma_loai&ms=$ma_sach&from=$from");
    exit;
} else {
    echo "Lỗi khi cập nhật yêu thích: " . mysqli_error($conn);
}
