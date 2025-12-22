<html>

<head>
    <title>Giao diện chính</title>
    <link href="index.css" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Book Library</h1>
    </header>
    <nav>
        <ul class="menu">
            <li class="menuparent"> Quản lý sách
                <ul class="submenu">
                    <li><a href="../librarian_book/php/danhmucsach.php" target="contentFrame" class="loadPage">Danh mục sách</a></li>
                    <li><a href="../librarian_book/php/theloai.php" target="contentFrame" class="loadPage">Thể loại</a></li>


                </ul>
            </li>
            <li>Quản lý mượn trả sách</li>
            <li class="menuparent">Quản lý tài khoản
                <ul class="submenu">
                    <li>Tài khoản sinh viên</li>
                    <li>Admin</li>
                </ul>
            </li>
        </ul>
    </nav>
    <section>
        <iframe id="contentFrame" name="contentFrame" src="" width="100%" height="600px" style="border:none;"></iframe>
        <!-- <div id="content" style="min-height:600px;"></div> -->
    </section>

    <script src="index.js"></script>
</body>

</html>