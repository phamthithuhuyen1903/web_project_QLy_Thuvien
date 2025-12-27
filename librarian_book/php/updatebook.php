<?php
include '../../connect/connect.php';

/* ==== DANH SÁCH ==== */
$resLoai = mysqli_query($conn, "SELECT * FROM loai_sach");
$resTG   = mysqli_query($conn, "SELECT ma_tg, ten_tg FROM tac_gia");

/* ==== LẤY SÁCH ==== */
$book = null;
if (isset($_GET['masach'])) {
    $sql = "SELECT * FROM sach WHERE ma_sach=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_GET['masach']);
    mysqli_stmt_execute($stmt);
    $book = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

/* ==== UPDATE ==== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ma        = $_POST['masach'];
    $ten       = $_POST['tensach'];
    $ma_tg     = (int)$_POST['ma_tg'];
    $nxb       = $_POST['nxb'];
    $nam       = (int)$_POST['namxuatban'];
    $sl        = (int)$_POST['soluong'];
    $loai      = $_POST['ma_loai_sach'];
    $tinhtrang = (int)$_POST['tinh_trang'];
    $mota      = $_POST['mota'];

    if ($sl <= 0) {
        $tinhtrang = 0;
    }

    /* ==== UPLOAD ẢNH ==== */
    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $imagePath = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../../image/" . $imagePath);
    }

    if ($imagePath !== "") {
        $sql = "UPDATE sach SET ten_sach=?, ma_tg=?, nha_xb=?, nam_xb=?, so_luong=?, mo_ta=?, ma_loai_sach=?, tinh_trang=?, image=? WHERE ma_sach=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sisiississ", $ten, $ma_tg, $nxb, $nam, $sl, $mota, $loai, $tinhtrang, $imagePath, $ma);
    } else {
        $sql = "UPDATE sach SET ten_sach=?, ma_tg=?, nha_xb=?, nam_xb=?, so_luong=?, mo_ta=?, ma_loai_sach=?, tinh_trang=? WHERE ma_sach=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sisiissis", $ten, $ma_tg, $nxb, $nam, $sl, $mota, $loai, $tinhtrang, $ma);
    }

    echo mysqli_stmt_execute($stmt) ? "success" : "error";
    exit;
}
?>


<html>

<head>
    <title>Sửa sách</title>
    <link rel="stylesheet" href="../css/updatebook.css">
    <link rel="stylesheet" href="/Project_QuanLyThuVien/header.css">
    <script src="/Project_QuanLyThuVien/logic_muonsach.js"></script>
</head>

<body>

    <div class="back-button" onclick="goBack()">
        Quay lại
    </div>

    <h2>Sửa thông tin sách</h2>
    <div id="messageBox"></div>

    <form class="formupdate" method="POST" enctype="multipart/form-data">
        <div class="form-left">

            <div class="form-group">
                <label>Mã sách</label>
                <input type="text" id="masach" name="masach" readonly
                    value="<?php echo htmlspecialchars($book['ma_sach'] ?? '', ENT_QUOTES); ?>">
            </div>

            <div class="form-group">
                <label>Tên sách</label>
                <input type="text" id="tensach" name="tensach" required
                    value="<?php echo htmlspecialchars($book['ten_sach'] ?? '', ENT_QUOTES); ?>">
            </div>

            <div class="form-group">
                <label>Tác giả</label>
                <select name="ma_tg" required>
                    <option value="" disabled>-- Chọn tác giả --</option>
                    <?php
                    while ($tg = mysqli_fetch_assoc($resTG)) {
                        $selected = ($book && $book['ma_tg'] == $tg['ma_tg']) ? "selected" : "";
                        echo "<option value='{$tg['ma_tg']}' $selected>{$tg['ten_tg']}</option>";
                    }
                    ?>
                </select>
            </div>


            <div class="form-group">
                <label>NXB</label>
                <input type="text" id="nxb" name="nxb" required
                    value="<?php echo htmlspecialchars($book['nha_xb'] ?? '', ENT_QUOTES); ?>">
            </div>

            <div class="form-group">
                <label>Năm xuất bản</label>
                <select name="namxuatban" required>
                    <option value="" disabled selected hidden>-- Chọn năm --</option>
                    <?php
                    $nam_hien_tai = date("Y");
                    for ($i = $nam_hien_tai; $i >= 1900; $i--) {
                        $selected = ($book && $book['nam_xb'] == $i) ? "selected" : "";
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Số lượng</label>
                <input type="number" id="soluong" name="soluong" min="0" required
                    value="<?php echo htmlspecialchars($book['so_luong'] ?? 0, ENT_QUOTES); ?>">
            </div>

            <div class="form-group">
                <label>Thể loại</label>
                <select name="ma_loai_sach" required>
                    <option value="" disabled selected hidden>-- Chọn thể loại --</option>
                    <?php
                    if ($resLoai && mysqli_num_rows($resLoai) > 0) {
                        while ($row = mysqli_fetch_assoc($resLoai)) {
                            $selected = ($book && $book['ma_loai_sach'] == $row['ma_loai_sach']) ? "selected" : "";
                            echo "<option value='" . htmlspecialchars($row['ma_loai_sach'], ENT_QUOTES) . "' $selected>"
                                . htmlspecialchars($row['ten_loai_sach'], ENT_QUOTES) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tình trạng</label>
                <select name="tinh_trang" required>
                    <option value="" disabled selected hidden>-- Chọn tình trạng --</option>
                    <option value="1" <?php echo ($book && $book['tinh_trang'] == 1) ? "selected" : ""; ?>>Còn</option>
                    <option value="0" <?php echo ($book && $book['tinh_trang'] == 0) ? "selected" : ""; ?>>Hết</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Mô tả</label>
                <textarea id="mota" name="mota"><?php echo htmlspecialchars($book['mo_ta'] ?? '', ENT_QUOTES); ?></textarea>
            </div>

        </div>

        <!-- BÊN PHẢI -->
        <div class="form-right">
            <button type="button" class="btnupload" id="btnUpload">Upload</button>
            <input type="file" id="fileInput" name="image" accept="image/*" style="display:none;">

            <div class="image">
                <?php if (!empty($book['image'])): ?>
                    <img src="../../image/<?php echo htmlspecialchars(basename($book['image']), ENT_QUOTES); ?>" width="100">
                <?php endif; ?>
            </div>

            <button type="submit" class="btnupdate">Sửa</button>
        </div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.querySelector(".formupdate");
            const messageBox = document.getElementById("messageBox");
            const btnUpload = document.getElementById("btnUpload");
            const fileInput = document.getElementById("fileInput");

            if (!form) return;

            // ===== Xử lý nút Upload =====
            if (btnUpload && fileInput) {
                btnUpload.addEventListener("click", () => {
                    fileInput.click();
                });

                // Preview ảnh sau khi chọn
                fileInput.addEventListener("change", e => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            let imgPreview = document.querySelector(".image img");
                            if (imgPreview) {
                                imgPreview.src = ev.target.result;
                            } else {
                                const newImg = document.createElement("img");
                                newImg.src = ev.target.result;
                                newImg.style.maxWidth = "100%";
                                newImg.style.maxHeight = "100%";
                                document.querySelector(".image").appendChild(newImg);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // ===== Xử lý submit form =====
            form.addEventListener("submit", e => {
                e.preventDefault();
                const formData = new FormData(form);

                fetch("", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.text())
                    .then(data => {
                        messageBox.style.display = "block";
                        messageBox.className = ""; // reset class

                        if (data.trim() === "success") {
                            messageBox.innerText = "Cập nhật thành công!";
                            messageBox.classList.add("success");
                            setTimeout(() => {
                                window.location.href = "danhmucsach.php";
                            }, 1500);
                        } else {
                            messageBox.innerText = "Cập nhật thất bại!";
                            messageBox.classList.add("error");
                        }
                    })
                    .catch(err => {
                        messageBox.style.display = "block";
                        messageBox.className = "";
                        messageBox.innerText = "Có lỗi xảy ra: " + err.message;
                        messageBox.classList.add("error");
                    });
            });

            // Thêm đoạn này vào bên trong DOMContentLoaded
            const soluongInput = document.getElementById("soluong");
            const tinhtrangSelect = document.querySelector("select[name='tinh_trang']");

            if (soluongInput && tinhtrangSelect) {
                soluongInput.addEventListener("input", () => {
                    if (parseInt(soluongInput.value) <= 0) {
                        tinhtrangSelect.value = "0"; // Tự động chọn "Hết"
                    } else if (parseInt(soluongInput.value) > 0 && tinhtrangSelect.value === "0") {
                        // Nếu tăng số lượng lên mà tình trạng đang là "Hết", gợi ý chuyển sang "Còn"
                        tinhtrangSelect.value = "1";
                    }
                });
            }
        });
    </script>
</body>

</html>