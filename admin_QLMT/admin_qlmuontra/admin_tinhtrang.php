<?php
    require '../connectDB.php';

    $sql = "SELECT tinh_trang, Count(*) AS soluong 
            FROM phieu_muon 
            GROUP BY tinh_trang";
    
    $result = mysqli_query($conn, $sql);

    $tinhtrang = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tinhtrang[$row['tinh_trang']] = $row['soluong'];
        }
    }
    else {
        echo "Lỗi truy vẫn: " . mysqli_error($conn);
    }

    $tinhtrangList = [
        'Đã trả' , 
        'Đang cho mượn',
        'Quá hạn trả',
        'Trả chậm'
    ];

?>

<form method="get">
    <?php foreach ($tinhtrangList as $tt): ?>
        <?php $count = $tinhtrang[$tt] ?? 0; ?>
        <button type="submit" name="btn_tinhtrang" value="<?=  htmlspecialchars($tt) ?>" class="hop_thong_ke">
            <strong><?= htmlspecialchars($tt) ?>: <?=  $count ?></strong>
        </button>
    <?php endforeach; ?>

    <button type="submit" name="btn_tinhtrang" value="" class="hop_thong_ke">
        <strong>Hiển thị tất cả</strong>
    </button>

</form>