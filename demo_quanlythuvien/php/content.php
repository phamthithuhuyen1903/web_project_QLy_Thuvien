<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    switch ($id) {
        case 'theloai':
            include 'theloai.php';
            break;

        case 'tacgia':
            include 'tacgia.php';
            break;

        case 'yeuthich':
            include 'yeuthich.php';
            break;

        case 'chitiet_yeuthich':
            include 'chitiet_yeuthich.php';
            break;

        default:
            echo "<p>Không tìm thấy nội dung</p>";
    }
}
