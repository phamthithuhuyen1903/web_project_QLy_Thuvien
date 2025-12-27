<?php
require '../../../Connect/connect.php';

$ma_hp = $_GET['ma_hp'] ?? null;
$result = "";

if ($ma_hp) {
    // BƯỚC 1: Kiểm tra tiến độ thực tế trong database
    $check_sql = "SELECT tien_do FROM hinh_phat WHERE ma_hp = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $ma_hp);
    mysqli_stmt_execute($check_stmt);
    $check_res = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($check_res);

    if ($row) {
        // BƯỚC 2: Kiểm tra điều kiện tiến độ
        if ($row['tien_do'] === 'Đã hoàn thành') {
            // Nếu đã xong thì mới cho xóa
            $sql = "DELETE FROM hinh_phat WHERE ma_hp = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $ma_hp);
                if (mysqli_stmt_execute($stmt)) {
                    $result = "success";
                } else {
                    $result = "error";
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            // Nếu chưa xong, trả về mã riêng để hiện thông báo
            $result = "not_finished";
        }
    } else {
        $result = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa hình phạt</title>
    <link rel="stylesheet" href="/Project_QuanLyThuVien/admin_qlmt/admin_qlmuontra/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .popup {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #d4edda;
            color: #155724;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            display: none;
            z-index: 9999;
            font-weight: bold;
        }
        .popup.error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<div id="popup" class="popup"></div>

<script>
// const popup = document.getElementById('popup');
// const result = "<?= $result ?>";

// if(result === "success") {
//     popup.textContent = "✔️ Xóa hình phạt thành công!";
//     popup.className = "popup";
//     popup.style.display = "block";
//     setTimeout(() => {
//         popup.style.display = "none";
//         window.location.href = "danhsach_hinhphat.php";
//     }, 2000);
// } else if(result === "error") {
//     popup.textContent = "❌ Lỗi khi xóa hình phạt";
//     popup.className = "popup error";
//     popup.style.display = "block";
//     setTimeout(() => {
//         popup.style.display = "none";
//         window.location.href = "danhsach_hinhphat.php";
//     }, 2000);
// }

    const popup = document.getElementById('popup');
    const result = "<?= $result ?>";

    if(result === "success") {
        popup.textContent = "✔️ Xóa hình phạt thành công!";
        popup.className = "popup";
    } else if(result === "not_finished") {
        popup.textContent = "❌ Sinh viên chưa hoàn thành hình phạt, không thể xóa!";
        popup.className = "popup error";
    } else if(result === "error") {
        popup.textContent = "❌ Lỗi hệ thống khi xóa hình phạt";
        popup.className = "popup error";
    }

    if(result !== "") {
        popup.style.display = "block";
        setTimeout(() => {
            popup.style.display = "none";
            window.location.href = "danhsach_hinhphat.php";
        }, 2500);
    }

</script>
</body>
</html>
