<?php
require_once __DIR__ . '/../../../../connect/connect.php';
// 1. Lấy thông tin admin cũ để hiển thị lên form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT sinh_vien.*, user.username, user.password 
            FROM sinh_vien 
            INNER JOIN user ON sinh_vien.id = user.id 
            WHERE sinh_vien.ma_sv = '$id'";

    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res);
}

// 2. Xử lý khi người dùng nhấn nút "Cập nhật"
if (isset($_POST['update'])) {
    $ma_sv = $_GET['id'];
    $matkhau_moi = $_POST['mat_khau'];

    $sql_update = "
        UPDATE user u
        INNER JOIN sinh_vien s ON u.id = s.id
        SET u.password = '$matkhau_moi'
        WHERE s.ma_sv = '$ma_sv' ";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Đổi mật khẩu thành công!'); window.location='?controller=taikhoansv';</script>";
    } else {
        echo 'Lỗi: ' . mysqli_error($conn);
    }
}

?>

<html>

<head>
    <title>Sửa Sinh viên</title>
    <link href="../webthuvien/sinhvien/css/taikhoansv.css" rel="stylesheet">
</head>

<body>
    <div class="form-edit">
        <h2>Chỉnh sửa thông tin Sinh viên</h2>

        <form method="POST">
            <div class="form-container">

                <div class="form-group">
                    <label>Mã sinh viên</label>
                    <input type="text" value="<?php echo $data['ma_sv']; ?>" readonly class="readonly-field">
                </div>

                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" value="<?php echo $data['ho_ten']; ?>" readonly class="readonly-field">
                </div>

                <div class="form-group">
                    <label>Lớp</label>
                    <input type="text" value="<?php echo $data['lop']; ?>" readonly class="readonly-field">
                </div>

                <div class="form-group">
                    <label>Giới tính</label>
                    <select name="gioi_tinh" class="readonly-field">
                        <option value="Nam" <?php if ($data['gioi_tinh'] == 'Nam') echo 'selected'; ?>>Nam</option>
                        <option value="Nữ" <?php if ($data['gioi_tinh'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ngày sinh</label>
                    <input type="date" value="<?php echo $data['ngay_sinh']; ?>" readonly class="readonly-field">
                </div>

                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" value="<?php echo $data['dia_chi']; ?>" readonly class="readonly-field">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo $data['email']; ?>" readonly class="readonly-field">
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" value="<?php echo $data['sdt']; ?>" readonly class="readonly-field">
                </div>
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" value="<?php echo $data['username']; ?>" readonly class="readonly-field">
                </div>
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="text" name="mat_khau" value="<?php echo $data['password']; ?>" required>
                </div>
                <div class="button-group">
                    <button type="submit" name="update" class="btn-save">Cập nhật</button>
                    <a href="?controller=taikhoansv" class="btn-back">Hủy bỏ</a>
                </div>

            </div>
        </form>
    </div>
</body>

</html>