<?php
require '../connectDB.php';

$ma_pm = $_GET['ma_pm'] ?? null;
$message = "";

if ($ma_pm) {
    // Lấy dữ liệu phiếu mượn
    $sql = "
        SELECT 
            pm.ma_pm,
            pm.ma_sv   AS pm_ma_sv,
            pm.ma_sach AS pm_ma_sach,
            pm.ngay_muon,
            pm.ngay_tra,
            pm.tinh_trang,
            sv.ma_sv   AS sv_ma_sv,
            sv.ho_ten  AS sv_ho_ten,
            s.ten_sach AS sach_ten_sach 
        FROM phieu_muon pm
        JOIN sinh_vien sv ON pm.ma_sv = sv.ma_sv
        JOIN sach s ON pm.ma_sach = s.ma_sach
        WHERE pm.ma_pm = ?
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ma_pm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "<script>alert('Không tìm thấy phiếu mượn với mã: $ma_pm'); window.location.href='admin_giaodien.php';</script>";
        exit;
    }

    // Lấy danh sách tất cả sách
    $sachs = [];
    $res_sach = mysqli_query($conn, "SELECT ma_sach, ten_sach FROM sach");
    if ($res_sach) {
        while ($row = mysqli_fetch_assoc($res_sach)) {
            $sachs[] = $row;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ngaytra   = $_POST['ngay_tra'];
        $tinhtrang = $_POST['tinh_trang'];
        $ma_sach   = $_POST['ma_sach'];

        $update_sql = "UPDATE phieu_muon SET ngay_tra = ?, tinh_trang = ?, ma_sach = ? WHERE ma_pm = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "ssss", $ngaytra, $tinhtrang, $ma_sach, $ma_pm);

        if (mysqli_stmt_execute($update_stmt)) {
            echo "<script>
                    alert('✔️ Cập nhật phiếu mượn thành công!');
                    window.location.href = 'admin_giaodien.php';
                </script>";
            exit;
        } else {
            echo "<script>
                    alert('❌ Lỗi khi cập nhật phiếu mượn');
                    window.location.href = 'admin_giaodien.php';
                </script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi"> 
<head>
    <meta charset="UTF-8">
    <title>Cập nhật phiếu mượn</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/update_phieumuon.css">
</head>
<body>

<?php include 'admin_menu.php'; ?>

<div class="page-wrapper">
<div class="form-container">
    <h2>Cập nhật phiếu mượn</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label>Mã sinh viên</label>
            <input type="text" value="<?= htmlspecialchars($data['sv_ma_sv']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Tên sinh viên</label>
            <input type="text" value="<?= htmlspecialchars($data['sv_ho_ten']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="ma_sach">Tên sách</label>
            <select name="ma_sach" id="ma_sach" required>
                <?php foreach ($sachs as $s): ?>
                    <option value="<?= $s['ma_sach'] ?>" 
                        <?= ($s['ma_sach'] == $data['pm_ma_sach']) ? "selected" : "" ?>>
                        <?= htmlspecialchars($s['ten_sach']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Ngày mượn</label>
            <input type="date" value="<?= htmlspecialchars($data['ngay_muon']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="ngay_tra">Ngày trả</label>
            <input type="date" name="ngay_tra" id="ngay_tra" value="<?= htmlspecialchars($data['ngay_tra']) ?>" required>
        </div>

        <div class="form-group">
            <label for="tinh_trang">Tình trạng</label>
            <select name="tinh_trang" id="tinh_trang" required>
                <?php
                $options = ["Đang cho mượn", "Đã trả", "Trả chậm", "Quá hạn trả"];
                foreach ($options as $opt) {
                    $selected = ($data['tinh_trang'] === $opt) ? "selected" : "";
                    echo "<option value=\"$opt\" $selected>$opt</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit">Cập nhật</button>
    </form>
</div>
</div>

</body>
</html>
