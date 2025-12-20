<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tài khoản sinh viên</title>
    <link rel="stylesheet" href="css/themdmsach.css"> 
</head>
<body>
    <h2>Thêm mới tài khoản sinh viên</h2>

    <form action="process_add_sv.php" method="POST" class="fromadd" enctype="multipart/form-data">
        <div class="form-left">

            <div class="form-group">
                <label>Mã sinh viên</label>
                <input type="text" id="masv" name="masv" required>
            </div>

            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" id="hotensv" name="hoten" required>
            </div>

            <div class="form-group">
                <label>Lớp</label>
                <input type="text" id="lop" name="lop">
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
                <label>Ngày sinh</label>
                <input type="ngaysinh" id="ngaysinhsv" name="ngaysinh">
            </div>

            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="diachi" id="diachi" name="diachi">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" id="emailsv" name="email">
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="sdt" id="sdt" name="sdt">
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