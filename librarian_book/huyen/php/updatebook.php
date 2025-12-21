<?php
include 'connect.php';

// Lấy danh sách thể loại
$sqlLoai = "SELECT * FROM loai_sach";
$resLoai = mysqli_query($conn, $sqlLoai);

// Lấy thông tin sách cần sửa
$book = null;
if (isset($_GET['masach'])) {
    $ma = $_GET['masach'];
    $sql = "SELECT * FROM sach WHERE ma_sach = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ma);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($result);
}

// Xử lý form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma = $_POST['masach'];
    $ten = $_POST['tensach'];
    $tg = $_POST['tacgia'];
    $nxb = $_POST['nxb'];
    $nam = (int)$_POST['namxuatban'];
    $sl = (int)$_POST['soluong'];
    $loai = $_POST['ma_loai_sach']; // VARCHAR
    $tinhtrang = (int)$_POST['tinh_trang'];
    $mota = $_POST['mota'];

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
        $sql = "UPDATE sach 
                SET ten_sach=?, ten_tg=?, nha_xb=?, nam_xb=?, so_luong=?, mo_ta=?, ma_loai_sach=?, tinh_trang=?, image=? 
                WHERE ma_sach=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssiississ",
            $ten,
            $tg,
            $nxb,
            $nam,
            $sl,
            $mota,
            $loai,
            $tinhtrang,
            $imagePath,
            $ma
        );
    } else {
        $sql = "UPDATE sach 
                SET ten_sach=?, ten_tg=?, nha_xb=?, nam_xb=?, so_luong=?, mo_ta=?, ma_loai_sach=?, tinh_trang=? 
                WHERE ma_sach=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssiissis",
            $ten,
            $tg,
            $nxb,
            $nam,
            $sl,
            $mota,
            $loai,
            $tinhtrang,
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
    <title>Sửa sách</title>
    <link rel="stylesheet" href="../css/updatebook.css">
</head>

<body>
    <h2>Sửa sách</h2>
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
                <input type="text" id="tacgia" name="tacgia" required
                    value="<?php echo htmlspecialchars($book['ten_tg'] ?? '', ENT_QUOTES); ?>">
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
                    <img src="../image/<?php echo htmlspecialchars(basename($book['image']), ENT_QUOTES); ?>" width="100">
                <?php endif; ?>
            </div>

            <button type="submit" class="btnupdate">Sửa</button>
        </div>
    </form>

    <!-- Gọi file JS riêng -->
    <script src="../js/updatebook.js"></script>
</body>

</html>