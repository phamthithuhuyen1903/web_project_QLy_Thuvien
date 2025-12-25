<?php
include 'connect.php';
$sql = "SELECT admin.*, user.username, user.password 
        FROM admin 
        INNER JOIN user ON admin.id = user.id"; 

$result = mysqli_query($conn, $sql);
?>

<html>
    <head>
         <title>Tài khoản admin</title>
         <link href="../css/taikhoanadmin.css" rel="stylesheet">
    </head>

    <body>
        <header>
            <h1>Thông tin Admin</h1>
        </header>
        
        <table>
        <thead>
            <tr>
                <th>Mã admin</th>
                <th>Họ tên</th>
                <th>Giới tính</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="tableInfor_admin">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['ma_admin']}</td>";
                    echo "<td>{$row['ho_ten']}</td>";
                    echo "<td>{$row['gioi_tinh']}</td>";
                    echo "<td>{$row['sdt']}</td>";
                    echo "<td>{$row['email']}</td>";
                    
                    echo "<td>
                            <a href='suaadmin.php?id={$row['ma_admin']}' style='text-decoration:none; color:blue; font-weight:bold;'>Sửa</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Chưa có tài khoản admin nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </body>
</html>