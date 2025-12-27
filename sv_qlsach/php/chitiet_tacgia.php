<?php
include '../../Connect/connect.php';

if (empty($_GET['ma_tg'])) {
    echo "Không có tác giả!";
    exit;
}
$ma_tg = mysqli_real_escape_string($conn, $_GET['ma_tg']);

$sql_tg = "SELECT * FROM tac_gia WHERE ma_tg = '$ma_tg'";
$result = mysqli_query($conn, $sql_tg);
$tacgia = mysqli_fetch_assoc($result);

if (!$tacgia) {
    echo "Tác giả không tồn tại!";
    exit;
}
?>

<?php
$sql_sach = "
    SELECT ma_sach, ten_sach, ma_loai_sach
    FROM sach
    WHERE ma_tg = '$ma_tg'
";

$result_sach = mysqli_query($conn, $sql_sach);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tacgia['ten_tg'] ?></title>
    <link rel="stylesheet" href="../css/chitiet_tacgia.css">

    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
</head>

<body>
    <a href="tacgia.php?ml=<?= $ma_tg ?>" class="back-button">
        Quay lại
    </a>

    <div class="chitiet-tg">
        <div class="tg-images">
            <?php
            $hinh = 'no-image.png';
            if (isset($tacgia['hinh']) && $tacgia['hinh'] != '') {
                $hinh = $tacgia['hinh'];
            }
            ?>
            <img src="../../image/<?php echo $hinh; ?>" alt="Hình minh họa" />

        </div>
        <div>
            <p><strong>Họ và tên:</strong><?php echo $tacgia['ten_tg'] ?></p>
            <p><strong>Ngày sinh:</strong><?php echo $tacgia['ngay_sinh'] ?></p>
            <p><strong>Giới tính:</strong>
                <?php echo ($tacgia['gioi_tinh'] == 0) ? 'Nam' : 'Nữ'; ?>
            </p>
            <p><strong>Quê quán:</strong><?php echo $tacgia['que'] ?></p>
            <p><strong>Tiểu sử:</strong><?php echo nl2br($tacgia['tieu_su']) ?></p>
            <p><strong>Tác phẩm:</strong></p>
            <ul>
                <?php while ($s = mysqli_fetch_assoc($result_sach)) { ?>
                    <li>
                        <!-- <a href="chitiet_sach.php?ms=<?php echo $s['ma_sach']; ?>">
                            <?php echo $s['ten_sach']; ?>
                        </a> -->
                        <a href="chitiet_sach.php?from=tacgia&ml=<?= $s['ma_loai_sach'] ?>&ms=<?= $s['ma_sach'] ?>">
                            <?= $s['ten_sach'] ?>
                        </a>

                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</body>

</html>