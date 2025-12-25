<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mượn trả</title>
    <link rel="stylesheet" href="lsmt.css">
</head>

<body>
    <div class="container">
        <header>
            <h2>⏳ LỊCH SỬ MƯỢN TRẢ SÁCH</h2>
            <p>
                Chào, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                (<?php echo ($_SESSION['role'] == 'admin') ? 'Quản trị viên' : 'Sinh viên'; ?>) |
                <a href="TRANGCHU.PHP">Quay lại trang chủ</a>
            </p>
        </header>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <div class="search-bar">
                <form action="" method="GET">
                    <input type="text" name="search" placeholder="Nhập Mã SV hoặc Tên..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Tìm kiếm</button>

                    <?php if (!empty($search)): ?>
                        <a href="lsmt_process.php" class="btn-clear">Xóa lọc</a>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>
        <?php
        if (isset($_GET['ms'])) {
            include 'muon_sach.php';
        }
        ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Mã PM</th>
                        <th>Mã SV</th>

                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <th>Họ Tên SV</th>
                        <?php endif; ?>

                        <th>Tên Sách</th>
                        <th>Ngày Mượn</th>
                        <th>Ngày Trả</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($data) && count($data) > 0): ?>
                        <?php foreach ($data as $row): ?>
                            <?php
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ma_pm']); ?></td>
                                <td><?php echo htmlspecialchars($row['ma_sv']); ?></td>

                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <td><?php echo htmlspecialchars($row['ho_ten'] ?? 'N/A'); ?></td>
                                <?php endif; ?>

                                <td><?php echo htmlspecialchars($row['ten_sach'] ?? $row['ma_sach']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['ngay_muon'])); ?></td>

                                <td>
                                    <?php
                                    if ($row['ngay_tra'] == '0000-00-00' || $row['ngay_tra'] == null) {
                                        echo '<span class="not-returned">-- Chưa trả --</span>';
                                    } else {
                                        echo date('d/m/Y', strtotime($row['ngay_tra']));
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $statusClass = '';
                                    $statusText = $row['tinh_trang'];

                                    if (stripos($statusText, 'Đã trả') !== false) {
                                        $statusClass = 'bg-success';
                                    } elseif (stripos($statusText, 'Đang mượn') !== false) {
                                        $statusClass = 'bg-warning';
                                    } else {
                                        $statusClass = 'bg-danger';
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($statusText); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
                                Không tìm thấy dữ liệu phiếu mượn nào phù hợp.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>