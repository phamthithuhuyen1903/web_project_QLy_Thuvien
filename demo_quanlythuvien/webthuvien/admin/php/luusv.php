<?php
// Kết nối cơ sở dữ liệu
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Tiếp nhận dữ liệu từ $_POST
    $ma_sv = mysqli_real_escape_string($conn, $_POST['masv']);
    $ho_ten = mysqli_real_escape_string($conn, $_POST['hoten']);
    $lop = mysqli_real_escape_string($conn, $_POST['lop']);
    $gioi_tinh = $_POST['gioitinh'];
    $ngay_sinh = $_POST['ngaysinh'];
    $dia_chi = mysqli_real_escape_string($conn, $_POST['diachi']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);
    // $tentk = mysqli_real_escape_string($conn, $_POST['tentk']);
    // $matkhau = mysqli_real_escape_string($conn, $_POST['matkhau']);

    // 2. Kiểm tra tính hợp lệ (Ví dụ: kiểm tra mã sinh viên đã tồn tại chưa)
    $sql_check = "SELECT * FROM sinh_vien WHERE ma_sv = '$ma_sv'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('Lỗi: Mã sinh viên này đã tồn tại trong hệ thống!'); window.history.back();</script>";
    } else {
        // 3. Thực hiện câu lệnh SQL thêm mới
        $sql_insert = "INSERT INTO sinh_vien (ma_sv, ho_ten, lop, gioi_tinh, ngay_sinh, dia_chi, email, sdt) 
                       VALUES ('$ma_sv', '$ho_ten', '$lop', '$gioi_tinh', '$ngay_sinh', '$dia_chi', '$email', '$sdt')";

        if (mysqli_query($conn, $sql_insert)) {
            echo "<script>alert('Thêm sinh viên thành công!'); window.location.href='taikhoansv.php';</script>";
        } else {
            echo "Lỗi khi lưu: " . mysqli_error($conn);
        }
    }
}
?>