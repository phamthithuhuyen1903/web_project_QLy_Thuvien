<html>
    <head>
        <link rel="stylesheet" href="/Project_QuanLyThuVien/css/table_phieumuon.css">
    </head>
<body>
<table>
    <tr>
        <th>Mã SV</th>
        <th>Tên SV</th>
        <th>Tên sách</th>
        <th>Mã sách</th>
        <th>Tác giả</th>
        <th>NXB</th>
        <th>Ngày mượn</th>
        <th>Ngày trả</th>
        <th>Tình trạng</th>
        <th colspan="3">Thao tác</th>
    </tr>

    <?php foreach ($rows as $r): ?>
        <?php
            // Tạo class CSS thân thiện từ tình trạng
            $class = strtolower(str_replace([' ', '(', ')', 'đã', 'ả'], ['-', '', '', 'da', 'a'], $r['tinh_trang']));
        ?>
        <tr>
            <td><?= htmlspecialchars($r['ma_sv']) ?></td>
            <td><?= htmlspecialchars($r['ho_ten']) ?></td>
            <td><?= htmlspecialchars($r['ten_sach']) ?></td>
            <td><?= htmlspecialchars($r['ma_sach']) ?></td>
            <td><?= htmlspecialchars($r['ten_tg']) ?></td>
            <td><?= htmlspecialchars($r['nha_xb']) ?></td>
            <td><?= date('d-m-Y', strtotime($r['ngay_muon'])) ?></td>
            <td><?= date('d-m-Y', strtotime($r['ngay_tra'])) ?></td>

            <td><span class="status"><?= htmlspecialchars($r['tinh_trang']) ?></span></td>

            <td class="action_links">
                <a href="update_phieumuontra.php?id=<?= urlencode($r['ma_pm']) ?>" title="Sửa">
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
                    <a href="hinhphat/form_xulyhp.php?masv=<?= urlencode($r['ma_sv']) ?>&ho_ten=<?= urlencode($r['ho_ten']) ?>" 
                       class="btn_phat" title="Xử lý hình phạt">
                        <i class="fas fa-gavel"></i>
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>