<?php
// Kết nối cơ sở dữ liệu
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Tiếp nhận dữ liệu từ $_POST
    $ma_admin = mysqli_real_escape_string($conn, $_POST['maadmin']);
    $ho_ten = mysqli_real_escape_string($conn, $_POST['hoten']);
    $gioi_tinh = $_POST['gioitinh'];
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // $tentk = mysqli_real_escape_string($conn, $_POST['tentk']);
    // $matkhau = mysqli_real_escape_string($conn, $_POST['matkhau']);

    // 2. Kiểm tra tính hợp lệ (Ví dụ: kiểm tra mã sinh viên đã tồn tại chưa)
    $sql_check = "SELECT * FROM admin WHERE maadmin = '$ma_admin'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('Lỗi: Mã admin này đã tồn tại trong hệ thống!'); window.history.back();</script>";
    } else {
        // 3. Thực hiện câu lệnh SQL thêm mới
        $sql_insert = "INSERT INTO admin (ma_admin, ho_ten, gioi_tinh, sdt, email) 
                       VALUES ('$ma_admin', '$ho_ten', '$gioi_tinh', '$sdt','$email')";

        if (mysqli_query($conn, $sql_insert)) {
            echo "<script>alert('Thêm admin thành công!'); window.location.href='taikhoanadmin.php';</script>";
        } else {
            echo "Lỗi khi lưu: " . mysqli_error($conn);
        }
    }
}
?>