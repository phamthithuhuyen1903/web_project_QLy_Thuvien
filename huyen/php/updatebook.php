<?php
include 'connect.php';

// ==========================
// LẤY DỮ LIỆU SÁCH THEO MÃ
// ==========================
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

// ==========================
// LẤY DANH SÁCH THỂ LOẠI
// ==========================
$sqlLoai = "SELECT * FROM loai_sach";
$resLoai = mysqli_query($conn, $sqlLoai);

// ==========================
// CẬP NHẬT SÁCH (AJAX POST)
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma = $_POST['masach'];
    $ten = $_POST['tensach'];
    $tg = $_POST['tacgia'];
    $nxb = $_POST['nxb'];
    $nam = $_POST['namxuatban'];
    $sl = $_POST['soluong'];
    $mota = $_POST['mota'];
    $loai = $_POST['ma_loai_sach'];
    $tinhtrang = $_POST['tinh_trang'];

    // Nếu có upload ảnh
    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $filename = basename($_FILES['image']['name']);
        $target = "../image/" . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imagePath = $filename;
        }
    }

    if ($imagePath !== "") {
        $sql = "UPDATE sach 
                SET ten_sach=?, ten_tg=?, nha_xb=?, nam_xb=?, so_luong=?, mo_ta=?, ma_loai_sach=?, tinh_trang=?, image=? 
                WHERE ma_sach=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssiiisis", $ten, $tg, $nxb, $nam, $sl, $mota, $loai, $tinhtrang, $imagePath, $ma);
    } else {
        $sql = "UPDATE sach 
                SET ten_sach=?, ten_tg=?, nha_xb=?, nam_xb=?, so_luong=?, mo_ta=?, ma_loai_sach=?, tinh_trang=? 
                WHERE ma_sach=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssiiisis", $ten, $tg, $nxb, $nam, $sl, $mota, $loai, $tinhtrang, $ma);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa sách</title>
    <link rel="stylesheet" href="../css/updatebook.css">
</head>

<body>
    <h2>Sửa sách</h2>

    <form class="fromadd" enctype="multipart/form-data">
        <div class="form-left">

            <div class="form-group">
                <label>Mã sách</label>
                <input type="text" id="masach" name="masach" readonly
                    value="<?php echo $book['ma_sach'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Tên sách</label>
                <input type="text" id="tensach" name="tensach"
                    value="<?php echo $book['ten_sach'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Tác giả</label>
                <input type="text" id="tacgia" name="tacgia"
                    value="<?php echo $book['ten_tg'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>NXB</label>
                <input type="text" id="nxb" name="nxb"
                    value="<?php echo $book['nha_xb'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Năm xuất bản</label>
                <select name="namxuatban">
                    <option value="">-- Chọn năm --</option>
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
                <input type="number" id="soluong" name="soluong"
                    value="<?php echo $book['so_luong'] ?? 0; ?>">
            </div>

            <div class="form-group">
                <label>Thể loại</label>
                <select name="ma_loai_sach">
                    <option value="">-- Chọn thể loại --</option>
                    <?php
                    if ($resLoai && mysqli_num_rows($resLoai) > 0) {
                        while ($row = mysqli_fetch_assoc($resLoai)) {
                            $selected = ($book && $book['ma_loai_sach'] == $row['ma_loai_sach']) ? "selected" : "";
                            echo "<option value='" . $row['ma_loai_sach'] . "' $selected>" . $row['ten_loai_sach'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tình trạng</label>
                <select name="tinh_trang">
                    <option value="1" <?php echo ($book && $book['tinh_trang'] == 1) ? "selected" : ""; ?>>Còn</option>
                    <option value="0" <?php echo ($book && $book['tinh_trang'] == 0) ? "selected" : ""; ?>>Hết</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Mô tả</label>
                <textarea id="mota" name="mota"><?php echo $book['mo_ta'] ?? ''; ?></textarea>
            </div>

        </div>

        <!-- BÊN PHẢI -->
        <div class="form-right">
            <button type="button" class="btnupload" id="btnUpload">Upload</button>
            <input type="file" id="fileInput" name="image" accept="image/*" style="display:none;">

            <div class="image">
                <?php if (!empty($book['image'])): ?>
                    <img src="../image/<?php echo basename($book['image']); ?>" width="100">
                <?php endif; ?>
            </div>

            <button type="submit" class="btnthem">Sửa</button>
        </div>
    </form>


    <!-- Gọi file JS riêng -->
    <script src="../js/updatebook.js"></script>
</body>

</html>