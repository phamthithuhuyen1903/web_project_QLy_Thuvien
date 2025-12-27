<?php
include '../../../Connect/connect.php';

// 1. Lấy thông tin admin cũ để hiển thị lên form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT admin.*, user.username, user.password 
            FROM admin 
            INNER JOIN user ON admin.id = user.id 
            WHERE admin.ma_admin = '$id'";   
    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res);
}

// 2. Xử lý khi người dùng nhấn nút "Cập nhật"
if (isset($_POST['update'])) {
    $id_admin_sua = $_GET['id'];
    $ho_ten = $_POST['ho_ten'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $password_moi = $_POST['mat_khau'];
    // $ten_dang_nhap = $_POST['ten_dang_nhap'];

    $sql_update_admin = "UPDATE admin SET 
                    ho_ten = '$ho_ten', 
                    gioi_tinh = '$gioi_tinh', 
                    email = '$email',
                    sdt = '$sdt'
                    WHERE ma_admin = '$id_admin_sua'";

    $id_user = $data['id']; // Lấy khóa ngoại từ dữ liệu đã fetch
    $sql_update_user = "UPDATE user 
                        SET password = '$password_moi'
                        WHERE id = '$id_user'";
    $check_admin = mysqli_query($conn, $sql_update_admin);
    $check_user = mysqli_query($conn, $sql_update_user);

    if ($check_admin && $check_user) {
        echo "<script>alert('Cập nhật thành công!'); window.location='taikhoanadmin.php';</script>";
    } else {
        echo "Lỗi cập nhật: " . mysqli_error($conn);
    }
}
?>

<html>
<head>
    <title>Sửa Admin</title>
    <link href="../css/suaadmin.css" rel="stylesheet">

</head>
<body>

   <div class="form-edit">
    <h2>Chỉnh sửa thông tin Admin</h2>

    <form method="POST">
        <div class="form-container">

            <div class="form-group">
                <label>Mã admin</label>
                <input type="text" value="<?php echo $data['ma_admin']; ?>" readonly class="readonly-field">
            </div>

            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="ho_ten" value="<?php echo $data['ho_ten']; ?>" required>
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <select name="gioi_tinh">
                    <option value="Nam" <?php if($data['gioi_tinh'] == 'Nam') echo 'selected'; ?>>Nam</option>
                    <option value="Nữ" <?php if($data['gioi_tinh'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt" value="<?php echo $data['sdt']; ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $data['email']; ?>" required>
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
                <a href="taikhoanadmin.php" class="btn-back">Hủy bỏ</a>
            </div>

        </div>
    </form>
</div>
</body>
</html>