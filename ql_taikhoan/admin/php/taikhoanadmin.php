<?php
include '../../../Connect/connect.php';
$sql = "SELECT admin.*, user.username, user.password 
        FROM admin 
        INNER JOIN user ON admin.id = user.id LIMIT 1";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tài khoản admin</title>
    <link href="../css/taikhoanadmin.css" rel="stylesheet">
    <link href="/Project_QuanLyThuVien/header.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="thanhdieuhuong">
        <a href="/Project_QuanLyThuVien/phan_quyen/php/TRANGCHU.PHP" class="thanhdieuhuong_btn">
            <i class="fas fa-home"></i> Trang Chủ
        </a>
        <span class="thanhdieuhuong_separator"></span>
        <a href="#" class="thanhdieuhuong_btn active">
            <i class="fas fa-user"></i> Tài khoản admin
        </a>
    </div>

    <div class="form-wrapper">
        <header>
            <h1>Thông tin cá nhân Admin</h1>
        </header>

        <?php if ($row): ?>
            <div class="form-group">
                <label>Mã admin</label>
                <input type="text" value="<?= htmlspecialchars($row['ma_admin']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" value="<?= htmlspecialchars($row['ho_ten']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <input type="text" value="<?= htmlspecialchars($row['gioi_tinh']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" value="<?= htmlspecialchars($row['sdt']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" value="<?= htmlspecialchars($row['email']) ?>" readonly>
            </div>

            <hr>
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" value="<?= htmlspecialchars($row['username']) ?>" readonly style="font-weight: bold;">
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" value="<?= htmlspecialchars($row['password']) ?>" readonly>
            </div>

            <div class="btn-action">
                <a href="suaadmin.php?id=<?= $row['ma_admin'] ?>">
                    <i class="fas fa-edit"></i> Chỉnh sửa thông tin
                </a>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: red;">Không tìm thấy dữ liệu Admin.</p>
        <?php endif; ?>
    </div>
</body>

</html>