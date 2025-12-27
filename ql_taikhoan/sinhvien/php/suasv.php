<?php
session_start();
include '../../../Connect/connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sinh_vien') {
    header("Location: login.html");
    exit();
}
$ma_sv = $_SESSION['user_id'];
// 1. Lấy thông tin admin cũ để hiển thị lên form
// if (isset($_GET['id'])) {
//     $id = $_GET['id'];
    $sql = "SELECT sinh_vien.*, user.username, user.password 
            FROM sinh_vien 
            INNER JOIN user ON sinh_vien.id = user.id 
            WHERE sinh_vien.ma_sv = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ma_sv);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);
//}

// 2. Xử lý khi người dùng nhấn nút "Cập nhật"
if (isset($_POST['update'])) {
    $email_moi = $_POST['email'];      
    $sdt_moi = $_POST['sdt'];
    $matkhau_moi = $_POST['mat_khau'];

    $sql_sv = "UPDATE sinh_vien SET email = ?, sdt = ? WHERE ma_sv = ?";
    $stmt_sv = mysqli_prepare($conn, $sql_sv);
    mysqli_stmt_bind_param($stmt_sv, "sss", $email_moi, $sdt_moi, $ma_sv);
    $check_sv = mysqli_stmt_execute($stmt_sv);

    // Cập nhật bảng user (Mật khẩu)
    $sql_user = "UPDATE user u
                 INNER JOIN sinh_vien s ON u.id = s.id
                 SET u.password = ?
                 WHERE s.ma_sv = ?";
    $stmt_user = mysqli_prepare($conn, $sql_user);
    mysqli_stmt_bind_param($stmt_user, "ss", $matkhau_moi, $ma_sv);
    $check_user = mysqli_stmt_execute($stmt_user);

    if ($check_sv && $check_user) {
        echo "<script>alert('Cập nhật thông tin thành công!'); window.location='taikhoansv.php';</script>";
    } else {
        echo "Lỗi cập nhật!";
    }
}

?>

<html>
<head>
    <title>Sửa Sinh viên</title>
    <link href="../css/suasv.css" rel="stylesheet">
    <link href="/Project_QuanLyThuVien/header.css" rel="stylesheet">
    <script src="/Project_QuanLyThuVien/logic_muonsach.js"></script>

</head>
<body>

    <div class="back-button" onclick="goBack()">
        Quay lại
    </div>
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
                        <option value="Nam" <?php if($data['gioi_tinh']=='Nam') echo 'selected'; ?>>Nam</option>
                        <option value="Nữ" <?php if($data['gioi_tinh']=='Nữ') echo 'selected'; ?>>Nữ</option>
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
                    <input type="email" name = "email" value="<?php echo $data['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name = "sdt" value="<?php echo $data['sdt']; ?>" required>
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