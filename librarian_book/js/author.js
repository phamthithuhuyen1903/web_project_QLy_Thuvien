
function loadTable() {
    fetch("author.php?action=load")
    .then(res => res.text())
    .then(data => {
        document.getElementById("tableauthor").innerHTML = data;
    });
}
loadTable(); 

//XÓA
document.addEventListener("click", function(e) {document.getElementById("deleteConfirmBox").style.display = "none";});document.addEventListener("click", function (e) {
    let btn = e.target.closest(".btn-delete");
    if(btn) {
        let ma = btn.dataset.id;
        showDeleteConfirm(ma);
    }
});
/* hàm xác nhận xóa */
function showDeleteConfirm(ma) {
    const box = document.getElementById("deleteConfirmBox");
    box.style.display = "flex"; // hiện hộp xác nhận

    document.getElementById("yesDelete").onclick = () => {
        let formData = new FormData();
        formData.append("action", "delete");
        formData.append("ma", ma);  

        fetch("author.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                showPopupMessage("✔️ Xóa tác giả thành công", "success");
                loadTable(); // tải lại bảng sau khi xóa
            } else if (data === "has_books") {
                showPopupMessage("❌ Không thể xóa vì tác giả này đang có sách!", "error");
            } else {
                showPopupMessage("❌ Xóa không thành công", "error");
            }
        })
        .catch(err => {
            showPopupMessage("❌ Lỗi kết nối khi xóa", "error");
            console.error("Lỗi:", err);
        });

        box.style.display = "none"; // ẩn hộp sau khi xử lý
    };

    document.getElementById("noDelete").onclick = () => {
        box.style.display = "none"; // ẩn hộp nếu chọn No
    };
}

    function showPopupMessage(message, type="success") {
        const box = document.getElementById("popupMessage");
        const text = document.getElementById("popupText");
    
        text.innerText = message;
        box.className = "popup-message " + type;
        box.style.display = "block";
    
        // nút đóng
        document.getElementById("popupClose").onclick = () => {
            box.style.display = "none";
        };
    }

    // TÌM KIẾM THEO TÊN
document.getElementById("search").addEventListener("keyup", function () {
    let keyword = this.value.trim();

    if (keyword === "") {
        // gọi lại hàm load toàn bộ bảng sách
        loadTable();
        return;
    }

    fetch("author.php?action=search&keyword=" + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(html => {
            document.getElementById("tableauthor").innerHTML = html;
        })
        .catch(err => console.error("Lỗi tìm kiếm:", err));
});


