<?php
require_once __DIR__ . '/../../connect/connect.php';

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
            echo '<li>
    <a href="?controller=theloai&ml='
                . $row['ma_loai_sach'] . '">'
                . $row['ten_loai_sach'] .
                '</a></li>';
        }
        ?>
    </ul>

    <?php } else {

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
        $sach = mysqli_query($conn, $sql);

        if (mysqli_num_rows($sach) > 0) {
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

                while ($r_sach = mysqli_fetch_assoc($sach)) {
                ?>
                    <tr class="row-click" onclick="window.location='/quanlythuvien/demo_quanlythuvien/php/chitiet_sach.php?ml=<?= $ma_loai ?>&ms=<?= $r_sach['ma_sach'] ?>'">
                        <td>

                            <?= $stt  ?>
                            </a>
                        </td>

                        <td>
                            <?php
                            $hinh = 'no-image.png';
                            if (isset($r_sach['hinh']) && $r_sach['hinh'] != '') {
                                $hinh = $r_sach['hinh'];
                            }
                            ?>

                            <img src="../images/<?= $hinh ?>" alt="Hình minh họa" width="60">
                            </a>
                        </td>

                        <td>

                            <?= $r_sach['ten_sach'] ?>
                            </a>
                        </td>

                        <td>

                            <?= $r_sach['ten_tg'] ?>
                            </a>
                        </td>

                        <td>

                            <?= $r_sach['nha_xb'] ?>
                            </a>
                        </td>

                        <td>

                            <?= $r_sach['nam_xb'] ?>
                            </a>
                        </td>

                        <td>
                            <a href="chitiet_sach.php?ml=<?= $ma_loai ?>&ms=<?= $r_sach['ma_sach'] ?>">
                                <?= $r_sach['so_luong'] ?>
                            </a>
                        </td>

                        <td>

                            <?= $r_sach['so_luong'] > 0 ? 'Còn' : 'Hết' ?>
                            </a>
                        </td>
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