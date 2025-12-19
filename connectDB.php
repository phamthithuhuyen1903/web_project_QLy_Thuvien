<?php
$host = 'localhost';         
$db   = 'demo_quanlythuvien';     
$user = 'root';             
$pass = '';   

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
else {
    echo "Kết nối thành công!";
}
?>