function toggleQuickMenu() {
    const menu = document.getElementById("quick-menu");
    // Thêm hoặc xóa class 'show' để hiện/ẩn menu
    menu.classList.toggle("show");
}

function hienDanhMucSach() {
    const danhmuc = document.getElementById("danhmuc");
    if (danhmuc) {
        danhmuc.style.display = "block";
        // Đóng menu sau khi chọn
        document.getElementById("quick-menu").classList.remove("show");
        danhmuc.scrollIntoView({ behavior: 'smooth' });
    }
}