<nav class="main_menu">
    <ul>
        <li class="menu_thaxuong">
            <button class="btn_thaxuong">Quản lý mượn trả sách</button>
            <ul class="content_menuthaxuong">
                <li><a href="/Project_QuanLyThuVien/admin_qlmuontra/admin_giaodien.php">Quản lý phiếu mượn</li>
                <li>Xem lịch sử hình phạt</li>

            </ul>
        </li>
    </ul>
</nav>

<style>
    .content_menuthaxuong {
        display: none !important;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #f8f9fa;
        min-width: 220px;
        box-shadow: 0 8px 16px rgb(0, 0, 0, 0.2);
        z-index: 10;
    }

    .content_menuthaxuong.show {
        display: block !important;
    }
    .btn_thaxuong {
        background-color: #003366;
        color: white;
        padding: 14px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }
    .main_menu {
        background-color: #003366;
        font-family: Arial;
        margin: 0;
        padding: 0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    .main_menu ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    .main_menu ul li {
        position: relative;
    }

    .main_menu ul li a,
    .btn_thaxuong {
        display: block;
        color: white;
        padding: 14px 20px;
        text-decoration: none;
        font-weight: bold;
        background-color: #003366;
        border: none;
    }

    .main_menu ul li a:hover,
    .btn_thaxuong:hover {
        background-color: #0056b3;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btn_thaxuong = document.querySelector(".btn_thaxuong");
        const menu_thaxuong = document.querySelector(".content_menuthaxuong");

        menu_thaxuong.classList.remove("show");
        btn_thaxuong.addEventListener("click", function(e) {
            e.preventDefault();
            menu_thaxuong.classList.toggle("show");
        });

        document.addEventListener("click", function(e) {
            if (!btn_thaxuong.contains(e.target) && !menu_thaxuong.contains(e.target)) {
                menu_thaxuong.classList.remove("show");
            }
        });
    });
</script>