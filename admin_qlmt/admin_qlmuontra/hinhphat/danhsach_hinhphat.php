<?php
require '../../../Connect/connect.php';

$action = $_GET['action'] ?? '';

$masv = $_GET['masv'] ?? '';
$tensv = $_GET['tensv'] ?? '';
$ngaymuon = $_GET['ngaymuon'] ?? '';
$ngaytra = $_GET['ngaytra'] ?? '';

if ($action === 'all') {
    $masv = '';
    $tensv = '';
    $ngaymuon = '';
    $ngaytra = '';
}


$sql = "SELECT ma_hp, hp.ma_sv, sv.ho_ten, hp.ly_do, hp.ngay_phat, hp.hinh_thuc, hp.tien_do
        FROM hinh_phat hp
        JOIN sinh_vien sv ON hp.ma_sv = sv.ma_sv
        WHERE 1=1";

if (!empty($masv)) {
    $sql .= " AND hp.ma_sv LIKE '%" . mysqli_real_escape_string($conn, $masv) . "%'";
}
if (!empty($tensv)) {
    $sql .= " AND sv.ho_ten LIKE '%" . mysqli_real_escape_string($conn, $tensv) . "%'";
}

$sql .= " ORDER BY hp.ngay_phat DESC";

$result = mysqli_query($conn, $sql);
$dsPhat = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hình phạt</title>
    <link rel="stylesheet" href="/Project_QuanLyThuVien/admin_qlmt/css/danhsach_hinhphat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="thanhdieuhuong">
        <a href="/Project_QuanLyThuVien/phan_quyen/php/TRANGCHU.PHP" class="thanhdieuhuong_btn">
            <i class="fas fa-home"></i> Trang Chủ
        </a>
        <span class="thanhdieuhuong_separator">›</span>
        <a href="/Project_QuanLyThuVien/admin_qlmt/admin_qlmuontra/hinhphat/danhsach_hinhphat.php" class="thanhdieuhuong_btn active">
            <i class="fas fa-history"></i> Xem lịch sử hình phạt
        </a>
    </div>
<h1>Danh sách hình phạt</h1>

<form method="get" class="filters">
    <input type="text" name="masv" placeholder="Mã sinh viên" value="<?= htmlspecialchars($masv) ?>">
    <input type="text" name="tensv" placeholder="Tên sinh viên" value="<?= htmlspecialchars($tensv) ?>">
    <button type="submit">Tìm kiếm</button>
    <button type="submit" name="action" value="all" class="show_button">Hiển thị tất cả</button>
</form>

<?php if (empty($dsPhat)): ?>
    <p style="text-align:center; color:#888;">Không có dữ liệu hình phạt</p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>Mã HP</th>
            <th>Mã SV</th>
            <th>Tên SV</th>
            <th>Lý do</th>
            <th>Ngày phạt</th>
            <th>Hình thức</th>
            <th>Tiến độ</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dsPhat as $phat): ?>
        <tr>
            <td><?= htmlspecialchars($phat['ma_hp']) ?></td>
            <td><?= htmlspecialchars($phat['ma_sv']) ?></td>
            <td><?= htmlspecialchars($phat['ho_ten']) ?></td>
            <td><?= htmlspecialchars($phat['ly_do']) ?></td>
            <td><?= date('d-m-Y', strtotime($phat['ngay_phat'])) ?></td>
            <td><?= htmlspecialchars($phat['hinh_thuc']) ?></td>
            <td>
                <span style="font-weight: bold; color: <?= ($phat['tien_do'] === 'Đã hoàn thành') ? '#28a745' : '#dc3545' ?>;">
                    <?= htmlspecialchars($phat['tien_do']) ?>
                </span>
            </td>

            <td class="action-buttons">
                
                <a href="update_hinhphat.php?ma_hp=<?= $phat['ma_hp'] ?>" class="btn-icon edit" title="Sửa">
                    <i class="fas fa-pen"></i><span>Sửa</span>
                </a>
                <!-- <a href="delete_hinhphat.php?ma_hp=<?= $phat['ma_hp'] ?>" class="btn-icon delete" title="Xóa" onclick="return confirm('Xóa hình phạt này?')">
                    <i class="fas fa-trash"></i><span>Xóa</span>
                </a> -->

                <?php if ($phat['tien_do'] === 'Đã hoàn thành'): ?>
                    <a href="delete_hinhphat.php?ma_hp=<?= $phat['ma_hp'] ?>" 
                    class="btn-icon delete" 
                    title="Xóa" 
                    onclick="return confirm('Bạn chắc chắn muốn xóa hình phạt đã hoàn thành này?')">
                        <i class="fas fa-trash"></i><span>Xóa</span>
                    </a>
                <?php else: ?>
                    <a href="javascript:void(0);" 
                    class="btn-icon delete" 
                    title="Không thể xóa" 
                    style="opacity: 0.5; cursor: not-allowed;"
                    onclick="alert('Sinh viên chưa hoàn thành hình phạt, không thể xóa!')">
                        <i class="fas fa-trash"></i><span>Xóa</span>
                    </a>
                <?php endif; ?>
                
            </td>


        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

</body>
</html>
