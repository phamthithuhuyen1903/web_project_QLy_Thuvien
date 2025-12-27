<?php
// Thiết lập đường dẫn gốc
$duong_dan_goc = $_SERVER['DOCUMENT_ROOT'] . '/Project_QuanLyThuVien';

require_once $duong_dan_goc . '/Connect/connect.php';
require_once $duong_dan_goc . '/functions.php'; // Chứa ktraNgayTra, ktraTonKho, muonSach

if (!isset($_GET['ms'])) {
    echo "Không có sách được chọn!";
    exit;
}

$ma_sach = $_GET['ms'];
$ma_loai = $_GET['ml'];

// Lấy thông tin sách bằng Prepared Statement để bảo mật
$truy_van_sach = $conn->prepare("SELECT * FROM sach WHERE ma_sach = ?");
$truy_van_sach->bind_param("s", $ma_sach);
$truy_van_sach->execute();
$ket_qua = $truy_van_sach->get_result();
$sach = $ket_qua->fetch_assoc();

if (!$sach) {
    echo "Sách không tồn tại!";
    exit;
}

// Xử lý khi bấm nút mượn
if (isset($_POST['muon'])) {
    $ma_phieu_muon = taoMaPhieuMuonTuDong($conn);

    $ma_sinh_vien  = $_POST['ma_sv'];
    $ngay_muon     = $_POST['ngay_muon'];
    $ngay_tra      = $_POST['ngay_tra'];
    $so_luong      = (int)$_POST['so_luong'];

    // 1. Kiểm tra ngày trả bằng hàm dùng chung
    if (!ktraNgayTra($ngay_muon, $ngay_tra)) {
        echo "<script>alert('❌ Ngày trả không hợp lệ hoặc vượt quá 31 ngày!'); window.history.back();</script>";
        exit;
    }

    // 2. Kiểm tra tồn kho bằng hàm dùng chung
    if (!ktraTonKho($conn, $ma_sach, $so_luong)) {
        echo "<script>alert('❌ Rất tiếc, số lượng sách trong kho không đủ!'); window.history.back();</script>";
        exit;
    }

    // 3. Thực hiện mượn sách bằng hàm muonSach (có dùng Transaction)
    if (muonSach($conn, $ma_phieu_muon, $ma_sinh_vien, $ma_sach, $so_luong, $ngay_muon, $ngay_tra)) {
        echo "<script>alert('✔️ Mượn sách thành công!'); window.location='/Project_QuanLyThuVien/phan_quyen/php/lsmt_process.php?ml=$ma_loai';</script>";
    } else {
        echo "<script>alert('❌ Có lỗi hệ thống xảy ra, vui lòng thử lại sau!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Mượn sách</title>
    <link rel="stylesheet" href="../css/muon_sach.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
</head>

<body>
    <div class="back-button" onclick="goBack()">
        Quay lại
    </div>

    <h2>📚 PHIẾU MƯỢN SÁCH</h2>

    <div class="thong-tin-sach">
        <p><b>Tên sách:</b> <?php echo htmlspecialchars($sach['ten_sach']); ?></p>
        <p><b>Hiện có trong kho:</b> <span id="ton_kho"><?php echo $sach['so_luong']; ?></span> quyển</p>
    </div>

    <form method="post">
        <label>Mã sinh viên:</label><br>
        <input type="text" name="ma_sv" required><br><br>

        <label>Số lượng mượn:</label><br>
        <input type="number" name="so_luong" id="so_luong" min="1" value="1" required><br><br>

        <label>Ngày mượn:</label><br>
        <input type="date" name="ngay_muon" id="ngay_muon"
            value="<?php echo date('Y-m-d'); ?>" required><br><br>

        <label>Ngày trả:</label><br>
        <input type="date" name="ngay_tra" id="ngay_tra" required><br><br>

        <?php if ($sach['so_luong'] > 0): ?>
            <button type="submit" name="muon">Xác nhận mượn</button>
        <?php else: ?>
            <button type="button" style="background-color: #ccc; color: #666; cursor: not-allowed; border: 1px solid #999;" disabled>
                Hết sách trong kho
            </button>
            <p style="color: red; font-weight: bold; margin-top: 10px;">
                ⚠️ Rất tiếc, cuốn sách này hiện đã hết. Bạn vui lòng quay lại sau!
            </p>
        <?php endif; ?>
    </form>

    <script src="/Project_QuanLyThuVien/logic_muonsach.js"></script>
    <script>
        $(document).ready(function() {
            // Lấy số lượng tồn kho thực tế từ PHP
            let tonKhoThucTe = <?php echo $sach['so_luong']; ?>;

            // Gọi hàm kiểm tra dùng chung (Đã đổi tên biến tiếng Việt)
            kiemTra('#ngay_muon', '#ngay_tra', '#so_luong', tonKhoThucTe);

            // Kích hoạt logic ngày tháng ngay lập tức để đặt giới hạn cho ô ngày trả
            $('#ngay_muon').trigger('change');
        });
    </script>

</body>

</html>