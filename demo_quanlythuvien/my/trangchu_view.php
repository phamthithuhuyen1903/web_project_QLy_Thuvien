<?php
if (!isset($userName) || !isset($userRole)) {
    header("Location: LOGIN.HTML");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý thư viện - Trang chủ</title>
    <link rel="stylesheet" href="trangchu.css">
</head>

<body>
    <header>
        <div class="header-content">
            <div class="header-left">
                <div class="menu-icon" onclick="toggleQuickMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <h1>Thư viện ABC</h1>
            </div>

            <div class="user-control">
                <span>Chào, <strong><?php echo $userName; ?></strong></span>
                <a href="LOGOUT.PHP" class="btn-logout">Đăng xuất</a>
            </div>
        </div>

        <nav id="quick-menu" class="quick-menu">
            <div class="menu-items">
                <?php if ($userRole == 'admin'): ?>
                    <div class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Quản lý sách ▾</a>
                        <div class="dropdown-content">
                            <a href="danhmucsach.php">Danh mục sách</a>
                            <a href="theloai.php">Thể loại</a>
                            <a href="author.php">Tác giả</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Quản lý mượn trả ▾</a>
                        <div class="dropdown-content">
                            <a href="javascript:void(0)" onclick="hienQuanLyMuontra()">Quản lý phiếu mượn</a>
                            <a href="javascript:void(0)">Xem lịch sử hình phạt</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Quản lý tài khoản ▾</a>
                        <div class="dropdown-content">
                            <a href="javascript:void(0)" onclick="hienQuanLyTaikhoanthuthu()">Tài khoản admin</a>
                            <a href="javascript:void(0)" onclick="hienQuanLyTaikhoansinhvien()">Tài khoản sinh viên</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Danh mục sách ▾</a>
                        <div class="dropdown-content">
                            <a href="?controller=theloai">Thể loại</a>
                            <a href="?controller=tacgia">Tác giả</a>
                        </div>
                    </div>
                    <a href="?controller=yeuthich">Mục yêu thích</a>
                    <a href="lsmt_process.php">Lịch sử mượn trả sách</a>
                    <a href="?controller=taikhoansv">Quản lý tài khoản</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="banner-container">
        <?php
        if (isset($_GET['controller'])) {
            $controller = $_GET['controller'];

            if ($controller == "theloai") {
                include_once __DIR__ . '/../php/theloai.php';
            }
            if ($controller == "tacgia") {
                include_once __DIR__ . '/../php/tacgia.php';
            }
            if ($controller == "yeuthich") {
                include_once __DIR__ . '/../php/chitiet_yeuthich.php';
            }
            if ($controller == "taikhoansv") {
                include_once __DIR__ . '/../webthuvien/sinhvien/php/taikhoansv.php';
            }
            if ($controller == "suasv") {
                include_once __DIR__ . '/../webthuvien/sinhvien/php/suasv.php';
            }
        } else {
        ?>
            <img src="banner1.jpg" alt="Thư viện sách">
            <div class="banner-text">
                <h2>📚 Kho tri thức dành cho mọi người</h2>
                <p>
                    Chào mừng
                    <strong><?php echo isset($userName) ? $userName : 'bạn'; ?></strong>
                    đến với hệ thống Thư viện ABC
                </p>
            </div>
        <?php
        }
        ?>
    </div>

    <main class="noidung">

    </main>

    <footer>
        <p>@2025 Thư viện ABC | Thiết kế bởi sinh viên CNTT</p>
    </footer>

    <script src="js/danhmucsach.js"></script>
    <script>
        function toggleQuickMenu() {
            var menu = document.getElementById("quick-menu");
            menu.classList.toggle("show");
        }
    </script>
</body>

</html>