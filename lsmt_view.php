<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử mượn trả</title>
    <link rel="stylesheet" href="Css/lsmt.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>⏳ LỊCH SỬ MƯỢN TRẢ SÁCH</h2>
            <p>Chào, <strong><?php echo $_SESSION['name']; ?></strong> | <a href="TRANGCHU.PHP">Quay lại trang chủ</a></p>
        </header>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Tên Sách</th>
                        <th>Ngày Mượn</th>
                        <th>Ngày Hết Hạn</th>
                        <th>Ngày Trả Thực Tế</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($data) > 0): ?>
                        <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $row['MASV']; ?></td>
                            <td><?php echo $row['TENSACH'] ?? $row['MASACH']; ?></td>
                            <td><?php echo date('y/m/d', strtotime($row['NGAYMUON'])); ?></td>
                            <td><?php echo date('y/m/d', strtotime($row['NGAYHETHAN'])); ?></td>
                            <td>
                                <?php 
                                    echo ($row['NGAYTRATHUCTE'] != '0000-00-00') 
                                    ? date('y/m/d', strtotime($row['NGAYTRATHUCTE'])) 
                                    : '<span class="not-returned">Chưa trả</span>'; 
                                ?>
                            </td>
                            <td>
                                <span class="badge <?php echo ($row['TRANGTHAI'] == 'ĐÃ TRẢ') ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo $row['TRANGTHAI']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Không có dữ liệu lịch sử.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>