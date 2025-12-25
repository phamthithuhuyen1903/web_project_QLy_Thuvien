<?php
require_once __DIR__ . '/../../../../connect/connect.php'
?>

<?php
$sql = "SELECT sinh_vien.*, user.username, user.password 
        FROM sinh_vien 
        INNER JOIN user ON sinh_vien.id = user.id";
$result = mysqli_query($conn, $sql);
?>

<link href="../webthuvien/sinhvien/css/taikhoansv.css" rel="stylesheet">
<div class="account-info-container">
    <div class="table-title">
        <h1>Thông tin Tài khoản Sinh viên</h1>
    </div>
</div>
<div class="table-responsive">
    <table class="styled-table">
        <thead>
            <tr>
                <th>Mã sinh viên</th>
                <th>Họ tên</th>
                <th>Lớp</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Địa chỉ</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Tên tài khoản</th>
                <th>Mật khẩu</th>
                <th>Thao tác</th>

            </tr>
        </thead>
        <tbody id="tableInfor_sv"><?php
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>{$row['ma_sv']}</td>";
                                            echo "<td>{$row['ho_ten']}</td>";
                                            echo "<td>{$row['lop']}</td>";
                                            echo "<td>{$row['gioi_tinh']}</td>";
                                            $ngay_sinh_formatted = date("d-m-Y", strtotime($row['ngay_sinh']));
                                            echo "<td>{$ngay_sinh_formatted}</td>";
                                            echo "<td>{$row['dia_chi']}</td>";
                                            echo "<td>{$row['email']}</td>";
                                            echo "<td>{$row['sdt']}</td>";
                                            echo "<td>{$row['username']}</td>";
                                            echo "<td>{$row['password']}</td>";
                                            echo "<td>
                            <a href='?controller=suasv&id=" . $row['ma_sv'] . "' 
                               style='text-decoration:none; color:blue; font-weight:bold;'>
                               Đổi mật khẩu
                            </a> 
                          </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='11'>Chưa có tài khoản sinh viên nào.</td></tr>";
                                    }
                                    ?></tbody>
    </table>
</div>