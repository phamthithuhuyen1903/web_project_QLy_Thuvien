<?php
require_once __DIR__ . '/../../connect/connect.php';

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
} else {
    $keyword = '';
}

$sql = "select * from tac_gia where 1";
if ($keyword != "") {
    $sql .= " AND ten_tg LIKE '%$keyword%'";
}
$result = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="../css/tacgia.css">
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
            <img id="tg" src="../images/<?php echo $hinh; ?>" width="50" height="50">

            <br />
            <b>Tác giả: </b><?php echo $tacgia['ten_tg'] ?> <br />
            <b>Quê: </b><?php echo $tacgia['que'] ?> <br>
            <a href="chitiet_tacgia.php?ma_tg=<?php echo $tacgia['ma_tg'] ?>">Xem chi tiết</a>
        </div>
    <?php } ?>
</div>