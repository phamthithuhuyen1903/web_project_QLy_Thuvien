<?php
include("connect.php");

if (isset($_GET['ml'])) {
    $ma_loai = $_GET['ml'];
} else {
    $ma_loai = null;
}

if (isset($_GET['ms'])) {
    $ma_sach = $_GET['ms'];
} else {
    $ma_sach = null;
}

if (!$ma_sach || !$ma_loai) {
    header("Location: index.php");
    exit;
}

// kiểm tra đã yêu thích chưa
$sql = "SELECT * FROM yeu_thich WHERE ma_sach='$ma_sach'";
$check = mysqli_query($conn, $sql);

if (mysqli_num_rows($check) > 0) {
    $sql_1 = "DELETE FROM yeu_thich WHERE ma_sach='$ma_sach'";
    mysqli_query($conn, $sql_1);
} else {
    $sql_2 = "INSERT INTO yeu_thich(ma_loai_sach, ma_sach) VALUES('$ma_loai', '$ma_sach')";
    mysqli_query($conn, $sql_2);
}

header("Location: chitiet_sach.php?ml=$ma_loai&ms=$ma_sach");

exit;
