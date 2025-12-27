<?php
session_start();

include '../../Connect/connect.php';

if (empty($_GET['ms']) || empty($_GET['ml'])) {
    echo "Thiếu tham số!";
    exit;
}

$ma_loai = mysqli_real_escape_string($conn, trim($_GET['ml']));
$ma_sach = mysqli_real_escape_string($conn, trim($_GET['ms']));

$sql = "SELECT s.*, tg.ten_tg FROM sach s JOIN tac_gia tg ON s.ma_tg = tg.ma_tg WHERE s.ma_sach = '$ma_sach'";

$result = mysqli_query($conn, $sql);
$sach = mysqli_fetch_assoc($result);

if (!$sach) {
    echo "Sách không tồn tại!";
    exit;
}

// xác định tình trạng sách
if ($sach['so_luong'] > 0) {
    $tinh_trang_sach = 'Còn';
    $muon = true;
} else {
    $tinh_trang_sach = 'Hết';
    $muon = false;
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết sách - <?php echo $sach['ten_sach'] ?></title>
    <link rel="stylesheet" href="../css/chitiet_sach.css">

    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
    <script src="/Project_QuanLyThuVien/logic_muonsach.js"></script>
</head>

<body>

    <!-- <a href="theloai.php?ml=<?= $ma_loai ?>" class="back-button">
        Quay lại
    </a> -->

    <div class="chitiet-tl">
        <h2><?php echo $sach['ten_sach'] ?></h2>
        <div class="image">
            <?php
            $hinh = 'no-image.png';
            if (isset($sach['image']) && $sach['image'] != '') {
                $hinh = $sach['image'];
            }
            ?>
            <img src="../../image/<?php echo $hinh; ?>" alt="Hình minh họa" />
        </div>
        <p><strong>Tác giả:</strong> <?php echo $sach['ten_tg'] ?></p>
        <p><strong>Nhà xuất bản:</strong> <?php echo $sach['nha_xb'] ?></p>
        <p><strong>Năm xuất bản:</strong> <?php echo $sach['nam_xb'] ?></p>
        <p><strong>Số lượng:</strong> <?php echo $sach['so_luong'] ?></p>
        <p><strong>Tình trạng:</strong> <?php echo $tinh_trang_sach; ?></p>
        <p><strong>Mô tả:</strong> <?php echo $sach['mo_ta'] ?></p>

        <?php
        // Hiển thị nút mượn nếu còn sách, ngược lại hiển thị hết
        if ($muon) { ?>
            <a href="muon_sach.php?ml=<?= $ma_loai ?>&ms=<?= $ma_sach ?>" class="btn_muon">📚 Mượn sách</a>

        <?php } else { ?>
            <span class="het_sach">Sách đã hết</span>
        <?php }
        ?>

        <!-- Nút yêu thích -->
        <?php
        $yeuthich = false;

        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'sinh_vien') {
            $ma_sv = $_SESSION['user_id'];

            $sql = "SELECT 1 from yeu_thich where ma_sv = '$ma_sv' AND ma_sach ='$ma_sach'";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $yeuthich = true;
            }
        }
        ?>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'sinh_vien'): ?>
            <a href="yeuthich.php?ml=<?= $ma_loai ?>&ms=<?= $ma_sach ?>"
                class="btn-yeuthich <?= $yeuthich ? 'da_yeuthich' : '' ?>">
                <?= $yeuthich ? '❤️ Đã yêu thích' : '🤍 Yêu thích' ?>
            </a>
        <?php else: ?>
            <a href="../../LOGIN.html" class="btn-yeuthich">
                🤍 Yêu thích (Đăng nhập)
            </a>
        <?php endif; ?>

        <?php
        $from = $_GET['from'] ?? '';
        ?>

        <?php if ($from === 'yeuthich') { ?>
            <a href="chitiet_yeuthich.php" class="back-button">Quay lại</a>

        <?php } elseif ($from === 'theloai' && $ma_loai) { ?>
            <a href="theloai.php?ml=<?= $ma_loai ?>" class="back-button">Quay lại</a>

        <?php } elseif ($from === 'tacgia') { ?>
            <a href="tacgia.php" class="back-button">Quay lại</a>
        <?php } ?>

    </div>

</body>

</html>