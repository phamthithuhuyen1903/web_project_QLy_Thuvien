<?php
require_once './connect/connect.php';

if (!isset($_GET['ms'])) {
    echo "Không có sách được chọn!";
    exit;
}

$ma_sach = $_GET['ms'];

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
    $ngay_tra  = $_POST['ngay_tra'];  // do sinh viên nhập
    $so_luong  = (int)$_POST['so_luong'];

    $sql_pm = "INSERT INTO phieu_muon
               (ma_pm, ma_sv, ma_sach, tinh_trang, ngay_muon, ngay_tra, so_luong)
               VALUES
               ('$ma_pm','$ma_sv','$ma_sach','Dang muon','$ngay_muon','$ngay_tra','$so_luong')";

    if (mysqli_query($conn, $sql_pm)) {

        // Trừ số lượng sách
        mysqli_query(
            $conn,
            "UPDATE sach SET so_luong = so_luong - $so_luong WHERE ma_sach='$ma_sach'"
        );

        echo "<script>
                alert('Mượn sách thành công!');
                window.location='chitiet_sach.php?ms=$ma_sach';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Mượn sách</title>
    <link rel="stylesheet" href="../css/muon_sach.css">
</head>

<body>

    <h2>📚 PHIẾU MƯỢN SÁCH</h2>

    <p><b>Tên sách:</b> <?php echo $sach['ten_sach']; ?></p>

    <form method="post">
        <label>Mã sinh viên:</label><br>
        <input type="text" name="ma_sv" required><br><br>

        <label>Số lượng mượn:</label><br>
        <input type="number" name="so_luong" min="1" value="1" required><br><br>

        <label>Ngày mượn:</label><br>
        <input type="date" name="ngay_muon"
            value="<?php echo date('Y-m-d'); ?>" required><br><br>

        <label>Ngày trả:</label><br>
        <input type="date" name="ngay_tra" required><br><br>

        <button type="submit" name="muon">Xác nhận mượn</button>

    </form>

</body>

</html>