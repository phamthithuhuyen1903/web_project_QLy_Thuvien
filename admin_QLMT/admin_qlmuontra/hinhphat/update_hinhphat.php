<?php
require '../../connectDB.php';

$ma_hp = $_GET['ma_hp'] ?? null;
$data = [];

if ($ma_hp) {
    $sql = "SELECT hp.*, sv.ho_ten 
            FROM hinh_phat hp 
            JOIN sinh_vien sv ON hp.ma_sv = sv.ma_sv
            WHERE hp.ma_hp = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ma_hp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ly_do     = $_POST['ly_do'];
        $ngay_phat = $_POST['ngay_phat'];
        $hinh_thuc = $_POST['hinh_thuc'];

        $update_sql = "UPDATE hinh_phat SET ly_do = ?, ngay_phat = ?, hinh_thuc = ? WHERE ma_hp = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "ssss", $ly_do, $ngay_phat, $hinh_thuc, $ma_hp);

        if (mysqli_stmt_execute($update_stmt)) {
            echo "<script>
                    alert('✔️ Cập nhật hình phạt thành công!');
                    window.location.href = 'danhsach_hinhphat.php';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('❌ Lỗi khi cập nhật hình phạt');
                    window.location.href = 'danhsach_hinhphat.php';
                  </script>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật hình phạt</title>
    <link rel="stylesheet" href="../../css/update_hinhphat.css">

</head>
<body>
<?php include '../admin_menu.php'; ?>
<div class="form-wrapper">
    <div class="form-container">
        <h2>Cập nhật hình phạt</h2>
        <form method="post">
            <div class="group_form">
                <label>Mã sinh viên</label>
                <input type="text" value="<?= htmlspecialchars($data['ma_sv'] ?? '') ?>" readonly>
            </div>
            <div class="group_form">
                <label>Tên sinh viên</label>
                <input type="text" value="<?= htmlspecialchars($data['ho_ten'] ?? '') ?>" readonly>
            </div>
            <div class="group_form">
                <label for="ly_do">Lý do</label>
                <input type="text" name="ly_do" id="ly_do" value="<?= htmlspecialchars($data['ly_do'] ?? '') ?>" required>
            </div>
            <div class="group_form">
                <label for="ngay_phat">Ngày phạt</label>
                <input type="date" name="ngay_phat" id="ngay_phat" value="<?= htmlspecialchars($data['ngay_phat'] ?? '') ?>" required>
            </div>
            <div class="group_form">
                <label for="hinh_thuc">Hình thức</label>
                <input type="text" name="hinh_thuc" id="hinh_thuc" value="<?= htmlspecialchars($data['hinh_thuc'] ?? '') ?>" required>
            </div>
            <button type="submit">Cập nhật</button>
        </form>
    </div>
</div>
</body>
</html>
