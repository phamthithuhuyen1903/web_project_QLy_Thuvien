<html>

<head>
    <title>Giao diện chính</title>
    <link href="../css/librarian.css" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Book Library</h1>
    </header>
    <nav>
        <ul class="menu">
            <li class="menuparent"> Quản lý sách
                <ul class="submenu">
                    <li><a href="danhmucsach.php" id="linkDanhMuc">Danh mục sách</a></li>
                    <li><a href="theloai.php" id="linkTheLoai">Thể loại</a></li>
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
    <!-- vùng hiển thị nội dung -->
    <main>
        <div id="mainContent"></div>
    </main>
    <script src="../js/librarian.js"></script>

</body>

</html>