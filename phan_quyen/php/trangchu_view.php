<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý thư viện - Trang chủ</title>
    <link rel="stylesheet" href="/Project_QuanLyThuVien/phan_quyen/css/trangchu.css">
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
                <h1 class='tieu_de'>Thư viện ABC</h1>
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
                        <a href="javascript::void(0)" class="dropbtn">Quản lý sách ▾</a>
                        <div class="dropdown-content">
                            <a href="../../librarian_book/php/danhmucsach.php">Danh mục sách</a>
                            <a href="../../librarian_book/php/theloai.php">Thể loại</a>
                            <a href="../../librarian_book/php/author.php">Tác giả</a>

                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="javascript::void(0)" class="dropbtn">Quản lý mượn trả ▾</a>
                        <div class="dropdown-content">
                            <a href="../../admin_qlmt/admin_qlmuontra/admin_giaodien.php">Quản lý phiếu mượn</a>

                            <a href="../../admin_qlmt/admin_qlmuontra/hinhphat/danhsach_hinhphat.php">Xem lịch sử hình phạt</a>
                        </div>
                    </div>
                    <a href="../../phan_quyen/php/lsmt_process.php">Lịch sử mượn trả sách</a>
                    <div class="dropdown">
                        <a href="javascript::void(0)" class="dropbtn">Quản lý tài khoản ▾</a>
                        <div class="dropdown-content">
                            <a href="../../ql_taikhoan/admin/php/taikhoanadmin.php">Tài khoản admin</a>
                            <a href="../../ql_taikhoan/admin/php/taikhoansv.php">Tài khoản sinh viên</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="dropdown">
                        <a href="javascript::void(0)" class="dropbtn">Danh mục sách ▾</a>
                        <div class="dropdown-content">
                            <a href="../../sv_qlsach/php/theloai.php">Thể loại</a>
                            <a href="../../sv_qlsach/php/tacgia.php">Tác giả</a>
                        </div>
                    </div>
                    <a href="../../sv_qlsach/php/chitiet_yeuthich.php">Mục yêu thích</a>
                    <a href="../../phan_quyen/php/lsmt_process.php">Lịch sử mượn trả sách</a>
                    <a href="../../ql_taikhoan/sinhvien/php/taikhoansv.php">Quản lý tài khoản</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <?php if (!in_array($page, ['phieumuon', 'hinhphat'])): ?>

        <div class="banner-container">
            <img src="../images/banner1.jpg" alt="Thư viện sách">
            <div class="banner-text">
                <h2>📚 Kho tri thức dành cho mọi người</h2>
                <p>Chào mừng <strong><?php echo isset($userName) ? $userName : 'bạn'; ?></strong></p>
            </div>
        </div>

    <?php endif; ?>

    <main class="noidung">

    </main>

    <footer>
        <p>@2025 Thư viện ABC | Thiết kế bởi sinh viên CNTT</p>
    </footer>

    <script>
        function toggleQuickMenu() {
            var menu = document.getElementById("quick-menu");
            menu.classList.toggle("show");
        }
    </script>
</body>

</html>