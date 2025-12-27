<?php
include '../../connect/connect.php';

// Lấy danh sách thể loại & tác giả
$result = mysqli_query($conn, "SELECT * FROM loai_sach");
$result_tg = mysqli_query($conn, "SELECT ma_tg, ten_tg FROM tac_gia");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_sach      = trim($_POST['ma_sach']);
    $ten_sach     = trim($_POST['ten_sach']);
    $ma_tg        = $_POST['ma_tg'];
    $nha_xb       = trim($_POST['nha_xb']);
    $nam_xb       = $_POST['nam_xb'];
    $so_luong     = (int)$_POST['so_luong'];
    $ma_loai_sach = $_POST['ma_loai_sach'];
    $mo_ta        = trim($_POST['mo_ta']);

    // Tự động xác định tình trạng để lưu vào DB
    $tinh_trang   = ($so_luong > 0) ? 1 : 0;

    $image = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === 0) {
        $filename = time() . "_" . basename($_FILES["hinhanh"]["name"]);
        if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], "../../image/" . $filename)) {
            $image = $filename;
        }
    }

    $stmt_check = mysqli_prepare($conn, "SELECT 1 FROM sach WHERE ma_sach=? LIMIT 1");
    mysqli_stmt_bind_param($stmt_check, "s", $ma_sach);
    mysqli_stmt_execute($stmt_check);
    if (mysqli_num_rows(mysqli_stmt_get_result($stmt_check)) > 0) {
        echo "duplicate";
        exit;
    }

    $sql_insert = "INSERT INTO sach (ma_sach, ten_sach, ma_tg, nha_xb, nam_xb, so_luong, ma_loai_sach, mo_ta, image, tinh_trang)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt, "ssisiisssi", $ma_sach, $ten_sach, $ma_tg, $nha_xb, $nam_xb, $so_luong, $ma_loai_sach, $mo_ta, $image, $tinh_trang);

    echo mysqli_stmt_execute($stmt) ? "success" : "error";
    mysqli_close($conn);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Thêm mới sách</title>
    <link rel="stylesheet" href="../css/addbook.css">
    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
    <script src="/Project_QuanLyThuVien/logic_muonsach.js"></script>
</head>

<body>

    <div class="back-button" onclick="goBack()">
        Quay lại
    </div>
    <h2>Thêm mới sách</h2>
    <div id="messageBox"></div>

    <form class="fromadd" method="POST" enctype="multipart/form-data">
        <div class="form-left">
            <div class="form-group">
                <label>Mã sách</label>
                <input type="text" name="ma_sach" required>
            </div>

            <div class="form-group">
                <label>Tên sách</label>
                <input type="text" name="ten_sach" required>
            </div>

            <div class="form-group">
                <label>Tác giả</label>
                <select name="ma_tg" required>
                    <option value="" disabled selected hidden>-- Chọn tác giả --</option>
                    <?php while ($tg = mysqli_fetch_assoc($result_tg)) {
                        echo "<option value='{$tg['ma_tg']}'>{$tg['ten_tg']}</option>";
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label>NXB</label>
                <input type="text" name="nha_xb">
            </div>

            <div class="form-group">
                <label>Năm xuất bản</label>
                <select name="nam_xb">
                    <option value="" disabled selected hidden>-- Chọn năm --</option>
                    <?php
                    $nam_hien_tai = date("Y");
                    for ($i = $nam_hien_tai; $i >= 1900; $i--) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Số lượng</label>
                <input type="number" name="so_luong" value="0" min="0">
            </div>

            <div class="form-group">
                <label>Thể loại</label>
                <select name="ma_loai_sach" required>
                    <option value="" disabled selected hidden>-- Chọn thể loại --</option>
                    <?php while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['ma_loai_sach']}'>{$row['ten_loai_sach']}</option>";
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tình trạng (Tự động)</label>
                <input type="text" id="display_tinh_trang" value="Hết" readonly
                    style="background-color: #eee; font-weight: bold; color: red; border: 1px solid #ccc;">
            </div>

            <div class="form-group full">
                <label>Mô tả</label>
                <textarea name="mo_ta"></textarea>
            </div>
        </div>

        <div class="form-right">
            <button type="button" class="btnupload" id="btnUpload">Upload ảnh</button>
            <div class="image">
                <input type="file" id="hinhanh" name="hinhanh" accept="image/*" style="display:none;">
                <div id="preview"></div>
            </div>
            <button type="submit" class="btnthem">Thêm sách</button>
        </div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnUpload = document.getElementById("btnUpload");
            const inputFile = document.getElementById("hinhanh");
            const preview = document.getElementById("preview");
            const formAdd = document.querySelector(".fromadd");
            const soLuongInput = document.querySelector("input[name='so_luong']");
            const displayTinhTrang = document.getElementById("display_tinh_trang");

            // Cập nhật trạng thái hiển thị ngay khi gõ số lượng
            soLuongInput.addEventListener("input", function() {
                const val = parseInt(this.value) || 0;
                if (val > 0) {
                    displayTinhTrang.value = "Còn";
                    displayTinhTrang.style.color = "green";
                } else {
                    displayTinhTrang.value = "Hết";
                    displayTinhTrang.style.color = "red";
                }
            });

            btnUpload.addEventListener("click", () => inputFile.click());

            inputFile.addEventListener("change", function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => preview.innerHTML = `<img src="${e.target.result}" width="120">`;
                    reader.readAsDataURL(file);
                }
            });

            formAdd.addEventListener("submit", function(e) {
                e.preventDefault();
                fetch("", {
                        method: "POST",
                        body: new FormData(this)
                    })
                    .then(res => res.text())
                    .then(data => {
                        const msg = document.getElementById("messageBox");
                        msg.style.display = "block";
                        const result = data.trim();
                        if (result === "success") {
                            msg.textContent = "Thêm thành công!";
                            msg.className = "success";
                            setTimeout(() => window.location.href = "danhmucsach.php", 1200);
                        } else if (result === "duplicate") {
                            msg.textContent = "Mã sách đã tồn tại!";
                            msg.className = "error";
                        } else {
                            msg.textContent = "Lỗi hệ thống!";
                            msg.className = "error";
                        }
                    });
            });
        });
    </script>
</body>

</html>