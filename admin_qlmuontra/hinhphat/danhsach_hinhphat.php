<?php
include '../../connectDB.php';

$masv = $_GET['masv'] ?? '';
$tensv = $_GET['tensv'] ?? '';
$ngaymuon = $_GET['ngaymuon'] ?? '';
$ngaytra = $_GET['ngaytra'] ?? '';

// $sql = "SELECT pm.ma_sv, sv.ho_ten, pm.tinh_trang 
//         FROM phieu_muon pm 
//         JOIN sinh_vien sv ON pm.ma_sv = sv.ma_sv 
//         WHERE pm.ma_pm = ?
// ";

$sql = "SELECT hp.ma_sv, sv.ho_ten, hp.ly_do, hp.ngay_phat, hp.hinh_thuc, hp.ma_hp
        FROM hinh_phat hp
        JOIN sinh_vien sv ON hp.ma_sv = sv.ma_sv
        WHERE 1=1";

if (!empty($masv)) {
    $sql .= " AND hp.ma_sv LIKE '%" . mysqli_real_escape_string($conn, $masv) . "%'";
}
if (!empty($tensv)) {
    $sql .= " AND sv.ho_ten LIKE '%" . mysqli_real_escape_string($conn, $tensv) . "%'";
}
if (!empty($ngaymuon) && !empty($ngaytra)) {
    $sql .= " AND hp.ngay_phat BETWEEN '$ngaymuon' AND '$ngaytra'";
} elseif (!empty($ngaymuon)) {
    $sql .= " AND hp.ngay_phat >= '$ngaymuon'";
} elseif (!empty($ngaytra)) {
    $sql .= " AND hp.ngay_phat <= '$ngaytra'";
}

$sql .= " ORDER BY hp.ngay_phat DESC";


/*if (!empty($masv)) {
    $sql .= " AND hp.ma_sv LIKE '%" . mysqli_real_escape_string($conn, $masv) . "%'";
}
if (!empty($tensv)) {
    $sql .= " AND sv.ho_ten LIKE '%" . mysqli_real_escape_string($conn, $tensv) . "%'";
}
if (!empty($ngaymuon) && !empty($ngaytra)) {
    $sql .= " AND hp.ngay_phat BETWEEN '$ngaymuon' AND '$ngaytra'";
} elseif (!empty($ngaymuon)) {
    $sql .= " AND hp.ngay_phat >= '$ngaymuon'";
} elseif (!empty($ngaytra)) {
    $sql .= " AND hp.ngay_phat <= '$ngaytra'";
}

$sql .= " ORDER BY hp.ngay_phat DESC";*/

$result = mysqli_query($conn, $sql);
$dsPhat = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hình phạt</title>
    <style>
        body { font-family: Arial; background:#f8f9fa; padding:20px; }
        h1 { text-align:center; color:#007bff; }
        .filters { text-align:center; margin-bottom:20px; }
        .filters input, .filters button {
            padding:8px; margin:5px; border:1px solid #ced4da; border-radius:4px;
        }
        table { width:100%; border-collapse:collapse; background:#fff; }
        th, td { border:1px solid #dee2e6; padding:10px; text-align:center; }
        th { background:#007bff; color:#fff; }
        .add-button {
            display:inline-block; margin:10px 0; padding:8px 12px;
            background-color:#007bff; color:white; border-radius:4px;
            text-decoration:none;
        }
        .add-button:hover { background-color:#0056b3; }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
            padding: 6px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-icon i {
            font-size: 18px;
        }

        .btn-icon span {
            margin-left: 6px;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-weight: bold;
        }

        .btn-icon:hover span {
            opacity: 1;
        }

        .btn-icon:hover {
            background-color: #e2e6ea;
        }

    </style>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include '../admin_menu.php'; ?>

<h1>Danh sách hình phạt</h1>
<a href="form_xulyhp.php" class="add-button">+ Thêm hình phạt</a>

<form method="get" class="filters">
    <input type="text" name="masv" placeholder="Mã sinh viên" value="<?= htmlspecialchars($masv) ?>">
    <input type="text" name="tensv" placeholder="Tên sinh viên" value="<?= htmlspecialchars($tensv) ?>">
    <input type="date" name="ngaymuon" value="<?= htmlspecialchars($ngaymuon) ?>">
    <input type="date" name="ngaytra" value="<?= htmlspecialchars($ngaytra) ?>">
    <button type="submit">Tìm kiếm</button>
</form>

<?php if (empty($dsPhat)): ?>
    <p style="text-align:center; color:#888;">Không có dữ liệu hình phạt</p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>Mã SV</th>
            <th>Tên SV</th>
            <th>Lý do</th>
            <th>Ngày phạt</th>
            <th>Hình thức</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dsPhat as $phat): ?>
        <tr>
            
            <td><?= htmlspecialchars($phat['ma_sv']) ?></td>
            <td><?= htmlspecialchars($phat['ho_ten']) ?></td>
            <td><?= htmlspecialchars($phat['ly_do']) ?></td>
            <td><?= date('d-m-Y', strtotime($phat['ngay_phat'])) ?></td>
            <td><?= htmlspecialchars($phat['hinh_thuc']) ?></td>
            <!-- <td class="action-buttons">
                <a href="update_hinhphat.php?id=<?= $phat['ma_hp'] ?>" class="btn-icon edit" title="Sửa">
                    <i class="fas fa-pen"></i><span>Sửa</span>
                </a>
                <a href="delete_hinhphat.php?id=<?= $phat['ma_hp'] ?>" class="btn-icon delete" title="Xóa" onclick="return confirm('Xóa hình phạt này?')">
                    <i class="fas fa-trash"></i><span>Xóa</span>
                </a>
            </td> -->

            <td class="action-buttons">
                
                <a href="update_hinhphat.php?id=<?= $phat['ma_hp'] ?>" class="btn-icon edit" title="Sửa">
                    <i class="fas fa-pen"></i><span>Sửa</span>
                </a>
                <a href="delete_hinhphat.php?id=<?= $phat['ma_hp'] ?>" class="btn-icon delete" title="Xóa" onclick="return confirm('Xóa hình phạt này?')">
                    <i class="fas fa-trash"></i><span>Xóa</span>
                </a>
            </td>


        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

</body>
</html>
