<html>
    <head>
        <link rel="stylesheet" href="../css/table_phieumuon.css">
    </head>
<body>
<table>
    <tr>
        <th>Mã PM</th>
        <th>Mã SV</th>
        <th>Tên SV</th>
        <th>Mã sách</th>
        <th>Tên sách</th>
        <th>Tác giả</th>
        <th>NXB</th>
        <th>Ngày mượn</th>
        <th>Ngày trả</th>
        <th>Tình trạng</th>
        <th colspan="3">Thao tác</th>
    </tr>

    <?php if (!empty($rows)): ?>
    <?php foreach ($rows as $r): ?>
        <?php
            // Tạo class CSS thân thiện từ tình trạng
            $class = strtolower(str_replace([' ', '(', ')', 'đã', 'ả'], ['-', '', '', 'da', 'a'], $r['tinh_trang']));
        ?>
        <tr>
            <td><?= htmlspecialchars($r['ma_pm']) ?></td>
            <td><?= htmlspecialchars($r['ma_sv']) ?></td>
            <td><?= htmlspecialchars($r['ho_ten']) ?></td>
            <td><?= htmlspecialchars($r['ma_sach']) ?></td>
            <td><?= htmlspecialchars($r['ten_sach']) ?></td>
            <td><?= htmlspecialchars($r['ten_tg']) ?></td>
            <td><?= htmlspecialchars($r['nha_xb']) ?></td>
            <td><?= date('d-m-Y', strtotime($r['ngay_muon'])) ?></td>
            <td><?= date('d-m-Y', strtotime($r['ngay_tra'])) ?></td>

            <td><span class="status"><?= htmlspecialchars($r['tinh_trang']) ?></span></td>

            <td class="action_links">
                <a href="update_phieumuon.php?ma_pm=<?= urlencode($r['ma_pm']) ?>" title="Sửa">
                    <i class="fas fa-edit"></i>
                </a>
            </td>

            <td class="action_links">
                <a href="#" onclick="deletePhieu('<?= $r['ma_pm'] ?>')" title="Xóa">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>

            <td class="action_links">
                <?php if (in_array($r['tinh_trang'], ['Trả chậm', 'Quá hạn trả'])): ?>
                    <a href="hinhphat/form_xulyhp.php?masv=<?= urlencode($r['ma_sv']) ?>&ho_ten=<?= urlencode($r['ho_ten']) ?>&tinh_trang=<?= urlencode($r['tinh_trang']) ?>" 
                       class="btn_phat" title="Xử lý hình phạt">
                        <i class="fas fa-gavel"></i>
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="13" style="text-align:center; color:red;">
                Không có dữ liệu phiếu mượn
            </td>
        </tr>
    <?php endif; ?>

</table>
</body>
</html>