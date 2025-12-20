<?php
include 'connect.php';

// Lấy danh sách thể loại
$sql = "select * from loai_sach";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_sach = $_POST['ma_sach'];
    $ten_sach = $_POST['ten_sach'];
    $ten_tg = $_POST['ten_tg'];
    $nha_xb = $_POST['nha_xb'];
    $nam_xb = $_POST['nam_xb'];
    $so_luong = $_POST['so_luong'];
    $ma_loai_sach = $_POST['ma_loai_sach'];
    $mo_ta = $_POST['mo_ta'];
    $tinh_trang = $_POST['tinh_trang'];

    // Upload ảnh (giữ nguyên name="hinhanh")
    $image = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
        $target_dir = "../image/";
        $filename = basename($_FILES["hinhanh"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file)) {
            $image = $filename;
        }
    }

    // Thêm vào DB
    $sql_insert = "INSERT INTO sach (ma_sach, ten_sach, ten_tg, nha_xb, nam_xb, so_luong, ma_loai_sach, mo_ta, image, tinh_trang)
                   VALUES ('$ma_sach', '$ten_sach', '$ten_tg', '$nha_xb', '$nam_xb', '$so_luong', '$ma_loai_sach', '$mo_ta', '$image', '$tinh_trang')";
    if (mysqli_query($conn, $sql_insert)) {
        echo "success";
    } else {
        echo "error";
    }
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
                <input type="text" name="ten_tg" required>
            </div>

            <div class="form-group">
                <label>NXB</label>
                <input type="text" name="nha_xb">
            </div>

            <div class="form-group">
                <label>Năm xuất bản</label>
                <select name="nam_xb">
                    <option value="">-- Chọn năm --</option>
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
                <select name="ma_loai_sach">
                    <option value="">-- Chọn thể loại --</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['ma_loai_sach'] . "'>" . $row['ten_loai_sach'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tình trạng</label>
                <select name="tinh_trang">
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
            <button type="button" class="btnupload" onclick="document.getElementById('hinhanh').click();">Upload</button>

            <div class="image">
                <input type="file" id="hinhanh" name="hinhanh" accept="image/*" style="display:none;">
                <div id="preview"></div>
            </div>

            <button type="submit" class="btnthem">Thêm</button>
        </div>
    </form>

    <script src="../js/addbook.js" defer></script>
</body>

</html>