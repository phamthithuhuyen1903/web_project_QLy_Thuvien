<?php
include '../../../Connect/connect.php';

/* 1. Lấy thông tin sinh viên cũ */
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT sinh_vien.*, user.username, user.password 
            FROM sinh_vien
            INNER JOIN user ON sinh_vien.id = user.id
            WHERE sinh_vien.ma_sv = '$id'";

    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res);
}

/* 2. Xử lý cập nhật */
if (isset($_POST['update'])) {
    $id_sv_sua = $_GET['id'];
    $ho_ten = $_POST['ho_ten'];
    $lop = $_POST['lop'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $dia_chi = $_POST['dia_chi'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $password_moi = $_POST['mat_khau'];

    /* Update bảng sinh_vien */
    $sql_update_sv = "UPDATE sinh_vien SET
                        ho_ten = '$ho_ten',
                        lop = '$lop',
                        gioi_tinh = '$gioi_tinh',
                        ngay_sinh = '$ngay_sinh',
                        dia_chi = '$dia_chi',
                        email = '$email',
                        sdt = '$sdt'
                      WHERE ma_sv = '$id_sv_sua'";

    /* Update bảng user */
    $id_user = $data['id'];
    $sql_update_user = "UPDATE user
                        SET password = '$password_moi'
                        WHERE id = '$id_user'";

    $check_sv   = mysqli_query($conn, $sql_update_sv);
    $check_user = mysqli_query($conn, $sql_update_user);

    if ($check_sv && $check_user) {
        echo "<script>alert('Cập nhật thành công!'); window.location='taikhoansv.php';</script>";
    } else {
        echo "Lỗi cập nhật: " . mysqli_error($conn);
    }
}
?>
<html>

<head>

    <title>Sửa Sinh viên</title>
    <link href="../css/suasv.css" rel="stylesheet">

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
                    <label>Họ tên</label>
                    <input type="text" name="ho_ten" value="<?php echo $data['ho_ten']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Lớp</label>
                    <input type="text" name="lop" value="<?php echo $data['lop']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Giới tính</label>
                    <select name="gioi_tinh">
                        <option value="Nam" <?php if ($data['gioi_tinh'] == 'Nam') echo 'selected'; ?>>Nam</option>
                        <option value="Nữ" <?php if ($data['gioi_tinh'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ngày sinh</label>
                    <input type="date" name="ngay_sinh" value="<?php echo $data['ngay_sinh']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" name="dia_chi" value="<?php echo $data['dia_chi']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $data['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="sdt" value="<?php echo $data['sdt']; ?>" required>
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
                    <a href="taikhoansv.php" class="btn-back">Hủy bỏ</a>
                </div>

            </div>
        </form>
    </div>
</body>

</html>