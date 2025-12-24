<?php
require '../connectDB.php';

$ma_pm = $_GET['ma_pm'] ?? null;
$result = "";

if ($ma_pm) {
    $sql = "DELETE FROM phieu_muon WHERE ma_pm = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $ma_pm);
        if (mysqli_stmt_execute($stmt)) {
            $result = "success";
        } else {
            $result = "error";
        }
        mysqli_stmt_close($stmt);
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
    <link rel="stylesheet" href="../css/style.css">
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
    const popup = document.getElementById('popup');
    const result = "<?= $result ?>";

    if(result === "success") {
        popup.textContent = "✔️ Xóa phiếu mượn thành công!";
        popup.className = "popup";
        popup.style.display = "block";
        setTimeout(() => {
            popup.style.display = "none";
            window.location.href = "admin_giaodien.php";
        }, 2000);
    } else if(result === "error") {
        popup.textContent = "❌ Lỗi khi xóa phiếu mượn";
        popup.className = "popup error";
        popup.style.display = "block";
        setTimeout(() => {
            popup.style.display = "none";
            window.location.href = "admin_giaodien.php";
        }, 2000);
    }
    </script>
</body>
</html>
