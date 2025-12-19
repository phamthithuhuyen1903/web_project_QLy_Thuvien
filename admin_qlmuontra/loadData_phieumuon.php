<?php
require '../connectDB.php';

$tinhtrang = $_GET['tinhtrang'] ?? null;

$sql = "SELECT 
        pm.ma_pm,
        pm.ma_sv,
        sv.ho_ten,
        pm.ma_sach,
        s.ten_sach, s.ten_tg, s.nha_xb,
        pm.ngay_muon, pm.ngay_tra,
        pm.tinh_trang 
    FROM phieu_muon pm
    JOIN sinh_vien sv ON pm.ma_sv = sv.ma_sv
    JOIN sach s ON pm.ma_sach = s.ma_sach";

if (!empty($tinhtrang)) {
    $sql .= " WHERE pm.tinh_trang = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $tinhtrang);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, $sql);
}

$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
} else {
    echo "Lỗi truy vấn: " . mysqli_error($conn);
}
?>

<?php 
    include 'table_phieumuon.php'; 
?>
