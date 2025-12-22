<?php
include '../../connect/connect.php';

// Lấy thông tin sách cần sửa
$author = null;
if (isset($_GET['ma_tg'])) {
    $ma = (int)$_GET['ma_tg'];
    $sql = "SELECT * FROM tac_gia WHERE ma_tg = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $ma);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $author = mysqli_fetch_assoc($result);
    if (!$author) {
        echo ("Không tìm thấy tác giả nào!");
    }
}

// Xử lý form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma = (int) $_POST['ma_tg'];
    $ten_tg = $_POST['ten_tg'];
    $gioi_tinh = (int)$_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $que = $_POST['que'];
    $tieu_su = $_POST['tieu_su'];

    // Xử lý upload ảnh
    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target = "../image/" . $filename;
        $fileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imagePath = $filename;
            }
        }
    }
    //khi có ảnh mới
    if ($imagePath !== "") {
        $sql = "UPDATE tac_gia 
                SET ten_tg=?, gioi_tinh=?, ngay_sinh=?, que=?, tieu_su=?, hinh=? 
                WHERE ma_tg=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sissssi",
            $ten_tg,
            $gioi_tinh,
            $ngay_sinh,
            $que,
            $tieu_su,
            $imagePath,
            $ma
        );
    } else {
        $sql = "UPDATE tac_gia 
                SET ten_tg=?, gioi_tinh=?, ngay_sinh=?, que=?, tieu_su=? 
                WHERE ma_tg=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sisssi",
            $ten_tg,
            $gioi_tinh,
            $ngay_sinh,
            $que,
            $tieu_su,
            $ma
        );
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($conn);
    }
    exit;
}
?>

<html>

<head>
    <title>Sửa tác giả</title>
    <link rel="stylesheet" href="../css/updateauthor.css">
</head>

<body>
    <h2>Sửa thông tin tác giả</h2>
    <div id="messageBox"></div>

    <form class="formupdate" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="ma_tg"
            value="<?php echo htmlspecialchars($author['ma_tg'] ?? '', ENT_QUOTES); ?>">

        <div class="form-left">

            <div class="form-group">
                <label>Tên tác giả</label>
                <input type="text" id="ten_tg" name="ten_tg" required
                    value="<?php echo htmlspecialchars($author['ten_tg'] ?? '', ENT_QUOTES); ?>">
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <select name="gioi_tinh" required>
                    <option value="" disabled selected hidden>-- Chọn giới tính --</option>
                    <option value="1" <?php echo ($author && $author['gioi_tinh'] == 1) ? "selected" : ""; ?>>Nam</option>
                    <option value="0" <?php echo ($author && $author['gioi_tinh'] == 0) ? "selected" : ""; ?>>Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="date" id="ngay_sinh" name="ngay_sinh" required
                    value="<?php echo htmlspecialchars($author['ngay_sinh'] ?? '', ENT_QUOTES); ?>">
            </div>

            <div class="form-group">
                <label>Quê quán</label>
                <input type="text" id="que" name="que" required
                    value="<?php echo htmlspecialchars($author['que'] ?? '', ENT_QUOTES); ?>">
            </div>

            <div class="form-group full">
                <label>Tiểu sử</label>
                <textarea id="tieu_su" name="tieu_su"><?php echo htmlspecialchars($author['tieu_su'] ?? '', ENT_QUOTES); ?></textarea>
            </div>

        </div>

        <!-- BÊN PHẢI -->
        <div class="form-right">
            <button type="button" class="btnupload" id="btnUpload">Upload</button>
            <input type="file" id="fileInput" name="image" accept="image/*" style="display:none;">

            <div class="image">
                <?php if (!empty($author['hinh'])): ?>
                    <img src="../image/<?php echo htmlspecialchars(basename($author['hinh']), ENT_QUOTES); ?>" width="100">
                <?php endif; ?>
            </div>

            <button type="submit" class="btnupdate">Sửa</button>
        </div>
    </form>

    <!-- js -->
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
                                window.location.href = "author.php";
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
        });
    </script>
</body>

</html>