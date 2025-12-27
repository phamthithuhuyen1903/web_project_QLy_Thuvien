<?php
require_once __DIR__ . '/../../Connect/connect.php';

$ma_pm = $_GET['ma_pm'] ?? null;
$result = "";

if ($ma_pm) {
    // 1. Kiểm tra tình trạng của phiếu trước khi xóa
    $check_sql = "SELECT tinh_trang FROM phieu_muon WHERE ma_pm = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $ma_pm);
    mysqli_stmt_execute($check_stmt);
    $check_res = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($check_res);

    if ($row) {
        // Chỉ cho phép xóa nếu tình trạng là 'Đã trả'
        if ($row['tinh_trang'] === 'Đã trả') {
            $sql = "DELETE FROM phieu_muon WHERE ma_pm = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $ma_pm);
            
            if (mysqli_stmt_execute($stmt)) {
                $result = "success";
            } else {
                $result = "error";
            }
        } else {
            // Thông báo lỗi nếu chưa trả sách
            $result = "not_returned";
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
    <title>Xóa phiếu mượn</title>
    <link rel="stylesheet" href="../../css/style.css">
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
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div id="popup" class="popup"></div>

    <script>
    const popup = document.getElementById('popup');
    const result = "<?= $result ?>";

    if(result === "success") {
        popup.textContent = "✔️ Xóa phiếu mượn thành công!";
        popup.className = "popup";
        //popup.style.display = "block";
        // setTimeout(() => {
        //     popup.style.display = "none";
        //     window.location.href = "admin_giaodien.php";
        // }, 2000);
    } else if (result === "not_returned") {
        popup.textContent = "❌ Sinh viên này đang mượn sách, không thể xóa phiếu mượn này!";
        popup.className = "popup error";
        //popup.style.display = "block";
    }else if(result === "error") {
        popup.textContent = "❌ Lỗi khi xóa phiếu mượn";
        popup.className = "popup error";
        // popup.style.display = "block";
        // setTimeout(() => {
        //     popup.style.display = "none";
        //     window.location.href = "admin_giaodien.php";
        // }, 2000);
    }

    // Hiển thị popup
    if (result !== "") {
        popup.style.display = "block";
        
        // Luôn quay về trang giao diện sau 2.5 giây bất kể thành công hay lỗi
        setTimeout(() => {
            window.location.href = "admin_giaodien.php";
        }, 2500);
    } else {
        // Nếu vào trang mà không có mã PM, quay về luôn
        window.location.href = "admin_giaodien.php";
    }
    </script>
</body>
</html>
