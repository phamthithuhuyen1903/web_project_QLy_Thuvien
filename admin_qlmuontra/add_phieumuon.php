<?php
require '../connectDB.php';

// Lấy danh sách sinh viên
$sql_sv = "SELECT ma_sv, ho_ten, lop FROM sinh_vien";
$result_sv = mysqli_query($conn, $sql_sv);
$sinhviens = [];
if ($result_sv) {
    while ($row = mysqli_fetch_assoc($result_sv)) {
        $sinhviens[] = $row;
    }
}

// Lấy danh sách sách
$sql_sach = "SELECT ma_sach, ten_sach FROM sach";
$result_sach = mysqli_query($conn, $sql_sach);
$sachs = [];
if ($result_sach) {
    while ($row = mysqli_fetch_assoc($result_sach)) {
        $sachs[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_pm = $_POST['ma_pm'];
    $ma_sv = $_POST['ma_sv'];
    $ma_sach = $_POST['ma_sach'];
    $ngaymuon = $_POST['ngaymuon'];
    $ngaytra = $_POST['ngaytra'];
    $tinhtrang = $_POST['tinhtrang'];
    $so_luong = $_POST['so_luong'];

    $sql = "INSERT INTO phieu_muon (ma_pm, ma_sv, ma_sach, ngay_muon, ngay_tra, tinh_trang, so_luong)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $ma_pm, $ma_sv, $ma_sach, $ngaymuon, $ngaytra, $tinhtrang, $so_luong);

    if (mysqli_stmt_execute($stmt)) {
        echo "<div style='text-align:center;
                        margin-top:50px;
                        font-size:20px;
                        color:green;
                        font-weight:bold;'>
                ✔️ Thêm phiếu mượn thành công!
            </div>";
        echo "<meta http-equiv='refresh' content='2;url=admin_giaodien.php'>";
        exit;
    } else {
        echo "<div style='text-align:center;
                        margin-top:50px;
                        font-size:20px;
                        color:red;
                        font-weight:bold;'>
                ❌ Lỗi khi thêm phiếu mượn
            </div>";
        echo "<meta http-equiv='refresh' content='2;url=admin_giaodien.php'>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm phiếu mượn</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- <link rel="stylesheet" href="/Project_QuanLyThuVien/css/style.css"> -->
    <link rel="stylesheet" href="/Project_QuanLyThuVien/css/add_phieumuon.css">
    
</head>
<body>

<?php include 'admin_menu.php'; ?>

<div class="form-wrapper">
    <div class="form-container">
        <h2>Thêm phiếu mượn</h2>
        <form method="post">
            <div class="form-group">
                <label for="ma_pm">Mã phiếu mượn</label>
                <input type="text" name="ma_pm" id="ma_pm" placeholder="Nhập mã phiếu mượn" required>
            </div>

            <div class="form-group">
                <label for="ma_sv">Sinh viên</label>
                <select name="ma_sv" id="ma_sv" required>
                    <option value="">-- Chọn sinh viên --</option>
                    <?php foreach ($sinhviens as $sv): ?>
                        <option value="<?= $sv['ma_sv'] ?>">
                            <?= htmlspecialchars($sv['ma_sv'] . " - " . $sv['ho_ten'] . " - " . $sv['lop']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ma_sach">Sách</label>
                <select name="ma_sach" id="ma_sach" required>
                    <option value="">-- Chọn sách --</option>
                    <?php foreach ($sachs as $s): ?>
                        <option value="<?= $s['ma_sach'] ?>"><?= htmlspecialchars($s['ten_sach']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="so_luong">Số lượng</label>
                <input type="number" name="so_luong" id="so_luong" min="1" value="1" required>
            </div>

            <div class="form-group">
                <label for="ngaymuon">Ngày mượn</label>
                <input type="date" name="ngaymuon" id="ngaymuon" required>
            </div>
            <div class="form-group">
                <label for="ngaytra">Ngày trả</label>
                <input type="date" name="ngaytra" id="ngaytra" required>
            </div>
            <div class="form-group">
                <label for="tinhtrang">Tình trạng</label>
                <select name="tinhtrang" id="tinhtrang" required>
                    <option value="">-- Chọn tình trạng --</option>
                    <option value="Đang cho mượn">Đang cho mượn</option>
                    <option value="Đã trả">Đã trả</option>
                    <option value="Trả chậm">Trả chậm</option>
                    <option value="Quá hạn trả">Quá hạn trả</option>
                </select>
            </div>
            <button type="submit">Thêm phiếu mượn</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#ma_sv').select2({
            placeholder: "-- Chọn sinh viên --",
            allowClear: true
        });
        $('#ma_sach').select2({
            placeholder: "-- Chọn sách --",
            allowClear: true
        });
    });
</script>

</body>
</html>
