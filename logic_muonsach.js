/**
 * Hàm thiết lập các ràng buộc mượn sách dùng chung cho toàn hệ thống
 * @param {string} ngayMuonSelector - ID của ô ngày mượn (vd: '#ngay_muon')
 * @param {string} ngayTraSelector  - ID của ô ngày trả (vd: '#ngay_tra')
 * @param {string} soLuongSelector  - ID của ô số lượng (vd: '#so_luong')
 * @param {number} maxStock         - Số lượng tối đa hiện có trong kho
 */
function kiemTra(ngayMuonSelector, ngayTraSelector, soLuongSelector, maxStock) {
    
    // --- 1. Xử lý logic Ngày tháng (Hạn 31 ngày) ---
    $(ngayMuonSelector).on('change', function() {
        let ngayMuonVal = this.value;
        let $ngayTra = $(ngayTraSelector);
        
        if (ngayMuonVal) {
            let ngayMuon = new Date(ngayMuonVal);
            let maxDate = new Date(ngayMuon);
            maxDate.setDate(maxDate.getDate() + 31); // Cộng 31 ngày

            // Cập nhật min/max cho ô ngày trả
            $ngayTra.attr('min', ngayMuonVal);
            $ngayTra.attr('max', maxDate.toISOString().split('T')[0]);
            
            // Nếu ngày trả cũ không còn hợp lệ thì xóa đi
            if($ngayTra.val() < ngayMuonVal || ($ngayTra.val() && new Date($ngayTra.val()) > maxDate)) {
                $ngayTra.val("");
            }
        }
    });

    // --- 2. Xử lý logic Số lượng (Không vượt tồn kho) ---
    $(soLuongSelector).on('input', function() {
        let val = parseInt($(this).val());
        if (val > maxStock) {
            alert("⚠️ Số lượng mượn không được vượt quá tồn kho (" + maxStock + ")");
            $(this).val(maxStock);
        } else if (val < 1) {
            $(this).val(1);
        }
    });
}

function goBack() {
    // Kiểm tra xem có trang trước đó trong lịch sử không
    if (document.referrer !== "") {
        window.history.back();
    } else {
        // Nếu không có lịch sử (truy cập trực tiếp), quay về trang chủ
        window.location.href = '/Project_QuanLyThuVien/phan_quyen/php/trangchu_view.php'; 
    }
}