<?php
include("connect.php");

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
} else {
    $keyword = '';
}
?>
<h2>DANH SÁCH TÁC GIẢ</h2>

<form method="GET" action="" style="margin-bottom:15px;">
    <input type="hidden" name="id" value="tacgia">
    <input type="text" name="keyword"
        placeholder="Tìm theo tên tác giả..."
        value="<?php echo htmlspecialchars($keyword) ?>">
    <button type="submit">🔍 Tìm</button>
</form>

<?php
$sql = "select * from tac_gia where 1";
if ($keyword != "") {
    $sql .= " AND ten_tg LIKE '%$keyword%'";
}
$result = mysqli_query($conn, $sql);
?>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>STT</th>
        <th>Hình</th>
        <th>Tác giả</th>
        <th>Quê</th>
    </tr>
    <?php
    $stt = 1;
    while ($tg = mysqli_fetch_assoc($result)) {
    ?>
        <tr onclick="location=' chitiet_tacgia.php?ma_tg=<?php echo $tg['ma_tg'] ?>'"
            style="cursor: pointer;">
            <td><?php echo $stt ?></td>
            <td>
                <?php
                if (!empty($tg['hinh'])) {
                    $hinh = $tg['hinh'];
                } else {
                    $hinh = 'no-image.png';
                }
                ?>
                <img src="images/<?php echo $hinh; ?>" width="60">
            </td>
            <td><?php echo $tg['ten_tg'] ?></td>
            <td><?php echo $tg['que'] ?></td>
        </tr>
    <?php
        $stt++;
    }
    ?>
</table>