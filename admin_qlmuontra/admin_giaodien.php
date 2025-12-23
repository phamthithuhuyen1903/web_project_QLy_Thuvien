<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý mượn trả sách</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
    <?php include 'admin_menu.php' ?>
    <div class="layout">
        <div class="tinhtrang">
            <h3>Thống kê tình trạng</h3>
            <?php include 'admin_tinhtrang.php'; ?>
        </div>

        <div class="noidung">
            <div class="timkiem">
                <h1>Quản lý phiếu mượn</h1>

                <form method="get" class="cac_tinhtrang">
                    <input type="text" name="ma_sv" placeholder="Mã sinh viên"
                        value="<?= htmlspecialchars($_GET['ma_sv'] ?? '') ?>">
                    <input type="text" name="ho_ten" placeholder="Tên sinh viên"
                        value="<?= htmlspecialchars($_GET['ho_ten'] ?? '') ?>">
                    <input type="text" name="ten_sach" placeholder="Tên sách"
                        value="<?= htmlspecialchars($_GET['ten_sach'] ?? '') ?>">

                    <div class="group_date">
                        <label for="ngaymuon">Ngày mượn:</label>
                        <input type="date" id="ngaymuon" name="ngaymuon" value="<?= $_GET['ngay_muon'] ?? '' ?>">
                    </div>

                    <div class="group_date">
                        <label for="ngaytra">Ngày trả:</label>
                        <input type="date" id="ngaytra" name="ngaytra" value="<?= $_GET['ngay_tra'] ?? '' ?>">
                    </div>

                    <button type="submit">Tìm kiếm</button>
                    <a href="add_phieumuon.php" class="add_button">+ Thêm phiếu mượn</a>
                </form>
            </div>

            <div class="bang">
                <?php
                    if(!empty($_GET['ten_sach']) || !empty($_GET['ma_sv']) || !empty($_GET['ho_ten']) || !empty($_GET['ngay_muon']) || !empty($_GET['ngay_tra'])) {
                        include 'search_phieumuon.php';
                    } else {
                        include 'loadData_phieumuon.php';
                    } 
                ?>
            </div>
        </div>
    </div>

    <script>
    function deletePhieu(ma_pm) {
        if(confirm("Bạn có chắc muốn xóa phiếu mượn này?")) {
            window.location.href = "delete_phieumuon.php?ma_pm=" + ma_pm;
        }
    }
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuTitle = document.querySelector(".menu-title");
        const submenu = document.querySelector(".submenu");

        menuTitle.addEventListener("click", function (e) {
        e.preventDefault();
        submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", function (e) {
        if (!menuTitle.contains(e.target) && !submenu.contains(e.target)) {
            submenu.style.display = "none";
        }
        });
    });
    </script>
    
</body>
</html>