<?php
include '../../connect/connect.php';

// XOÁ TÁC GIẢ
if (isset($_POST['action']) && $_POST['action'] == "delete") {
    $ma = mysqli_real_escape_string($conn, $_POST['ma']);

    $sql = "DELETE FROM tac_gia WHERE ma_tg = '$ma'";
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// TÌM KIẾM TÁC GIẢ THEO TÊN
if (isset($_GET['action']) && $_GET['action'] == "search") {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

    $sql = "SELECT *,
                   CASE WHEN gioi_tinh = 1 THEN 'Nam' ELSE 'Nữ' END AS gioi_tinh_hien_thi
            FROM tac_gia
            WHERE LOWER(ten_tg) LIKE LOWER('%$keyword%')";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $filename = trim(basename($row['hinh']));
            $src = "../image/" . ($filename ?: "default.jpg");

            echo "<tr data-row='{$row['ma_tg']}'>
                    <td>{$row['ma_tg']}</td>
                    <td>{$row['ten_tg']}</td>
                    <td><img src='{$src}' alt='Ảnh tác giả' width='50'></td>
                    <td>{$row['gioi_tinh_hien_thi']}</td>
                    <td>{$row['ngay_sinh']}</td>
                    <td>{$row['que']}</td>
                    <td>
                        <a href='updateauthor.php?ma_tg={$row['ma_tg']}' title='Sửa' class='btn-edit'>
                            <i class='fa-solid fa-pen-to-square'></i>
                        </a>
                        <button class='btn-delete' data-id='{$row['ma_tg']}' title='Xóa'>
                            <i class='fas fa-trash-alt'></i>
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Không tìm thấy tác giả nào.</td></tr>";
    }
    exit;
}

// LOAD DANH SÁCH TÁC GIẢ
if (isset($_GET['action']) && $_GET['action'] == "load") {
    $sql = "SELECT *,
                   CASE WHEN gioi_tinh = 1 THEN 'Nam' ELSE 'Nữ' END AS gioi_tinh_hien_thi
            FROM tac_gia";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $filename = trim(basename($row['hinh']));
            $src = "../image/" . ($filename ?: "default.jpg");

            echo "<tr data-row='{$row['ma_tg']}'>
                    <td>{$row['ma_tg']}</td>
                    <td>{$row['ten_tg']}</td>
                    <td><img src='{$src}' alt='Ảnh tác giả' width='50'></td>
                    <td>{$row['gioi_tinh_hien_thi']}</td>
                    <td>{$row['ngay_sinh']}</td>
                    <td>{$row['que']}</td>
                    
                    <td>
                        <a href='updateauthor.php?ma_tg={$row['ma_tg']}' title='Sửa' class='btn-edit'>
                            <i class='fa-solid fa-pen-to-square'></i>
                        </a>
                        <button class='btn-delete' data-id='{$row['ma_tg']}' title='Xóa'>
                            <i class='fas fa-trash-alt'></i>
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Không có tác giả nào.</td></tr>";
    }
    exit;
}

mysqli_close($conn);
?>
<html>

<head>
    <title>Danh sách tác giả</title>
    <link href="../css/author.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header>
        <h2>Danh sách tác giả</h2>
    </header>

    <div class="button">
        <div class="search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search" placeholder="Tìm kiếm ">
        </div>
        <a href="addauthor.php" class="add">
            <i class="fas fa-plus"></i>Thêm mới
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã tác giả</th>
                <th>Tên tác giả</th>
                <th>Hình ảnh</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Quê quán</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="tableauthor">
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

    <!-- Gọi file JS -->
    <script src="../js/author.js"></script>
</body>

</html>