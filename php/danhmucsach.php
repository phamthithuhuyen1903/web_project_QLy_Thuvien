<?php
include 'connect.php';

// ==========================
// XỬ LÝ XÓA SÁCH (AJAX)
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $ma = $_POST['ma'];
    $sql = "DELETE FROM sach WHERE ma_sach = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ma);
    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit; // Dừng tại đây để không render HTML nữa
}

// ==========================
// LẤY DANH SÁCH SÁCH
// ==========================
$sql = "SELECT sach.*, loai_sach.ten_loai_sach,
        CASE WHEN sach.tinh_trang = 1 THEN 'Còn' ELSE 'Hết' END AS tinh_trang_hien_thi
        FROM sach
        JOIN loai_sach ON sach.ma_loai_sach = loai_sach.ma_loai_sach";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách sách</title>
    <link href="../css/danhmucsach.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header>
        <h1>Danh mục sách</h1>
    </header>
    <div class="button">
        <a href="addbook.php" class="add">Thêm mới</a>
        <input type="text" id="search" placeholder="Tìm kiếm ">
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
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr data-row='{$row['ma_sach']}'>";
                    echo "<td>{$row['ma_sach']}</td>";
                    echo "<td>{$row['ten_sach']}</td>";
                    $filename = trim(basename($row['image']));
                    $src = "../image/" . $filename;
                    if (!empty($filename)) {
                        echo "<td><img src='{$src}' alt='Ảnh sách' width='50'></td>";
                    } else {
                        echo "<td><img src='../image/default.jpg' alt='Ảnh mặc định' width='50'></td>";
                    }
                    echo "<td>{$row['ten_loai_sach']}</td>";
                    echo "<td>{$row['ten_tg']}</td>";
                    echo "<td>{$row['nha_xb']}</td>";
                    echo "<td>{$row['nam_xb']}</td>";
                    echo "<td>{$row['so_luong']}</td>";
                    echo "<td>{$row['tinh_trang_hien_thi']}</td>";
                    echo "<td>
                        <a href='xemdmsach.php?masach={$row['ma_sach']}' title='Xem'><i class='fa-solid fa-book-open'></i></a>
                        <a href='updatebook.php?masach={$row['ma_sach']}' title='Sửa'><i class='fa-solid fa-pen-to-square'></i></a>
                        <button class='btn-delete' data-id='{$row['ma_sach']}' title='Xóa'><i class='fas fa-trash-alt'></i></button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>Không có sách nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Hộp thông báo -->
    <div id="messageBox" class="message"></div>

    <!-- Modal xác nhận xóa -->
    <div class="modal" id="deleteModal">
        <div class="modal-box">
            <h2>Bạn có chắc chắn muốn xóa sách này?</h2>
            <input type="hidden" id="deleteId">
            <div class="modal-actions">
                <button class="btn-yes" id="btnYes">Yes</button>
                <button class="btn-no" id="btnNo">No</button>
            </div>
        </div>
    </div>

    <!-- Gọi file JS -->
    <script src="../js/danhmucsach.js"></script>
</body>

</html>