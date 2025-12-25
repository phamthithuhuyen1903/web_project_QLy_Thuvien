<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $ma_sv = $_POST['masv'];
    $ho_ten = $_POST['hoten'];
    $lop = $_POST['lop'];
    $gioi_tinh = $_POST['gioitinh'];
    $ngay_sinh = $_POST['ngaysinh'];
    $dia_chi = $_POST['diachi'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    // $tentk = $_POST['tentk'];
    // $matkhau = $_POST['matkhau'];

    // Câu lệnh SQL
    $sql_insert = "INSERT INTO sinh_vien (ma_sv, ho_ten, lop, gioi_tinh, ngay_sinh, dia_chi, email, sdt) 
                   VALUES ('$ma_sv', '$ho_ten', '$lop', '$gioi_tinh', '$ngay_sinh', '$dia_chi', '$email', '$sdt')";

    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('Thêm sinh viên thành công!'); window.location.href='taikhoansv.php';</script>";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tài khoản sinh viên</title>
    <link rel="stylesheet" href="../css/themtksv.css"> 
</head>
<body>
    <h2>Thêm mới tài khoản sinh viên</h2>

    <form action="" method="POST" class="fromadd">
        <div class="form-container">
            <div class="form-group">
                <label>Mã sinh viên</label>
                <input type="text" name="masv" required placeholder="Nhập mã SV">
            </div>

            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="hoten" required placeholder="Nhập họ tên">
            </div>

            <div class="form-group">
                <label>Lớp</label>
                <input type="text" name="lop">
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <select name="gioitinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="date" name="ngaysinh">
            </div>

            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="diachi">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email">
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt">
            </div>

            <!-- <div class="form-group">
                <label>Tên tài khoản</label>
                <input type="text" name="tentk" required>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="matkhau" required>
            </div> -->

            <div class="button-group">
                <button type="submit" name="btn_them" class="btnthem">Lưu</button>
                <button type="button" class="btnback" onclick="history.back()">Quay lại</button> 
            </div>
        </div>
    </form>
</body>
</html>