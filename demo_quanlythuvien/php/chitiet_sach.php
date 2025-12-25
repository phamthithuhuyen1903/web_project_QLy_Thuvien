<?php
require_once __DIR__ . '/../../connect/connect.php';
if (isset($_GET['ml'])) {
    $ma_loai = trim($_GET['ml']);
} else {
    $ma_loai = null;
}

// Lấy mã sách từ URL
if (isset($_GET['ms'])) {
    $ma_sach = trim($_GET['ms']);
} else {
    $ma_sach = null;
}

if (!$ma_sach) {
    echo "Không có sách được chọn!";
    exit;
}


$sql = "SELECT s.*, tg.ten_tg FROM sach s JOIN tac_gia tg ON s.ma_tg = tg.ma_tg WHERE s.ma_sach = '$ma_sach'";

$result = mysqli_query($conn, $sql);
$sach = mysqli_fetch_assoc($result);

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


</head>

<body>
    <div class="chitiet-tl">
        <form action="./yeuthich.php?action=add&ms=<?php echo $ma_sach ?>&ml=<?php echo $ma_loai ?>" method="post">
            <h2><?php echo $sach['ten_sach'] ?></h2>
            <div class="image">
                <?php
                $hinh = 'no-image.png';
                if (isset($sach['hinh']) && $sach['hinh'] != '') {
                    $hinh = $sach['hinh'];
                }
                ?>
                <img src="../images/<?php echo $hinh; ?>" alt="Hình minh họa" />
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
                <a href="muon_sach.php?ml=<?= $_GET['ml'] ?>&ms=<?php echo $sach['ma_sach']; ?>" class="btn_muon">📚 Mượn sách</a>
            <?php } else { ?>
                <span class="het_sach">Sách đã hết</span>
            <?php }
            ?>

            <?php
            $yeuthich = false;
            $sql = "SELECT * from yeu_thich where ma_sach ='$ma_sach'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $yeuthich = true;
            }
            ?>
            <a href="yeuthich.php?ms=<?php echo $ma_sach ?>&ml=<?php echo $ma_loai ?>"
                class="btn-yeuthich <?php echo $yeuthich ? 'da_yeuthich' : '' ?>">
                <?php echo $yeuthich ? '❤️ Đã yêu thích' : '🤍 Yêu thích' ?>
            </a>

        </form>

    </div>

</body>

</html>