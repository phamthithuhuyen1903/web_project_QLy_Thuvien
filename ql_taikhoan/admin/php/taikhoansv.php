<?php
include '../../../Connect/connect.php';
$sql = "SELECT * FROM sinh_vien";
$result = mysqli_query($conn, $sql);

?>
<html>

<head>
    <title>Tài khoản sinh viên</title>
    <link href="../css/taikhoansv.css" rel="stylesheet">
    <link href="/Project_QuanLyThuVien/header.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

    <div class="thanhdieuhuong">
        <a href="/Project_QuanLyThuVien/phan_quyen/php/TRANGCHU.PHP" class="thanhdieuhuong_btn">
            <i class="fas fa-home"></i> Trang Chủ
        </a>
        <span class="thanhdieuhuong_separator"></span>
        <a href="#" class="thanhdieuhuong_btn active">
            <i class="fas fa-user"></i> Tài khoản sinh viên
        </a>
    </div>

    <header>
        <h1>Thông tin Sinh viên</h1>
    </header>
    <div class="button">
        <button class="add" onclick="window.location.href='themtaikhoansv.php'">Thêm</button>
        <div class="search-container" style="display: inline-block">
            <input type="text" id="search_sv" placeholder="Nhập mã sv hoặc tên">
            <button type="button" id="btn_search" class="btn-search-submit">Tìm kiếm</button>
        </div>
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
                <!-- <th>Tên tài khoản</th>
                <th>Mật khẩu</th> -->
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
                                            // echo "<td>{$row['tentk']}</td>";
                                            // echo "<td>{$row['matkhau']}</td>";
                                            // echo "<td>{$row['ten_dang_nhap']}</td>";
                                            // echo "<td>******</td>"; // Không nên hiển thị mật khẩu thật
                                            // echo "<td>
                                            //     <a href='edit_admin.php?id={$row['ma_admin']}' title='Sửa'><i class='fa-solid fa-pen-to-square'></i></a>
                                            //     <button class='btn-delete-admin' data-id='{$row['ma_admin']}' style='border:none; background:none; color:red; cursor:pointer;'><i class='fas fa-trash-alt'></i></button>
                                            // </td>";
                                            echo "<td>
                            <a href='suasv.php?id={$row['ma_sv']}' style='text-decoration:none; color:blue; font-weight:bold;'>Sửa</a> | 
                            <a href='xoasv.php?id={$row['ma_sv']}' style='text-decoration:none; color:red; font-weight:bold;' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>
                          </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='11'>Chưa có tài khoản sinh viên nào.</td></tr>";
                                    }
                                    ?></tbody>
    </table>
    <script src="../js/timkiemsv.js"></script>
</body>

</html>