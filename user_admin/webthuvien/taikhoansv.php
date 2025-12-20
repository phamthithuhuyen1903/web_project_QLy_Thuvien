<?php
include 'connect.php';
$sql = "SELECT * FROM sinh_vien"; 
$result = mysqli_query($conn, $sql);
?>
<html>
    <head>
         <title>Tài khoản sinh viên</title>
         <link href="css/taikhoansv.css" rel="stylesheet">
    </head>

    <body>
        <header>
            <h1>Thông tin Sinh viên</h1>
        </header>
    <div class="button">
        <button class="add" onclick="addThongtin()">Thêm</button>
        <input type="text" id="search_sv" placeholder="Tìm kiếm ">
    </div>
    <table>
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
            </tr>
        </thead>
        <tbody id = "tableInfor_sv"><?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['ma_sv']}</td>";
                    echo "<td>{$row['ho_ten']}</td>";
                    echo "<td>{$row['lop']}</td>";
                    echo "<td>{$row['gioi_tinh']}</td>";
                    echo "<td>{$row['ngay_sinh']}</td>";
                    echo "<td>{$row['dia_chi']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>{$row['sdt']}</td>";
                    // echo "<td>{$row['ten_dang_nhap']}</td>";
                    // echo "<td>******</td>"; // Không nên hiển thị mật khẩu thật
                    // echo "<td>
                    //     <a href='edit_admin.php?id={$row['ma_admin']}' title='Sửa'><i class='fa-solid fa-pen-to-square'></i></a>
                    //     <button class='btn-delete-admin' data-id='{$row['ma_admin']}' style='border:none; background:none; color:red; cursor:pointer;'><i class='fas fa-trash-alt'></i></button>
                    // </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Chưa có tài khoản admin nào.</td></tr>";
            }
            ?></tbody>
    </table>
    <script src="js/taikhoansv.js"></script>
    </body>

</html>