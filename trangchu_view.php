<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thư viện - Trang chủ</title>
    <link rel="stylesheet" href="css/trangchu.css">
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
                <a href="javascript:void(0)" onclick="hienQuanLySach()">Quản lý sách</a>
                <a href="javascript:void(0)" onclick="hienDanhMucSach()">Danh mục sách</a>
                <a href="lsmt_process.php">Lịch sử mượn trả sách</a>
                <a href="javascript:void(0)" onclick="hienQuanLyThuThu()">Quản lý tài khoản thủ thư</a>
                <a href="javascript:void(0)" onclick="hienQuanLySinhVien()">Quản lý tài khoản sinh viên</a>
            <?php else: ?>
                <a href="javascript:void(0)" onclick="hienDanhMucSach()">Danh mục sách</a>
                <a href="javascript:void(0)" onclick="hienMucyeuthich()">Mục yêu thích</a>
                <a href="lsmt_process.php">Lịch sử mượn trả sách</a>
                <a href="javascript:void(0)" onclick="hienQuanLyCaNhan()">Quản lý tài khoản</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<div class="banner-container">
    <img src="./JPG/banner1.jpg" alt="Thư viện sách">
    <div class="banner-text">
        <h2>📚 Kho tri thức dành cho mọi người</h2>
        <p>Chào mừng <strong><?php echo isset($userName) ? $userName : 'bạn'; ?></strong></p>
    </div>
</div>

<main class="noidung">
    </main>

    <footer>
        <p>@2025 Thư viện ABC | Thiết kế bởi sinh viên CNTT</p>
    </footer>
    <script src="js/danhmucsach.js"></script>
    <script>
    function toggleQuickMenu() {
        // 1. Tìm thẻ menu bằng ID "quick-menu"
        var menu = document.getElementById("quick-menu");
        
        // 2. Thêm hoặc bớt class "show" vào thẻ menu
        // Khi có class "show", CSS sẽ đổi max-height từ 0 thành 500px -> Menu hiện ra
        menu.classList.toggle("show");
    }
</script>
</body>
</html>