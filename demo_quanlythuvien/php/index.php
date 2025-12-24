<?php
include __DIR__ . '/../../connect/connect.php';
// if (file_exists($path)) {
//     include $path;
// } else {
//     echo "❌ Không tìm thấy connect.php";
// }

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách thể loại</title>
    <link rel="stylesheet" href="../css/index.css">

</head>

<body>

    <!-- Tiêu đề chính -->
    <section class="main-header">
        <h1>Book Library</h1>
    </section>

    <!-- Menu -->
    <nav>
        <ul class="menu">
            <li><a href="index.php">Trang chủ</a></li>
            <li class="menuparent">Danh mục sách
                <ul class="submenu">
                    <li><a href="index.php?id=theloai">Thể loại</a></li>
                    <li><a href="index.php?id=tacgia">Tác giả</a></li>
                </ul>
            </li>
            <li class="menuparent"><a href="index.php?id=chitiet_yeuthich">Mục yêu thích</a>
            </li>
        </ul>
    </nav>

    <!-- Nội dung -->
    <?php if (!isset($_GET['id'])) { ?>
        <!-- TRANG CHỦ -->
        <section class="home">
            <div class="home-banner">
                <img src="../images/banner.png" alt="Thư viện sách">

                <div class="home-text">
                    <h2>📚 Kho tri thức dành cho mọi người</h2>
                    <p>Đọc sách hôm nay – Thành công ngày mai</p>
                </div>
            </div>
        </section>

    <?php } else { ?>
        <!-- TRANG CON -->
        <div class="content">
            <?php include("content.php"); ?>
        </div>
    <?php } ?>


    <footer>
        <p>© 2025 Book Library | Thiết kế bởi sinh viên CNTT</p>
    </footer>
</body>

</html>