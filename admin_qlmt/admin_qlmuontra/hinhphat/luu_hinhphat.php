<?php
require '../../../Connect/connect.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // $ma_hp     = $_POST['ma_hp'] ?? '';
    $ma_sv     = $_POST['ma_sv'] ?? '';
    $ly_do     = $_POST['ly_do'] ?? '';
    $ngay_phat = $_POST['ngay_phat'] ?? '';
    $hinh_thuc = $_POST['hinh_thuc'] ?? '';
    $tinh_trang = $_POST['tinh_trang'];
    $tien_do    = $_POST['tien_do'] ?? 'Chưa hoàn thành';

    if (empty($ma_sv) || empty($ly_do) || empty($ngay_phat) || empty($hinh_thuc)) {
        throw new Exception("Vui lòng nhập đầy đủ thông tin!");
    }

    $sql = "INSERT INTO hinh_phat (ma_sv, ly_do, ngay_phat, hinh_thuc, tien_do)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $ma_sv, $ly_do, $ngay_phat, $hinh_thuc, $tien_do);
    mysqli_stmt_execute($stmt);

    echo "<div style='text-align:center;
                    margin-top:50px;
                    font-size:20px;
                    color:green;
                    font-weight:bold;'>
            ✔️ Thêm hình phạt thành công!
        </div>";
    echo "<meta http-equiv='refresh' content='2;url=danhsach_hinhphat.php'>";
    exit;

} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    echo "<div style='text-align:center;
                    margin-top:50px;
                    font-size:20px;
                    color:red;
                    font-weight:bold;'>
            ❌ Lỗi khi thêm hình phạt!<br>$errorMsg
        </div>";
    echo "<meta http-equiv='refresh' content='5;url=danhsach_hinhphat.php'>";
    exit;
}
?>
