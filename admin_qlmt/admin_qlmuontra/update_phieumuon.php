<?php
// Thiết lập đường dẫn gốc
$duong_dan_goc = $_SERVER['DOCUMENT_ROOT'] . '/Project_QuanLyThuVien';

require_once $duong_dan_goc . '/Connect/connect.php';
require_once $duong_dan_goc . '/functions.php'; // Chứa ktraNgayTra, ktraTonKho

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$ma_phieu_muon = $_GET['ma_pm'] ?? null;

if ($ma_phieu_muon) {
    // 1. Lấy thông tin chi tiết phiếu mượn
    $truy_van = "SELECT pm.*, sv.ho_ten AS ten_sinh_vien, s.ten_sach 
                 FROM phieu_muon pm
                 JOIN sinh_vien sv ON pm.ma_sv = sv.ma_sv
                 JOIN sach s ON pm.ma_sach = s.ma_sach
                 WHERE pm.ma_pm = ?";
    $cau_lenh = $conn->prepare($truy_van);
    $cau_lenh->bind_param("s", $ma_phieu_muon);
    $cau_lenh->execute();
    $du_lieu = $cau_lenh->get_result()->fetch_assoc();

    if (!$du_lieu) {
        echo "<script>alert('Không tìm thấy phiếu mượn!'); window.location.href='admin_giaodien.php';</script>";
        exit;
    }

    // Lấy danh sách sách để chọn khi muốn đổi sách
    $danh_sach_sach = $conn->query("SELECT ma_sach, ten_sach, so_luong FROM sach")->fetch_all(MYSQLI_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ma_sach_moi = $_POST['ma_sach'];
        $so_luong_moi = (int)$_POST['so_luong'];
        $ngay_tra_moi = $_POST['ngay_tra'];
        $tinh_trang_moi = $_POST['tinh_trang'];

        $ma_sach_cu = $du_lieu['ma_sach'];
        $so_luong_cu = (int)$du_lieu['so_luong'];
        $tinh_trang_cu = $du_lieu['tinh_trang'];

        // 2. Kiểm tra logic ngày trả (không quá 1 tháng)
        if (!ktraNgayTra($du_lieu['ngay_muon'], $ngay_tra_moi)) {
            echo "<script>alert('❌ Lỗi: Ngày trả không hợp lệ hoặc vượt quá 1 tháng!'); window.history.back();</script>";
            exit;
        }

        $conn->begin_transaction();
        try {
            // 3. Cập nhật bảng phiếu mượn
            $cap_nhat_phieu = $conn->prepare("UPDATE phieu_muon SET ngay_tra = ?, tinh_trang = ?, ma_sach = ?, so_luong = ? WHERE ma_pm = ?");
            $cap_nhat_phieu->bind_param("sssis", $ngay_tra_moi, $tinh_trang_moi, $ma_sach_moi, $so_luong_moi, $ma_phieu_muon);
            $cap_nhat_phieu->execute();

            // 4. Xử lý logic kho sách
            if ($tinh_trang_moi === "Đã trả") {
                if ($tinh_trang_cu !== "Đã trả") {
                    // Trả sách lại vào kho
                    $conn->query("UPDATE sach SET so_luong = so_luong + $so_luong_cu WHERE ma_sach = '$ma_sach_cu'");

                    // Kiểm tra lại số lượng sau khi trả để cập nhật tình trạng
                    $kiem_tra_sl = $conn->query("SELECT so_luong FROM sach WHERE ma_sach = '$ma_sach_cu'");
                    $sl_hien_tai = $kiem_tra_sl->fetch_assoc()['so_luong'];

                    if ($sl_hien_tai > 0) {
                        $conn->query("UPDATE sach SET tinh_trang = 'Còn' WHERE ma_sach = '$ma_sach_cu'");
                    } else {
                        $conn->query("UPDATE sach SET tinh_trang = 'Hết' WHERE ma_sach = '$ma_sach_cu'");
                    }
                }
            } else {
                // Nếu vẫn đang mượn hoặc trả chậm
                if ($ma_sach_moi === $ma_sach_cu) {
                    $chenh_lech = $so_luong_moi - $so_luong_cu;
                    if ($chenh_lech > 0) { // Mượn thêm
                        if (!ktraTonKho($conn, $ma_sach_moi, $chenh_lech)) throw new Exception("Kho không đủ sách để mượn thêm!");
                        $conn->query("UPDATE sach SET so_luong = so_luong - $chenh_lech WHERE ma_sach = '$ma_sach_moi'");
                    } else if ($chenh_lech < 0) { // Trả bớt
                        $conn->query("UPDATE sach SET so_luong = so_luong + " . abs($chenh_lech) . " WHERE ma_sach = '$ma_sach_moi'");
                    }
                } else {
                    // Đổi sang sách khác
                    $conn->query("UPDATE sach SET so_luong = so_luong + $so_luong_cu WHERE ma_sach = '$ma_sach_cu'");

                    // Cập nhật tình trạng sách cũ
                    $kiem_tra_sl = $conn->query("SELECT so_luong FROM sach WHERE ma_sach = '$ma_sach_cu'");
                    $sl_hien_tai = $kiem_tra_sl->fetch_assoc()['so_luong'];
                    if ($sl_hien_tai > 0) {
                        $conn->query("UPDATE sach SET tinh_trang = 'Còn' WHERE ma_sach = '$ma_sach_cu'");
                    } else {
                        $conn->query("UPDATE sach SET tinh_trang = 'Hết' WHERE ma_sach = '$ma_sach_cu'");
                    }

                    // Trừ sách mới
                    if (!ktraTonKho($conn, $ma_sach_moi, $so_luong_moi)) throw new Exception("Sách mới không đủ số lượng trong kho!");
                    $conn->query("UPDATE sach SET so_luong = so_luong - $so_luong_moi WHERE ma_sach = '$ma_sach_moi'");
                }
            }

            $conn->commit();
            echo "<script>alert('✔️ Cập nhật thành công!'); window.location.href='admin_giaodien.php';</script>";
        } catch (Exception $loi) {
            $conn->rollback();
            echo "<script>alert('❌ Lỗi: " . $loi->getMessage() . "'); window.history.back();</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cập nhật phiếu mượn</title>
    <link rel="stylesheet" href="/Project_QuanLyThuVien/admin_qlmt/css/update_phieumuon.css">
    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="back-button" onclick="goBack()">
        Quay lại
    </div>

    <div class="page-wrapper">
        <div class="form-container">
            <h2>Cập nhật phiếu mượn</h2>

            <form method="post">
                <div class="form-group">
                    <label>Sinh viên</label>
                    <input type="text" value="<?= $du_lieu['ma_sv'] . " - " . htmlspecialchars($du_lieu['ten_sinh_vien']) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="ma_sach">Sách mượn</label>
                    <select name="ma_sach" id="ma_sach" required>
                        <?php foreach ($danh_sach_sach as $sach): ?>
                            <option value="<?= $sach['ma_sach'] ?>"
                                data-tonkho="<?= $sach['so_luong'] ?>"
                                <?= ($sach['ma_sach'] == $du_lieu['ma_sach']) ? "selected" : "" ?>>
                                <?= htmlspecialchars($sach['ten_sach']) ?> (Còn: <?= $sach['so_luong'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="so_luong">Số lượng mượn</label>
                    <input type="number" name="so_luong" id="so_luong" value="<?= $du_lieu['so_luong'] ?>" min="1" required>
                </div>

                <div class="form-group">
                    <label>Ngày mượn</label>
                    <input type="date" id="ngay_muon" value="<?= $du_lieu['ngay_muon'] ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="ngay_tra">Ngày trả</label>
                    <input type="date" name="ngay_tra" id="ngay_tra" value="<?= $du_lieu['ngay_tra'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="tinh_trang">Tình trạng</label>
                    <select name="tinh_trang" id="tinh_trang" required>
                        <?php
                        $cac_lua_chon = ["Đang cho mượn", "Đã trả", "Trả chậm", "Quá hạn trả"];
                        foreach ($cac_lua_chon as $lua_chon) {
                            $da_chon = ($du_lieu['tinh_trang'] === $lua_chon) ? "selected" : "";
                            echo "<option value=\"$lua_chon\" $da_chon>$lua_chon</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit">Lưu cập nhật</button>
            </form>
        </div>
    </div>

    <script src="/Project_QuanLyThuVien/logic_muonsach.js"></script>
    <script>
        $(document).ready(function() {
            // Tồn kho ảo để giới hạn ô nhập = Tồn kho trong DB + Số lượng đang mượn của phiếu này
            let tonKhoGioiHan = parseInt($('#ma_sach option:selected').data('tonkho')) + <?= $du_lieu['so_luong'] ?>;

            // Gọi hàm kiểm tra từ file JS chung (đảm bảo file JS dùng tên biến tiếng Việt tương ứng)
            kiemTra('#ngay_muon', '#ngay_tra', '#so_luong', tonKhoGioiHan);

            // Cập nhật lại giới hạn khi người dùng thay đổi loại sách trong danh sách
            $('#ma_sach').on('change', function() {
                let tonKhoMoi = parseInt($(this).find(':selected').data('tonkho'));

                // Nếu Admin chọn lại đúng cuốn sách cũ đang mượn
                if ($(this).val() == "<?= $du_lieu['ma_sach'] ?>") {
                    tonKhoMoi += <?= $du_lieu['so_luong'] ?>;
                }

                $('#so_luong').attr('max', tonKhoMoi);
            });

            // Kích hoạt logic ngày tháng ngay khi trang vừa tải xong
            $('#ngay_muon').trigger('change');
        });
    </script>

</body>

</html>