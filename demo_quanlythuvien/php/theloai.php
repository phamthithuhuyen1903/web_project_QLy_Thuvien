<?php
include("connect.php");
if (isset($_GET['ml'])) {
    $ma_loai = $_GET['ml'];
} else {
    $ma_loai = null;
}

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
} else {
    $keyword = '';
}

if (isset($_GET['ms'])) {
    $ma_sach = $_GET['ms'];
} else {
    $ma_sach = null;
}

?>
<link rel="stylesheet" href="../css/theloai.css">
<?php if ($ma_loai == null) { ?>
    <h2>DANH SÁCH THỂ LOẠI</h2>
    <ul class="theloai_list">
        <?php
        $sql = "SELECT * FROM loai_sach";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li><a href="index.php?id=theloai&ml=' . $row['ma_loai_sach'] . '">' . $row['ten_loai_sach'] . '</a></li>';
        }
        ?>
    </ul>

    <?php } else {

    // Lấy thông tin thể loại 
    $sql = "SELECT * FROM loai_sach WHERE ma_loai_sach='$ma_loai'";
    $result = mysqli_query($conn, $sql);
    $loai = mysqli_fetch_assoc($result);
    if ($loai) {
        echo "<h2>Sách " . $loai['ten_loai_sach'] . "</h2>";
    ?>
        <form method="GET" action="" style="margin-bottom:15px;">
            <input type="hidden" name="id" value="theloai">
            <input type="hidden" name="ml" value="<?php echo $ma_loai ?>">
            <input type="text" name="keyword" placeholder="Tìm theo tên sách..." value="<?php echo htmlspecialchars($keyword) ?>">
            <button type="submit">🔍 Tìm</button>
        </form>
        <?php
        $sql = "SELECT s.*, tg.ten_tg FROM sach s JOIN tac_gia tg ON s.ma_tg = tg.ma_tg WHERE s.ma_loai_sach = '$ma_loai'";
        if ($keyword != '') {
            $sql .= " AND s.ten_sach LIKE '%$keyword%'";
        }
        $books = mysqli_query($conn, $sql);

        if (mysqli_num_rows($books) > 0) {
        ?>
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <tr>
                    <th>STT</th>
                    <th>Hình minh họa</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>NXB</th>
                    <th>Năm XB</th>
                    <th>Số lượng</th>
                    <th>Tình trạng</th>
                </tr>
                <?php
                $stt = 1;
                while ($sach = mysqli_fetch_assoc($books)) {
                ?>
                    <tr onclick="location='chitiet_sach.php?ml=<?php echo $ma_loai; ?>&ms=<?php echo $sach['ma_sach'] ?>'" style="cursor:pointer;">
                        <td><?= $stt ?></td>
                        <td>
                            <?php
                            $hinh = 'no-image.png';
                            if (isset($sach['hinh']) && $sach['hinh'] != '') {
                                $hinh = $sach['hinh'];
                            }

                            ?>
                            <img src="../images/<?php echo $hinh; ?>" alt="Hình minh họa" />
                        </td>
                        <td><?php echo $sach['ten_sach'] ?></td>
                        <td><?php echo $sach['ten_tg'] ?></td>
                        <td><?php echo $sach['nha_xb'] ?></td>
                        <td><?php echo $sach['nam_xb'] ?></td>
                        <td><?php echo $sach['so_luong'] ?></td>
                        <td><?php echo $sach['tinh_trang'] == 1 ? 'Còn' : 'Hết' ?></td>
                    </tr>
                <?php
                    $stt++;
                }
                ?>
            </table>
            <?php
        } else {
            echo "<p>Không tìm thấy sách nào.</p>";
        }
            ?><?php
            }
        } ?>