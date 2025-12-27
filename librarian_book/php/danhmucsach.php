<?php
include '../../Connect/connect.php';


// XOÁ SÁCH
if (isset($_POST['action']) && $_POST['action'] === "delete") {
    $ma = mysqli_real_escape_string($conn, $_POST['ma']);

    // 1. Kiểm tra sách có trong phiếu mượn
    $kiemtra_pm = $conn->query("SELECT COUNT(*) AS total FROM phieu_muon WHERE ma_sach = '$ma'");
    $row_pm = $kiemtra_pm->fetch_assoc();

    // 2. Kiểm tra sách có trong yêu thích
    $kiemtra_yt = $conn->query("SELECT COUNT(*) AS total FROM yeu_thich WHERE ma_sach = '$ma'");
    $row_yt = $kiemtra_yt->fetch_assoc();

    if ($row_pm['total'] > 0 || $row_yt['total'] > 0) {
        // Nếu sách đang tồn tại trong phiếu mượn hoặc yêu thích => không cho xóa
        echo "not_allowed";
        exit;
    }

    // 3. Nếu không tồn tại trong phiếu mượn và yêu thích thì cho phép xóa
    $sql = "DELETE FROM sach WHERE ma_sach = '$ma'";
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}


// TÌM KIẾM SÁCH THEO TÊN
if (isset($_GET['action']) && $_GET['action'] == "search") {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

    $sql = "SELECT 
            sach.*, 
            loai_sach.ten_loai_sach,
            tac_gia.ten_tg,
            CASE WHEN sach.tinh_trang = 1 THEN 'Còn' ELSE 'Hết' END AS tinh_trang_hien_thi
        FROM sach
        JOIN loai_sach ON sach.ma_loai_sach = loai_sach.ma_loai_sach
        JOIN tac_gia ON sach.ma_tg = tac_gia.ma_tg
        WHERE LOWER(sach.ten_sach) LIKE LOWER('%$keyword%')";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $filename = trim(basename($row['image']));
            $src = "../../image/" . $filename;
            if (empty($filename)) {
                $src = "../../image/default.jpg";
            }

            echo "<tr data-row='{$row['ma_sach']}'>
                    <td>{$row['ma_sach']}</td>
                    <td>{$row['ten_sach']}</td>
                    <td><img src='{$src}' alt='Ảnh sách' width='50'></td>
                    <td>{$row['ten_loai_sach']}</td>
                    <td>{$row['ten_tg']}</td>
                    <td>{$row['nha_xb']}</td>
                    <td>{$row['nam_xb']}</td>
                    <td>{$row['so_luong']}</td>
                    <td>{$row['tinh_trang_hien_thi']}</td>
                    <td>
                        <a href='viewbook.php?masach={$row['ma_sach']}' title='Xem' class='btn-view'>
                            <i class='fa-solid fa-eye'></i>
                        </a>
                        <a href='updatebook.php?masach={$row['ma_sach']}' title='Sửa' class='btn-edit'>
                            <i class='fa-solid fa-pen-to-square'></i>
                        </a>
                        <button class='btn-delete' data-id='{$row['ma_sach']}' title='Xóa'>
                            <i class='fas fa-trash-alt'></i>
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>Không tìm thấy sách nào.</td></tr>";
    }
    exit;
}

// XEM SÁCH
if (isset($_GET['action']) && $_GET['action'] == "view" && isset($_GET['masach'])) {
    $ma = mysqli_real_escape_string($conn, $_GET['masach']);

    $sql = "SELECT 
                sach.*, 
                loai_sach.ten_loai_sach,
                tac_gia.ten_tg,
                CASE WHEN sach.tinh_trang = 1 THEN 'Còn' ELSE 'Hết' END AS tinh_trang_hien_thi
            FROM sach
            JOIN loai_sach ON sach.ma_loai_sach = loai_sach.ma_loai_sach
            JOIN tac_gia ON sach.ma_tg = tac_gia.ma_tg
            WHERE sach.ma_sach = '$ma'
            LIMIT 1";

    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    }
    exit;
}




//LOAD TABLE
if (isset($_GET['action']) && $_GET['action'] == "load") {
    $sql = "SELECT 
            sach.*, 
            loai_sach.ten_loai_sach,
            tac_gia.ten_tg,
            CASE WHEN sach.tinh_trang = 1 THEN 'Còn' ELSE 'Hết' END AS tinh_trang_hien_thi
        FROM sach
        JOIN loai_sach ON sach.ma_loai_sach = loai_sach.ma_loai_sach
        JOIN tac_gia ON sach.ma_tg = tac_gia.ma_tg";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr data-row='{$row['ma_sach']}'>";
            echo "<td>{$row['ma_sach']}</td>";
            echo "<td>{$row['ten_sach']}</td>";
            $filename = trim(basename($row['image']));
            $src = "../../image/" . $filename;
            if (!empty($filename)) {
                echo "<td><img src='{$src}' alt='Ảnh sách' width='50'></td>";
            } else {
                echo "<td><img src='../../image/default.jpg' alt='Ảnh mặc định' width='50'></td>";
            }
            echo "<td>{$row['ten_loai_sach']}</td>";
            echo "<td>{$row['ten_tg']}</td>";
            echo "<td>{$row['nha_xb']}</td>";
            echo "<td>{$row['nam_xb']}</td>";
            echo "<td>{$row['so_luong']}</td>";
            $statusClass = ($row['tinh_trang_hien_thi'] === 'Hết') ? 'status-out' : 'status-in';
            echo "<td class='$statusClass'>{$row['tinh_trang_hien_thi']}</td>";

            echo "<td>
                        <a href='danhmucsach.php?action=view&masach={$row['ma_sach']}' class='btn-view'>
                        <i class='fa-solid fa-eye'></i>
                        </a>
                        <a href='updatebook.php?masach={$row['ma_sach']}' title='Sửa' class='btn-edit'>
                        <i class='fa-solid fa-pen-to-square'></i>
                        </a>
                        <button class='btn-delete' data-id='{$row['ma_sach']}' title='Xóa'>
                        <i class='fas fa-trash-alt'></i>
                        </button>
                    </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>Không có sách nào.</td></tr>";
    }
    exit;
}
mysqli_close($conn);
?>
<html>

<head>
    <title>Danh sách sách</title>
    <link href="/Project_QuanLyThuVien/librarian_book/css/danhmucsach.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
</head>

<body>
    <div class="thanhdieuhuong">
        <a href="/Project_QuanLyThuVien/phan_quyen/php/TRANGCHU.PHP" class="thanhdieuhuong_btn">
            <i class="fas fa-home"></i> Trang Chủ
        </a>
        <span class="thanhdieuhuong_separator"></span>
        <a href="/Project_QuanLyThuVien/librarian_book/php/danhmucsach.php" class="thanhdieuhuong_btn active">
            <i class="fas fa-book"></i> Danh mục sách
        </a>
    </div>
    <header>
        <h2>Danh mục sách</h2>
    </header>

    <div class="button">
        <div class="search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search" placeholder="Tìm kiếm ">
        </div>
        <a href="addbook.php" class="add">
            <i class="fas fa-plus"></i>Thêm mới
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã sách</th>
                <th>Tên sách</th>
                <th>Hình ảnh</th>
                <th>Thể loại</th>
                <th>Tác giả</th>
                <th>NXB</th>
                <th>Năm xuất bản</th>
                <th>Số lượng</th>
                <th>Tình trạng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="tablebook">
        </tbody>
    </table>

    <!-- HỘP THOẠI XÁC NHẬN XÓA -->
    <div class="confirm-box" id="deleteConfirmBox" style="display:none;">
        <div class="confirm-content">
            <p>Bạn có chắc chắn muốn xóa không?</p>
            <div class="confirm-actions">
                <button id="yesDelete">Yes</button>
                <button id="noDelete">No</button>
            </div>
        </div>
    </div>
    <!-- HỘP THOẠI THÔNG BÁO -->
    <div id="popupMessage" class="popup-message">
        <p id="popupText"></p>
        <button id="popupClose">Đóng</button>
    </div>

    <!-- FORM XEM THÔNG TIN SÁCH -->
    <div id="viewbook" class="modal">
        <div class="content">
            <span class="btnclose" id="closeView">&times;</span>
            <h2>Thông tin Sách</h2>
            <div class="book-form">
                <div class="book-image">
                    <img id="image" src="" alt="Ảnh sách">
                </div>
                <div class="book-info">
                    <p><strong>Tên sách:</strong> <span id="tensach"></span></p>
                    <p><strong>Tác giả:</strong> <span id="tacgia"></span></p>
                    <p><strong>Nhà xuất bản:</strong> <span id="nxb"></span></p>
                    <p><strong>Xuất bản:</strong> <span id="namxb"></span></p>
                    <p><strong>Số lượng:</strong> <span id="soluong"></span></p>
                    <p><strong>Tình trạng:</strong> <span id="tinhtrang"></span></p>
                </div>
            </div>
            <div class="book-description">
                <h3>Mô tả:</h3>
                <p id="mota"></p>
            </div>
        </div>
    </div>





    <!-- Gọi file JS -->
    <script src="../js/danhmucsach.js"></script>
</body>

</html>