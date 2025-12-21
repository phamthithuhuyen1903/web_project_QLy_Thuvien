<?php include("connect.php"); ?>
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
            <li class="menuparent">Danh mục sách
                <ul class="submenu">
                    <li><a href="index.php?id=theloai">Thể loại</a></li>
                    <li><a href="index.php?id=tacgia">Tác giả</a></li>
                </ul>
            </li>

            <li class="menuparent"><a href="index.php?id=yeuthich">Mục yêu thích</a>
            </li>
        </ul>
    </nav>


    <!-- Nội dung -->
    <div class="content">
        <?php include("content.php"); ?>
    </div>

</body>

</html>