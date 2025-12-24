<?php
require_once './connect/connect.php';
if (!isset($_GET['id'])) {
  echo "Không lấy đc query";
  die();
}

$sql = "
SELECT 
    s.ma_sach,
    s.ma_loai_sach,   
    tg.ten_tg,
    s.ten_sach,
    ls.ten_loai_sach,
    s.nam_xb,
    s.hinh
FROM yeu_thich yt
JOIN sach s 
    ON yt.ma_sach = s.ma_sach
JOIN tac_gia tg 
    ON s.ma_tg = tg.ma_tg
JOIN loai_sach ls 
    ON s.ma_loai_sach = ls.ma_loai_sach;

";
$result = mysqli_query($conn, $sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title></title>
  <link rel="stylesheet" href="../css/chitiet_yeuthich.css" />
</head>

<body>
  <div class="container">
    <?php while ($yeuthich = mysqli_fetch_assoc($result)) { ?>

      <div class="yeuthich">
        <?php
        $hinh = 'no-image.png';
        if (isset($yeuthich['hinh']) && $yeuthich['hinh'] != '') {
          $hinh = $yeuthich['hinh'];
        }
        ?>
        <img src="../images/<?php echo $hinh; ?>" alt="Hình minh họa" />
        <br />
        <b>Tên sách: </b><?php echo $yeuthich['ten_sach'] ?> <br />
        <b>Thể loại: </b><?php echo $yeuthich['ten_loai_sach'] ?> <br />
        <b>Tác giả: </b><?php echo $yeuthich['ten_tg'] ?> <br />
        <b>Năm xuất bản: </b><?php echo $yeuthich['nam_xb'] ?><br />
        <a href="chitiet_sach.php?ml=<?php echo $yeuthich['ma_loai_sach']; ?>&ms=<?php echo $yeuthich['ma_sach'] ?>">Xem chi tiết</a>
      </div>
    <?php } ?>
  </div>
</body>

</html>