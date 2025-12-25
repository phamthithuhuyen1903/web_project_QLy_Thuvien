<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    switch ($id) {
        case 'theloai':
            include __DIR__ . '/theloai.php';
            break;

        case 'tacgia':
            include __DIR__ . '/tacgia.php';
            break;

        case 'yeuthich':
            include __DIR__ . '/yeuthich.php';
            break;

        case 'chitiet_yeuthich':
            include __DIR__ . '/chitiet_yeuthich.php';
            break;
        default:
            echo "<p>Không tìm thấy nội dung</p>";
    }
}
