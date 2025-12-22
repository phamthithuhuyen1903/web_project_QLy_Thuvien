<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thư viện - Trang chủ</title>
    <link rel="stylesheet" href="css/trangchu.css">
</head>
<body>
    <<header>
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
                <a href="javascript:void(0)" onclick="hienQuanLySach()">Quản lý sách</a>
                <a href="javascript:void(0)" onclick="hienDanhMucSach()">Danh mục sách</a>
                <a href="lsmt_process.php">Lịch sử mượn trả sách</a>
                <a href="javascript:void(0)" onclick="hienQuanLyThuThu()">Quản lý tài khoản thủ thư</a>
                <a href="javascript:void(0)" onclick="hienQuanLySinhVien()">Quản lý tài khoản sinh viên</a>
            <?php else: ?>
                <a href="javascript:void(0)" onclick="hienDanhMucSach()">Danh mục sách</a>
                <a href="lsmt_process.php">Lịch sử mượn trả sách</a>
                <a href="javascript:void(0)" onclick="hienQuanLyCaNhan()">Quản lý tài khoản</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

    <main class="noidung">
        <h2>Chào mừng bạn đến với hệ thống thư viện</h2>
        <p>Đây là hệ thống mượn - trả sách dành cho <?php echo ($userRole == 'admin') ? 'Quản trị viên' : 'Sinh viên'; ?>.</p>
    </main>

    <footer>
        <p>@Thư viện ABC - Liên hệ: 1900019100</p>
    </footer>
    <script src="js/danhmucsach.js"></script>
</body>
</html>