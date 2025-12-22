<?php
include '../../connect/connect.php';

// Lấy danh sách thể loại
$sql = "SELECT * FROM loai_sach";
$result = mysqli_query($conn, $sql);
// Lấy danh sách tác giả
$sql_tg = "SELECT ma_tg, ten_tg FROM tac_gia";
$result_tg = mysqli_query($conn, $sql_tg);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_sach       = trim($_POST['ma_sach']);
    $ten_sach      = trim($_POST['ten_sach']);
    $ma_tg        = trim($_POST['ma_tg']);
    $nha_xb        = trim($_POST['nha_xb']);
    $nam_xb        = trim($_POST['nam_xb']);
    $so_luong      = trim($_POST['so_luong']);
    $ma_loai_sach  = trim($_POST['ma_loai_sach']);
    $mo_ta         = trim($_POST['mo_ta']);
    $tinh_trang    = trim($_POST['tinh_trang']);

    // Upload ảnh
    $image = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === 0) {
        $target_dir  = "../image/";
        $filename    = basename($_FILES["hinhanh"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file)) {
            $image = $filename;
        }
    }

    // Kiểm tra trùng mã sách
    $sql_check   = "SELECT 1 FROM sach WHERE ma_sach='$ma_sach' LIMIT 1";
    $result_check = mysqli_query($conn, $sql_check);
    if ($result_check && mysqli_num_rows($result_check) > 0) {
        echo "duplicate";
        mysqli_close($conn);
        exit;
    }

    // Thêm vào DB
    $sql_insert = "INSERT INTO sach 
    (ma_sach, ten_sach, ma_tg, nha_xb, nam_xb, so_luong, ma_loai_sach, mo_ta, image, tinh_trang)
    VALUES 
    ('$ma_sach', '$ten_sach', '$ma_tg', '$nha_xb', '$nam_xb', '$so_luong', '$ma_loai_sach', '$mo_ta', '$image', '$tinh_trang')";

    if (mysqli_query($conn, $sql_insert)) {
        echo "success";
    } else {
        echo "error";
    }
    mysqli_close($conn);
    exit;
}
?>

<html>

<head>
    <title>Thêm mới sách</title>
    <link rel="stylesheet" href="../css/addbook.css">

</head>

<body>
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
                    <?php
                    while ($tg = mysqli_fetch_assoc($result_tg)) {
                        echo "<option value='{$tg['ma_tg']}'>{$tg['ten_tg']}</option>";
                    }
                    ?>
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
                <input type="number" name="so_luong" value="0">
            </div>

            <div class="form-group">
                <label>Thể loại</label>
                <select name="ma_loai_sach" required>
                    <option value="" disabled selected hidden>-- Chọn thể loại --</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['ma_loai_sach'] . "'>" . $row['ten_loai_sach'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tình trạng</label>
                <select name="tinh_trang" required>
                    <option value="" disabled selected hidden>--Chọn tình trạng--</option>
                    <option value="1">Còn</option>
                    <option value="0">Hết</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Mô tả</label>
                <textarea name="mo_ta"></textarea>
            </div>
        </div>

        <div class="form-right">
            <button type="button" class="btnupload" id="btnUpload">Upload</button>
            <div class="image">
                <input type="file" id="hinhanh" name="hinhanh" accept="image/*" class="hidden">
                <div id="preview"></div>
            </div>

            <button type="submit" class="btnthem">Thêm</button>
        </div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnUpload = document.querySelector(".btnupload");
            const inputFile = document.getElementById("hinhanh");
            const preview = document.getElementById("preview");
            const messageBox = document.getElementById("messageBox");
            const formAdd = document.querySelector(".fromadd");

            //ẩn thông báo khi người dùng nhập lại
            formAdd.querySelectorAll("input, select, textarea").forEach(element => {
                element.addEventListener("input", () => {
                    messageBox.style.display = "none";
                    messageBox.textContent = "";
                    messageBox.className = "";
                });
            });
            btnUpload.addEventListener("click", function() {
                inputFile.click();
            });

            inputFile.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = "<img src='" + e.target.result + "' width='120'>";
                    };
                    reader.readAsDataURL(file);
                }
            });

            formAdd.addEventListener("submit", function(event) {
                event.preventDefault();

                // // // reset thông báo trước khi gửi
                // messageBox.style.display = "none";
                // messageBox.textContent = "";
                // messageBox.className = "";

                const formData = new FormData(formAdd);

                fetch("addbook.php", { // gọi trực tiếp file xử lý PHP
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        const result = data.trim(); // loại bỏ khoảng trắng, xuống dòng

                        if (result === "success") {
                            messageBox.textContent = "Thêm thành công!";
                            messageBox.className = "success";
                            messageBox.style.display = "block";

                            setTimeout(() => {
                                window.location.href = "danhmucsach.php";
                            }, 1500);
                        } else if (result === "duplicate") {
                            messageBox.textContent = "Mã sách đã tồn tại, vui lòng nhập lại!";
                            messageBox.className = "error";
                            messageBox.style.display = "block";
                        } else {
                            messageBox.textContent = "Thêm sách không thành công!";
                            messageBox.className = "error";
                            messageBox.style.display = "block";
                        }
                    })
                    .catch(error => {
                        messageBox.textContent = "Có lỗi xảy ra khi gửi dữ liệu!";
                        messageBox.className = "error";
                        messageBox.style.display = "block";
                        console.error("Lỗi:", error);
                    });
            });
        });
    </script>
</body>

</html>