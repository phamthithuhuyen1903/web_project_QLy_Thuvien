<?php
include 'connect.php';

/* THÊM LOẠI SÁCH */
if (isset($_POST['action']) && $_POST['action'] == "add") {

    $ma = trim($_POST['ma']);
    $ten = trim($_POST['ten']);

    if ($ma == "" || $ten == "") {
        echo "empty";
        exit;
    }

    $check = mysqli_query($conn, "SELECT * FROM loai_sach WHERE ma_loai_sach = '$ma'");
    if (mysqli_num_rows($check) > 0) {
        echo "duplicate";
        exit;
    }

    mysqli_query($conn, "INSERT INTO loai_sach VALUES ('$ma', '$ten')");
    echo "success";
    exit;
}
/* SỬA LOẠI SÁCH */
if (isset($_POST['action']) && $_POST['action'] == "update") {

    $ma = $_POST['ma'];
    $ten = $_POST['ten'];
    $sql = "UPDATE loai_sach 
            SET ten_loai_sach = '$ten' 
            WHERE ma_loai_sach = '$ma'";
    mysqli_query($conn, $sql);
    echo "success";
    exit;
}

/* XOÁ LOẠI SÁCH */
if (isset($_POST['action']) && $_POST['action'] == "delete") {
    $ma = mysqli_real_escape_string($conn, $_POST['ma']);

    $sql = "DELETE FROM loai_sach WHERE ma_loai_sach = '$ma'";
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        // Nếu không xóa được (ví dụ do ràng buộc khóa ngoại), trả về lỗi
        echo "error";
    }
    exit;
}




/* TÌM KIẾM THEO TÊN */
if (isset($_GET['action']) && $_GET['action'] == "search") {

    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

    $sql = "SELECT * FROM loai_sach 
            WHERE ten_loai_sach LIKE '%$keyword%'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "
            <tr>
                <td>{$row['ma_loai_sach']}</td>
                <td>{$row['ten_loai_sach']}</td>
                <td>
                    <button class='btn-edit' data-id='{$row['ma_loai_sach']}'>
                        <i class='fas fa-edit'></i>
                    </button>
                    <button class='btn-delete' data-id='{$row['ma_loai_sach']}'>
                        <i class='fas fa-trash-alt'></i>
                    </button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Không tìm thấy dữ liệu</td></tr>";
    }
    exit;
}

/* LOAD TABLE */
if (isset($_GET['action']) && $_GET['action'] == "load") {
    $sql = "SELECT * FROM loai_sach";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "
            <tr>
                <td>{$row['ma_loai_sach']}</td>
                <td>{$row['ten_loai_sach']}</td>
                <td>
                    <button class='btn-edit' data-id='{$row['ma_loai_sach']}'>
                        <i class='fas fa-edit'></i>
                    </button>
                    <button class='btn-delete' data-id='{$row['ma_loai_sach']}'>
                        <i class='fas fa-trash-alt'></i>
                    </button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Không có dữ liệu</td></tr>";
    }
    exit;
}
mysqli_close($conn);
?>

<html>

<head>
    <title>Thể loại sách</title>
    <link rel="stylesheet" href="../css/theloai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <header>
        <h1>Thể loại sách</h1>
    </header>
    <div class="button">
        <div class="search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search" placeholder="Tìm kiếm theo tên">
        </div>
        <button class="add">
            <i class="fas fa-plus"></i>Thêm mới
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã thể loại</th>
                <th>Tên thể loại</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="tabletheloai">

        </tbody>
    </table>

    <!-- FORM THÊM -->
    <div class="modal" id="addFormModal">
        <div class="modal-box">

            <div class="modal-header">
                <h2>Thêm loại sách</h2>
            </div>

            <div class="modal-body">
                <div class="form-message" id="addMessage"></div>

                <div class="form-group">
                    <label>Mã loại sách</label>
                    <input type="text" id="maLoai">
                </div>

                <div class="form-group">
                    <label>Tên loại sách</label>
                    <input type="text" id="tenLoai">
                </div>

                <div class="modal-actions">
                    <button class="btn-save" id="btnSave">Lưu</button>
                    <button class="btn-cancel" id="btnClose">Hủy</button>
                </div>
            </div>

        </div>
    </div>


    <!-- FORM SỬA -->
    <div class="modal" id="editFormModal">
        <div class="modal-box">

            <div class="modal-header">
                <h2>Sửa loại sách</h2>
            </div>

            <div class="modal-body">
                <div class="form-message" id="editMessage"></div>

                <div class="form-group">
                    <label>Mã loại sách</label>
                    <input type="text" id="editMaLoai" readonly>
                </div>

                <div class="form-group">
                    <label>Tên loại sách</label>
                    <input type="text" id="editTenLoai">
                </div>

                <div class="modal-actions">
                    <button class="btn-save" id="btnUpdate">Lưu</button>
                    <button class="btn-cancel" id="btnCloseEdit">Hủy</button>
                </div>
            </div>

        </div>
    </div>

    <!-- FORM XÓA -->
    <div class="confirm-box" id="deleteConfirmBox" style="display:none;">
        <div class="confirm-content">
            <p>Bạn có chắc chắn muốn xóa không?</p>
            <div class="confirm-actions">
                <button id="yesDelete">Yes</button>
                <button id="noDelete">No</button>
            </div>
        </div>
    </div>

    <!-- hộp thoại thông báo -->
    <div id="popupMessage" class="popup-message">
        <p id="popupText"></p>
        <button id="popupClose">Đóng</button>
    </div>

    <script src="../js/theloai.js"></script>

</body>

</html>