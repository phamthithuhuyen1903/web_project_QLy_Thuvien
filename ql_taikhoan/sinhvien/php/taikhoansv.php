<?php
session_start();
include '../../../Connect/connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sinh_vien') {
    header("Location: login.html");
    exit();
}
$ma_sv = $_SESSION['user_id'];

$sql = "SELECT sinh_vien.*, user.username, user.password 
        FROM sinh_vien 
        INNER JOIN user ON sinh_vien.id = user.id
        WHERE sinh_vien.ma_sv = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $ma_sv);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result); // Lấy dữ liệu 1 dòng duy nhất
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tài khoản sinh viên</title>
    <link href="/Project_QuanLyThuVien/ql_taikhoan/sinhvien/css/taikhoansv.css" rel="stylesheet">
    <link href="/Project_QuanLyThuVien/header.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
    <div class="thanhdieuhuong">
        <a href="/Project_QuanLyThuVien/phan_quyen/php/TRANGCHU.PHP" class="thanhdieuhuong_btn">
            <i class="fas fa-home"></i> Trang Chủ
        </a>
        <span class="thanhdieuhuong_separator">›</span>
        <a href="/Project_QuanLyThuVien/admin_qlmt/admin_qlmuontra/admin_giaodien.php" class="thanhdieuhuong_btn active">
            <i class="fas fa-user"></i> Quản lý tài khoản
        </a>
    </div>
    <div class="form-wrapper">
        <header>
            <h1>Thông tin tài khoản</h1>
        </header>

        <?php if ($row): ?>
            <div class="form-group">
                <label>Mã sinh viên</label>
                <input type="text" value="<?= htmlspecialchars($row['ma_sv']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" value="<?= htmlspecialchars($row['ho_ten']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Lớp</label>
                <input type="text" value="<?= htmlspecialchars($row['lop']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <input type="text" value="<?= htmlspecialchars($row['gioi_tinh']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="text" value="<?= date("d-m-Y", strtotime($row['ngay_sinh'])) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" value="<?= htmlspecialchars($row['email']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" value="<?= htmlspecialchars($row['sdt']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" value="<?= htmlspecialchars($row['dia_chi']) ?>" readonly>
            </div>

            <hr>

            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" value="<?= htmlspecialchars($row['username']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" value="<?= htmlspecialchars($row['password']) ?>" readonly>
            </div>

            <div style="text-align: center;">
                <a href="suasv.php?id=<?= $row['ma_sv'] ?>" class="btn-change-pass">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </a>
            </div>

        <?php else: ?>
            <p style="text-align: center; color: red;">Không tìm thấy thông tin sinh viên.</p>
        <?php endif; ?>
    </div>
</body>
</html>