<?php
$conn = mysqli_connect("localhost", "root", "", "demo_quanlythuvdien");
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
