<?php
include '../../Connect/connect.php';


$sql = "
SELECT 
    s.ma_sach,
    s.ma_loai_sach,   
    tg.ten_tg,
    s.ten_sach,
    ls.ten_loai_sach,
    s.nam_xb,
    s.image 
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
  <link rel="stylesheet" href="/Project_QuanLyThuVien/sv_qlsach/css/chitiet_yeuthich.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">

</head>

<body>

  <div class="thanhdieuhuong">
    <a href="/Project_QuanLyThuVien/phan_quyen/php/TRANGCHU.PHP" class="thanhdieuhuong_btn">
      <i class="fas fa-home"></i> Trang Chủ
    </a>
    <span class="thanhdieuhuong_separator">›</span>
    <a href="/Project_QuanLyThuVien/sv_qlsach/php/chitiet_yeuthich.php" class="thanhdieuhuong_btn active">
      <i class="fas fa-book"></i> Mục yêu thích
    </a>
  </div>

  <div class="container">
    <?php while ($yeuthich = mysqli_fetch_assoc($result)) { ?>

      <div class="yeuthich">
        <?php
        $hinh = 'no-image.png';
        if (isset($yeuthich['image']) && $yeuthich['image'] != '') {
          $hinh = $yeuthich['image'];
        }
        ?>
        <img src="../../image/<?php echo $hinh; ?>" alt="Hình minh họa" />
        <br />
        <b>Tên sách: </b><?php echo $yeuthich['ten_sach'] ?> <br />
        <b>Thể loại: </b><?php echo $yeuthich['ten_loai_sach'] ?> <br />
        <b>Tác giả: </b><?php echo $yeuthich['ten_tg'] ?> <br />
        <b>Năm xuất bản: </b><?php echo $yeuthich['nam_xb'] ?><br />
        <a href="/Project_QuanLyThuVien/sv_qlsach/php/chitiet_sach.php?ml=<?php echo $yeuthich['ma_loai_sach']; ?>&ms=<?php echo $yeuthich['ma_sach'] ?>">Xem chi tiết</a>
      </div>
    <?php } ?>
  </div>
</body>

</html>