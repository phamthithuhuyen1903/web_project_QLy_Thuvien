<?php
include '../../../Connect/connect.php';

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : '';

$sql = "SELECT * FROM sinh_vien";
if ($search !== '') {
    $sql .= " WHERE ma_sv LIKE '%$search%' 
              OR ho_ten LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $ngay_sinh = date("d-m-Y", strtotime($row['ngay_sinh']));
        echo "<tr>
                <td>{$row['ma_sv']}</td>
                <td>{$row['ho_ten']}</td>
                <td>{$row['lop']}</td>
                <td>{$row['gioi_tinh']}</td>
                <td>{$ngay_sinh}</td>
                <td>{$row['dia_chi']}</td>
                <td>{$row['email']}</td>
                <td>{$row['sdt']}</td>
        
            <td>
                    <a href='suasv.php?id={$row['ma_sv']}' 
                       style='text-decoration:none; color:blue; font-weight:bold;'>Sửa</a> | 
                    <a href='xoasv.php?id={$row['ma_sv']}' 
                       style='text-decoration:none; color:red; font-weight:bold;' 
                       onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr>
            <td colspan='9' style='text-align:center;'>Không có dữ liệu</td>
          </tr>";
}
