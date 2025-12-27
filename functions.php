<?php
// functions.php

/**
 * 1. Kiểm tra thời hạn mượn (không quá 31 ngày)
 */
function ktraNgayTra($ngay_muon, $ngay_tra)
{
    $dateM = new DateTime($ngay_muon);
    $dateT = new DateTime($ngay_tra);

    if ($dateT < $dateM) return false;

    $diff = $dateM->diff($dateT);
    return ($diff->days <= 31);
}

/**
 * 2. Kiểm tra tồn kho
 */
function ktraTonKho($conn, $ma_sach, $so_luong_muon)
{
    $sql = "SELECT so_luong FROM sach WHERE ma_sach = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_sach);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return ($row && $row['so_luong'] >= $so_luong_muon);
}

/**
 * 3. Cập nhật trạng thái sách theo số lượng
 * so_luong > 0  => tinh_trang = 1 (Còn)
 * so_luong <= 0 => tinh_trang = 0 (Hết)
 */
function capNhatTrangThaiSach($conn, $ma_sach)
{
    $sql = "
        UPDATE sach 
        SET tinh_trang = CASE 
            WHEN so_luong > 0 THEN 1 
            ELSE 0 
        END
        WHERE ma_sach = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_sach);
    $stmt->execute();
}

/**
 * 4. Hàm mượn sách
 */
function muonSach($conn, $ma_pm, $ma_sv, $ma_sach, $so_luong, $ngay_muon, $ngay_tra)
{
    $conn->begin_transaction();
    try {
        // Thêm phiếu mượn
        $sql_pm = "
            INSERT INTO phieu_muon 
            (ma_pm, ma_sv, ma_sach, tinh_trang, ngay_muon, ngay_tra, so_luong)
            VALUES (?, ?, ?, 'Đang cho mượn', ?, ?, ?)
        ";
        $stmt_pm = $conn->prepare($sql_pm);
        $stmt_pm->bind_param(
            "sssssi",
            $ma_pm,
            $ma_sv,
            $ma_sach,
            $ngay_muon,
            $ngay_tra,
            $so_luong
        );
        $stmt_pm->execute();

        // Trừ kho
        $sql_sach = "UPDATE sach SET so_luong = so_luong - ? WHERE ma_sach = ?";
        $stmt_sach = $conn->prepare($sql_sach);
        $stmt_sach->bind_param("is", $so_luong, $ma_sach);
        $stmt_sach->execute();

        // Cập nhật trạng thái còn / hết
        capNhatTrangThaiSach($conn, $ma_sach);

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

/**
 * 5. Hàm trả sách (Đã trả / Trả chậm)
 */
function traSachHieuQua($conn, $ma_pm, $ma_sach, $so_luong_tra, $tinh_trang_moi)
{
    $conn->begin_transaction();
    try {
        // Cập nhật tình trạng phiếu mượn
        $sql_pm = "UPDATE phieu_muon SET tinh_trang = ? WHERE ma_pm = ?";
        $stmt_pm = $conn->prepare($sql_pm);
        $stmt_pm->bind_param("ss", $tinh_trang_moi, $ma_pm);
        $stmt_pm->execute();

        // Cộng kho
        $sql_kho = "UPDATE sach SET so_luong = so_luong + ? WHERE ma_sach = ?";
        $stmt_kho = $conn->prepare($sql_kho);
        $stmt_kho->bind_param("is", $so_luong_tra, $ma_sach);
        $stmt_kho->execute();

        // Cập nhật trạng thái sách
        capNhatTrangThaiSach($conn, $ma_sach);

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

/**
 * 6. Tạo mã phiếu mượn tự động
 */
function taoMaPhieuMuonTuDong($conn)
{
    $sql = "
        SELECT ma_pm 
        FROM phieu_muon 
        WHERE ma_pm LIKE 'PM%' 
        ORDER BY CAST(SUBSTRING(ma_pm, 3) AS UNSIGNED) DESC 
        LIMIT 1
    ";
    $rs = mysqli_query($conn, $sql);

    if ($rs && mysqli_num_rows($rs) > 0) {
        $row = mysqli_fetch_assoc($rs);
        $so = (int)substr($row['ma_pm'], 2);
        return 'PM' . str_pad($so + 1, 2, '0', STR_PAD_LEFT);
    }

    return 'PM01';
}
