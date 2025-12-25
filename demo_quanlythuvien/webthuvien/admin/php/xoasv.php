<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $ma_sv = $_GET['id'];

    mysqli_begin_transaction($conn);

    try {
        // 1. Xóa bảng con
        mysqli_query($conn, "DELETE FROM hinh_phat WHERE ma_sv = '$ma_sv'");
        mysqli_query($conn, "DELETE FROM phieu_muon WHERE ma_sv = '$ma_sv'");

        // 2. Xóa sinh viên
        mysqli_query($conn, "DELETE FROM sinh_vien WHERE ma_sv = '$ma_sv'");

        mysqli_commit($conn);

        echo "<script>
                alert('Xóa sinh viên và dữ liệu liên quan thành công!');
                window.location.href = 'taikhoansv.php';
              </script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Lỗi khi xóa: " . $e->getMessage();
    }
} else {
    header("Location: taikhoansv.php");
}
?>
