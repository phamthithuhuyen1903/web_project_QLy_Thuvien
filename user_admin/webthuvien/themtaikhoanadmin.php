<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tài khoản admin</title>
    <link rel="stylesheet" href="css/themdmsach.css"> 
</head>
<body>
    <h2>Thêm mới tài khoản admin</h2>

    <form action="process_add_admin.php" method="POST" class="fromadd" enctype="multipart/form-data">
        <div class="form-left">

            <div class="form-group">
                <label>Mã admin</label>
                <input type="text" id="maadmin" name="maadmin" required>
            </div>

            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" id="hotenad" name="hoten" required>
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <select name="gioitinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="emailad" name="email">
            </div>

            <div class="form-group">
                <label>Tên tài khoản</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
        </div>

            <button type="submit" name="btn_them" class="btnthem">Lưu thông tin</button>
            <button type="button" onclick="history.back()">Quay lại</button> 
        </div>
    </form>

</body>
</html>