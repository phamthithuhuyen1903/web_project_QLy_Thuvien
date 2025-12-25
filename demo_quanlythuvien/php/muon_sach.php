<?php
session_start();
require_once __DIR__ . '/../../connect/connect.php';

// if (!isset($_GET['ms'])) {
//     echo "Không có sách được chọn!";
//     exit;
// }

$sv = $_SESSION['id'];
$sql_sv = "select ma_sv from sinh_vien where id = '$sv'";
$result_sv = mysqli_query($conn, $sql_sv);
$row_sv = mysqli_fetch_assoc($result_sv);

$ma_sv = $row_sv['ma_sv'];

$ma_sach = $_GET['ms'];
$ma_loai = $_GET['ml'];
// Lấy thông tin sách
$sql = "SELECT * FROM sach WHERE ma_sach = '$ma_sach'";
$result = mysqli_query($conn, $sql);
$sach = mysqli_fetch_assoc($result);

if (!$sach) {
    echo "Sách không tồn tại!";
    exit;
}

// Xử lý khi bấm nút mượn
if (isset($_POST['muon'])) {

    $ma_pm     = 'PM' . time();
    $ma_sv     = $_POST['ma_sv'];
    $ngay_muon = $_POST['ngay_muon'];
    $ngay_tra  = $_POST['ngay_tra'];
    $so_luong  = (int)$_POST['so_luong'];

    $sql_pm = "INSERT INTO phieu_muon
               (ma_pm, ma_sv, ma_sach, tinh_trang, ngay_muon, ngay_tra, so_luong)
               VALUES
               ('$ma_pm','$ma_sv','$ma_sach','Đang mượn','$ngay_muon','$ngay_tra','$so_luong')";
    if (mysqli_query($conn, $sql_pm)) {

        mysqli_query($conn, "UPDATE sach SET so_luong = so_luong - $so_luong WHERE ma_sach='$ma_sach'");

        echo "<script>alert('Mượn sách thành công!'); window.location='../my/lsmt_process.php?ml=$ma_loai';</script>";
    } else {
        // Hiển thị lỗi SQL
        echo "<script>alert('Lỗi SQL: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!-- <!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Mượn sách</title> -->
<link rel="stylesheet" href="../css/muon_sach.css">
<!-- </head>

<body> -->

<h2>📚 PHIẾU MƯỢN SÁCH</h2>

<p><b>Tên sách:</b> <?php echo $sach['ten_sach']; ?></p>

<form method="post">
    <label>Mã sinh viên:</label><br>
    <input type="text" name="ma_sv" value="<?php echo htmlspecialchars($ma_sv); ?>"> <br><br>

    <label>Số lượng mượn:</label><br>
    <input type="number" name="so_luong" min="1" value="1" required required><br><br>

    <label>Ngày mượn:</label><br>
    <input type="date" name="ngay_muon"
        value="<?php echo date('Y-m-d'); ?>" required><br><br>

    <label>Ngày trả:</label><br>
    <input type="date" name="ngay_tra" required><br><br>

    <button type="submit" name="muon">Xác nhận mượn</button>

</form>

<!-- </body>

</html> -->