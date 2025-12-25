document.addEventListener('DOMContentLoaded', function() {
    const inputSearch = document.getElementById('search_sv');
    const tableBody = document.getElementById('tableInfor_sv');
    let timeout = null; // Biến để lưu thời gian chờ

    // Hàm thực hiện gọi AJAX
    function performSearch() {
        const searchValue = inputSearch.value.trim();

        // Gửi yêu cầu đến timkiemsv.php
        // Nếu searchValue rỗng, PHP sẽ thực hiện: WHERE ma_sv LIKE '%%' (tức là lấy tất cả)
        fetch('timkiemsv.php?search=' + encodeURIComponent(searchValue))
            .then(response => {
                if (!response.ok) throw new Error('Lỗi kết nối máy chủ');
                return response.text();
            })
            .then(data => {
                tableBody.innerHTML = data;
            })
            .catch(error => {
                console.error('Lỗi:', error);
                tableBody.innerHTML = "<tr><td colspan='11' style='text-align:center; color:red;'>Có lỗi xảy ra.</td></tr>";
            });
    }

    // Bắt sự kiện khi người dùng gõ
    if (inputSearch) {
        inputSearch.addEventListener('input', function() {
            // Xóa hàng đợi cũ nếu người dùng đang gõ nhanh
            clearTimeout(timeout);

            // Chờ 300ms sau khi ngừng gõ mới gửi yêu cầu (Debounce)
            // Điều này giúp web mượt hơn và giảm tải cho server
            timeout = setTimeout(() => {
                performSearch();
            }, 100);
        });
    }
});