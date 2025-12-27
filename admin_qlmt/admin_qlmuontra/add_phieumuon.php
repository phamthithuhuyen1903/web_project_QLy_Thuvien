<?php
require_once __DIR__ . '/../../Connect/connect.php';
require_once __DIR__ . '/../../functions.php';

$message = ""; $messageClass = "";

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
$ma_pm_tu_dong = taoMaPhieuMuonTuDong($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_pm = $ma_pm_tu_dong;
    $ma_sv = $_POST['ma_sv'];
    $ma_sach = $_POST['ma_sach'];
    $ngaymuon = $_POST['ngaymuon'];
    $ngaytra = $_POST['ngaytra'];
    $tinhtrang = $_POST['tinhtrang'];
    $so_luong = $_POST['so_luong'];

    
    // Sử dụng hàm đã viết trong functions.php để kiểm tra và thực hiện mượn
    if (!ktraNgayTra($ngaymuon, $ngaytra)) {
        $error = "❌ Lỗi: Ngày trả không hợp lệ hoặc quá 31 ngày!";
    } elseif (!ktraTonKho($conn, $ma_sach, $so_luong)) {
        $error = "❌ Lỗi: Không đủ sách trong kho!";
    } else {
        // Thực hiện mượn sách bằng hàm trong functions.php (có transaction)
        if (muonSach($conn, $ma_pm, $ma_sv, $ma_sach, $so_luong, $ngaymuon, $ngaytra)) {
            echo "<div style='text-align:center; margin-top:50px; font-size:20px; color:green; font-weight:bold;'>
                    ✔️ Thêm phiếu mượn $ma_pm thành công!
                  </div>";
            echo "<meta http-equiv='refresh' content='2;url=admin_giaodien.php'>";
            exit;
        } else {
            $error = "❌ Lỗi hệ thống khi tạo phiếu mượn!";
        }
    }

    if (isset($error)) {
        echo "<div style='text-align:center; margin-top:50px; font-size:20px; color:red; font-weight:bold;'>$error</div>";
        echo "<meta http-equiv='refresh' content='3;url=add_phieumuon.php'>";
        exit;
    }

    // Kiểm tra mã phiếu mượn trùng
    $checkSql = "SELECT ma_pm FROM phieu_muon WHERE ma_pm = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $ma_pm);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<div style='text-align:center; margin-top:50px; font-size:20px; color:red; font-weight:bold;'>
                ❌ Mã phiếu mượn \"$ma_pm\" đã tồn tại, vui lòng nhập lại!
              </div>";
        echo "<meta http-equiv='refresh' content='4;url=add_phieumuon.php'>";
        exit;
    }

    // 1. Kiểm tra số lượng tồn kho trước khi thêm phiếu mượn
    $checkStockSql = "SELECT so_luong FROM sach WHERE ma_sach = ?";
    $checkStockStmt = mysqli_prepare($conn, $checkStockSql);
    mysqli_stmt_bind_param($checkStockStmt, "s", $ma_sach);
    mysqli_stmt_execute($checkStockStmt);
    $stockResult = mysqli_stmt_get_result($checkStockStmt);
    $stockRow = mysqli_fetch_assoc($stockResult);

    if ($stockRow['so_luong'] < $so_luong) {
        echo "<div style='text-align:center; margin-top:50px; font-size:20px; color:red; font-weight:bold;'>
                ❌ Không đủ sách trong kho để mượn!
              </div>";
        echo "<meta http-equiv='refresh' content='4;url=add_phieumuon.php'>";
        exit;
    }

    // 2. Thêm phiếu mượn
    $sql = "INSERT INTO phieu_muon (ma_pm, ma_sv, ma_sach, ngay_muon, ngay_tra, tinh_trang, so_luong)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $ma_pm, $ma_sv, $ma_sach, $ngaymuon, $ngaytra, $tinhtrang, $so_luong);

    if (mysqli_stmt_execute($stmt)) {
        // 3. Cập nhật số lượng sách trong kho
        $updateSql = "UPDATE sach SET so_luong = so_luong - ? WHERE ma_sach = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "is", $so_luong, $ma_sach);
        mysqli_stmt_execute($updateStmt);

        echo "<div style='text-align:center; margin-top:50px; font-size:20px; color:green; font-weight:bold;'>
                ✔️ Thêm phiếu mượn thành công và đã cập nhật số lượng sách!
              </div>";
        echo "<meta http-equiv='refresh' content='2;url=admin_giaodien.php'>";
        exit;
    } else {
        $errorMsg = mysqli_error($conn);
        echo "<div style='text-align:center; margin-top:50px; font-size:20px; color:red; font-weight:bold;'>
                ❌ Lỗi khi thêm phiếu mượn!<br>$errorMsg
              </div>";
        echo "<meta http-equiv='refresh' content='4;url=add_phieumuon.php'>";
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
    <link rel="stylesheet" href="/Project_QuanLyThuVien/admin_qlmt/css/add_phieumuon.css">
    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
    <script src="/Project_QuanLyThuVien/logic_muonsach.js"></script>
</head>
<body>

<div class="back-button" onclick="goBack()">
     Quay lại
</div>

<div class="form-wrapper">
    <div class="form-container">
        <h2>Thêm phiếu mượn</h2>
        <form method="post">
            <div class="form-group">
                <!-- <label for="ma_pm" class="required">Mã phiếu mượn</label>
                <input type="text" name="ma_pm" id="ma_pm" placeholder="Nhập mã phiếu mượn" required> -->
                <label for="ma_pm" class="required">Mã phiếu mượn (Tự động)</label>
                <input type="text" name="ma_pm_display" id="ma_pm" 
                    value="<?= $ma_pm_tu_dong ?>" 
                    readonly 
                    style="background-color: #e9ecef; cursor: not-allowed; font-weight: bold;">
                <small style="color: #666;">Mã này được hệ thống tự sinh để tránh trùng lặp.</small>
            </div>

            <div class="form-group">
                <label for="ma_sv" class="required">Sinh viên</label>
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
                <label for="ma_sach" class="required">Sách</label>
                <select name="ma_sach" id="ma_sach" required>
                    <option value="">-- Chọn sách --</option>
                    <?php foreach ($sachs as $s): ?>
                        <option value="<?= $s['ma_sach'] ?>"><?= htmlspecialchars($s['ten_sach']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="so_luong" class="required">Số lượng</label>
                <input type="number" name="so_luong" id="so_luong" min="1" value="1" required>
            </div>

            <div class="form-group">
                <label for="ngaymuon" class="required">Ngày mượn</label>
                <input type="date" name="ngaymuon" id="ngaymuon" required>
            </div>

            <div class="form-group">
                <label for="ngaytra" class="required">Ngày trả</label>
                <input type="date" name="ngaytra" id="ngaytra" required>
            </div>

            <div class="form-group">
                <label for="tinhtrang" class="required">Tình trạng</label>
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

        <?php if ($message): ?>
            <div class="message <?= $messageClass ?>"><?= $message ?></div>
        <?php endif; ?>
    </div>
</div>


</script>

<script>
    $(document).ready(function() {
        // Khởi tạo Select2
        $('#ma_sv').select2({
            placeholder: "-- Chọn sinh viên --",
            allowClear: true
        });
        $('#ma_sach').select2({
            placeholder: "-- Chọn sách --",
            allowClear: true
        });

        // Xử lý giới hạn ngày mượn/trả (Đưa vào bên trong ready)
        $('#ngaymuon').on('change', function() {
            let ngayMuonVal = this.value;
            let ngayTraInput = document.getElementById('ngaytra');
            
            if (ngayMuonVal) {
                let ngayMuon = new Date(ngayMuonVal);
                let maxDate = new Date(ngayMuon);
                maxDate.setDate(maxDate.getDate() + 31); // Cộng 31 ngày

                ngayTraInput.setAttribute('min', ngayMuonVal);
                ngayTraInput.setAttribute('max', maxDate.toISOString().split('T')[0]);
                
                if(ngayTraInput.value < ngayMuonVal || (ngayTraInput.value && new Date(ngayTraInput.value) > maxDate)) {
                    ngayTraInput.value = "";
                }
            }
        });
    });
</script>


</body>
</html>
