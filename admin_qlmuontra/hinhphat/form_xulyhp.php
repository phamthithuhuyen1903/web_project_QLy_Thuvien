<?php
$message = "";
$messageClass = "";

if (isset($_GET['success'])) {
    $message = "✔️ Thêm hình phạt thành công!";
    $messageClass = "success";
} elseif (isset($_GET['error'])) {
    $message = "❌ Lỗi khi thêm hình phạt!";
    $messageClass = "error";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xử lý hình phạt</title>
    <style>
        body {
            font-family: Arial;
            background: #f1f3f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .form-group { margin-bottom: 20px; }
        label { 
            display: block; 
            margin-bottom: 6px; 
            font-weight: bold; 
        }
        .required::after {
            content: " *";
            color: red;
        }
        input, select {
            width: 100%; padding: 8px;
            border: 1px solid #ced4da; border-radius: 4px;
        }
        input[readonly] {
            background-color: #e9ecef;
            color: #495057;
        }
        button {
            width: 100%; padding: 10px;
            background: #dc3545; color: #fff;
            border: none; border-radius: 4px;
            font-weight: bold;
        }
        button:hover { background: #c82333; }
        .message {
            margin-top: 15px; text-align: center;
            font-weight: bold; padding: 12px; border-radius: 6px;
        }
        .message.success { background-color: #d4edda; color: #155724; }
        .message.error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<div class="form-container">
    <h1>Xử lý hình phạt</h1>

    <form method="post" action="luu_hinhphat.php" onsubmit="return confirm('Bạn có chắc muốn thêm hình phạt này?')">
        <div class="form-group">
            <label class="required">Mã hình phạt:</label>
            <input type="text" name="ma_hp" required>
        </div>
        <div class="form-group">
            <label>Mã sinh viên:</label>
            <input type="text" name="ma_sv" value="<?= htmlspecialchars($_GET['masv'] ?? '') ?>" readonly>
        </div>
        <div class="form-group">
            <label>Tên sinh viên:</label>
            <input type="text" name="ho_ten" value="<?= htmlspecialchars($_GET['ho_ten'] ?? '') ?>" readonly>
        </div>
        <div class="form-group">
            <label>Tình trạng phiếu mượn:</label>
            <input type="text" name="tinh_trang" value="<?= htmlspecialchars($_GET['tinh_trang'] ?? '') ?>" readonly>
        </div>
        <div class="form-group">
            <label class="required" for="ly_do">Lý do vi phạm:</label>
            <input type="text" name="ly_do" id="ly_do" placeholder="Nhập lý do vi phạm" required>
        </div>
        <div class="form-group">
            <label class="required">Ngày phạt</label>
            <input type="date" name="ngay_phat" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
            <label class="required">Hình thức xử lý:</label>
            <input type="text" name="hinh_thuc" placeholder="VD: Cấm mượn 1 tháng" required>
        </div>
        <button type="submit">Lưu hình phạt</button>
    </form>

    <?php if ($message): ?>
        <div class="message <?= $messageClass ?>"><?= $message ?></div>
    <?php endif; ?>
    </div>

    <script>
        const messageBox = document.querySelector('.message');
        if (messageBox) {
            setTimeout(() => {
                messageBox.remove();
            }, 3000);
        }
    </script>
</body>
</html>
