<?php
$message = "";
$messageClass = "";

if (isset($_GET['success'])) {
    $message = "✔️ Thêm hình phạt thành công!";
    $messageClass = "success";
} elseif (isset($_GET['error'])) {
    $message = "❌ Lỗi khi lưu hình phạt!";
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
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 60px);
            padding: 20px;
            margin-top: 60px;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(220, 53, 69, 0.15),
                        0 4px 12px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(220, 53, 69, 0.1);
            width: 400px;
            max-width: 100%;
        }
        
        h1 {
            text-align: center;
            color: #dc3545;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
            text-shadow: 0 3px 6px rgba(220, 53, 69, 0.3),
                         0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        .form-group { 
            margin-bottom: 20px; 
        }
        
        label { 
            display: block; 
            margin-bottom: 6px; 
            font-weight: bold;
            color: #333;
        }
        
        .required::after {
            content: " *";
            color: red;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 15px;
            transition: border-color 0.2s ease;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #dc3545;
        }
        
        input[readonly] {
            background-color: #e9ecef;
            color: #495057;
            cursor: not-allowed;
        }
        
        .form-container button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #dc3545, #e74c5e);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.5),
                        0 4px 10px rgba(0, 0, 0, 0.25);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        button:hover {
            background: linear-gradient(135deg, #c82333, #dc3545);
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 12px 28px rgba(220, 53, 69, 0.6),
                        0 6px 14px rgba(0, 0, 0, 0.3);
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
        }
        
        button:active {
            transform: translateY(2px) scale(0.95);
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.4),
                        0 2px 5px rgba(0, 0, 0, 0.2);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
            padding: 12px;
            border-radius: 6px;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
    <script src="/Project_QuanLyThuVien/logic_muonsach.js"></script>
</head>
<body>

    <div class="back-button" onclick="goBack()">
        Quay lại
    </div>

    <div class="page-wrapper">
    <div class="form-container">
        <h1>Xử lý hình phạt</h1>

        <form method="post" action="luu_hinhphat.php" onsubmit="return confirm('Bạn có chắc muốn thêm hình phạt này?')">
            <!-- <div class="form-group">
                <label class="required">Mã hình phạt:</label>
                <input type="text" name="ma_hp" required>
            </div> -->
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

            <div class="form-group">
                <label class="required">Tiến độ:</label>
                <select name="tien_do" required>
                    <option value="Chưa hoàn thành" selected>Chưa hoàn thành</option>
                    <option value="Đã hoàn thành">Đã hoàn thành</option>
                </select>
            </div>

            <button type="submit">Lưu hình phạt</button>
        </form>

        <?php if ($message): ?>
            <div class="message <?= $messageClass ?>"><?= $message ?></div>
        <?php endif; ?>
        </div>
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
