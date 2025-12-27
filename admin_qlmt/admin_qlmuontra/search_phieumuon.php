<?php
require_once __DIR__ . '/../../Connect/connect.php';

$ten_sach   = $_GET['ten_sach']   ?? '';
$ma_sv      = $_GET['ma_sv']      ?? '';
$ho_ten     = $_GET['ho_ten']     ?? '';

$sql = "
    SELECT 
        pm.ma_pm,
        pm.ma_sv,
        pm.ma_sach,
        pm.so_luong, 
        pm.ngay_muon,
        pm.ngay_tra,
        pm.tinh_trang,
        sv.ho_ten,
        s.ten_sach,
        tg.ten_tg,
        s.nha_xb 
    FROM phieu_muon pm
    JOIN sinh_vien sv ON pm.ma_sv = sv.ma_sv
    JOIN sach s ON pm.ma_sach = s.ma_sach 
    JOIN tac_gia tg ON s.ma_tg = tg.ma_tg 
    WHERE 1=1
";

$params = [];
$types = "";

if ($ten_sach !== '') {
    $sql .= " AND s.ten_sach LIKE ?";
    $params[] = "%$ten_sach%";
    $types .= "s";
}
if ($ma_sv !== '') {
    $sql .= " AND sv.ma_sv LIKE ?";
    $params[] = "%$ma_sv%";
    $types .= "s";
}
if ($ho_ten !== '') {
    $sql .= " AND sv.ho_ten LIKE ?";
    $params[] = "%$ho_ten%";
    $types .= "s";
}

$stmt = mysqli_prepare($conn, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}
?>

<?php if (!empty($rows)): ?>
  <?php include __DIR__ . '/table_phieumuon.php'; ?>
<?php else: ?>
  <p>Không có kết quả phù hợp.</p>
<?php endif; ?>
