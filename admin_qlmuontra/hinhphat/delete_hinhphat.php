<?php
require '../../connectDB.php';

$ma_hp = $_GET['id'] ?? null;
$result = "";

if ($ma_hp) {
    // Xóa theo khóa chính ma_hp
    $sql = "DELETE FROM hinh_phat WHERE ma_hp = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $ma_hp);
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
    <title>Xóa hình phạt</title>
    <link rel="stylesheet" href="../css/style.css">
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
<?php include '../menu.php'; ?>
<div id="popup" class="popup"></div>

<script>
const popup = document.getElementById('popup');
const result = "<?= $result ?>";

if(result === "success") {
    popup.textContent = "✔️ Xóa hình phạt thành công!";
    popup.className = "popup";
    popup.style.display = "block";
    setTimeout(() => {
        popup.style.display = "none";
        window.location.href = "danhsach_hinhphat.php";
    }, 2000);
} else if(result === "error") {
    popup.textContent = "❌ Lỗi khi xóa hình phạt";
    popup.className = "popup error";
    popup.style.display = "block";
    setTimeout(() => {
        popup.style.display = "none";
        window.location.href = "danhsach_hinhphat.php";
    }, 2000);
}
</script>
</body>
</html>
