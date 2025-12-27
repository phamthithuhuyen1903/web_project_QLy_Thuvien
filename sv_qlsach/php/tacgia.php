<?php
include '../../Connect/connect.php';

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
} else {
    $keyword = '';
}

$from = $_GET['from'] ?? '';

$sql = "select * from tac_gia where 1";
if ($keyword != "") {
    $sql .= " AND ten_tg LIKE '%$keyword%'";
}
$result = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="../css/tacgia.css">

<link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="thanhdieuhuong">
    <a href="/Project_QuanLyThuVien/phan_quyen/php/TRANGCHU.PHP" class="thanhdieuhuong_btn">
        <i class="fas fa-home"></i> Trang Chủ
    </a>
    <span class="thanhdieuhuong_separator">›</span>
    <a href="/Project_QuanLyThuVien/sv_qlsach/php/tacgia.php" class="thanhdieuhuong_btn active">
        <i class="fas fa-user"></i> Tác giả
    </a>
</div>

<form method="GET" action="" style="margin-bottom:15px;">
    <input type="hidden" name="id" value="tacgia">
    <input type="text" name="keyword"
        placeholder="Tìm theo tên tác giả..."
        value="<?php echo htmlspecialchars($keyword) ?>">
    <button type="submit">🔍 Tìm</button>
</form>

<div class="container">


    <?php while ($tacgia = mysqli_fetch_assoc($result)) { ?>

        <div class="tacgia">
            <?php
            $hinh = (!empty($tacgia['hinh'])) ? $tacgia['hinh'] : 'no-image.png';
            ?>
            <img id="tg" src="../../image/<?php echo $hinh; ?>" width="50" height="50">

            <br />
            <b>Tác giả: </b><?php echo $tacgia['ten_tg'] ?> <br />
            <b>Quê: </b><?php echo $tacgia['que'] ?> <br>
            <a href="/Project_QuanLyThuVien/sv_qlsach/php/chitiet_tacgia.php?ma_tg=<?php echo $tacgia['ma_tg'] ?>">Xem chi tiết</a>
        </div>
    <?php } ?>
</div>