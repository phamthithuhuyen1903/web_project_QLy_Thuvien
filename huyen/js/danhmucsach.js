document.addEventListener("DOMContentLoaded", () => {
    const deleteBtns = document.querySelectorAll(".btn-delete");
    const deleteModal = document.getElementById("deleteModal");
    const deleteIdInput = document.getElementById("deleteId");
    const btnYes = document.getElementById("btnYes");
    const btnNo = document.getElementById("btnNo");
    const messageBox = document.getElementById("messageBox");

    // Mở modal khi bấm nút xóa
    deleteBtns.forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            deleteIdInput.value = btn.dataset.id;
            deleteModal.classList.add("show");
        });
    });

    // Nút No: đóng modal
    btnNo.addEventListener("click", () => {
        deleteModal.classList.remove("show");
    });

    // Nút Yes: gửi request xóa bằng AJAX
    btnYes.addEventListener("click", () => {
        const id = deleteIdInput.value;
        fetch("danhmucsach.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "action=delete&ma=" + encodeURIComponent(id)
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") {
                messageBox.innerText = "Xóa thành công!";
                messageBox.style.display = "block";
                // Xóa dòng khỏi bảng mà không reload
                const row = document.querySelector(`tr[data-row='${id}']`);
                if (row) row.remove();
                deleteModal.classList.remove("show");
            } else {
                messageBox.innerText = "Xóa thất bại!";
                messageBox.style.display = "block";
                deleteModal.classList.remove("show");
            }
        })
        .catch(err => {
            messageBox.innerText = "Có lỗi xảy ra: " + err;
            messageBox.style.display = "block";
            deleteModal.classList.remove("show");
        });
    });
});
//XEM CHI TIẾT SÁCH
document.addEventListener("DOMContentLoaded", () => {
    const viewButtons = document.querySelectorAll(".btn-view");
    const detailBox = document.getElementById("bookDetailBox");
    const btnClose = document.getElementById("btnCloseDetail");

    viewButtons.forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            const id = btn.getAttribute("data-id");

            fetch("getbookdetail.php?masach=" + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById("bookImage").src = data.image;
                    document.getElementById("bookTitle").innerText = data.ten_sach;
                    document.getElementById("bookAuthor").innerText = data.ten_tg;
                    document.getElementById("bookPublisher").innerText = data.nha_xb;
                    document.getElementById("bookYear").innerText = data.nam_xb;
                    document.getElementById("bookCategory").innerText = data.ten_loai_sach;
                    document.getElementById("bookStatus").innerText = data.tinh_trang_hien_thi;
                    document.getElementById("bookDescription").innerText = data.mo_ta;
                    detailBox.style.display = "block";
                });
        });
    });

    btnClose.addEventListener("click", () => {
        detailBox.style.display = "none";
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const deleteModal = document.getElementById("deleteModal");
    const btnYes = document.getElementById("btnYes");
    const btnNo = document.getElementById("btnNo");
    const messageBox = document.getElementById("messageBox");

    // ==========================
    // TÌM KIẾM THEO TÊN SÁCH
    // ==========================
    const searchInput = document.getElementById("search");
    searchInput.addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll("#tablebook tr");

        rows.forEach(row => {
            const tenSachCell = row.querySelector("td:nth-child(2)"); // cột Tên sách
            if (tenSachCell) {
                const tenSach = tenSachCell.textContent.toLowerCase();
                if (tenSach.includes(keyword)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });
    });

    // ==========================
    // XÓA SÁCH
    // ==========================
    let deleteId = null;
    document.querySelectorAll(".btn-delete").forEach(btn => {
        btn.addEventListener("click", function () {
            deleteId = this.dataset.id;
            document.getElementById("deleteId").value = deleteId;
            deleteModal.style.display = "block";
        });
    });

    btnYes.addEventListener("click", () => {
        const ma = document.getElementById("deleteId").value;

        fetch("danhmucsach.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "action=delete&ma=" + encodeURIComponent(ma)
        })
        .then(res => res.text())
        .then(response => {
            if (response.trim() === "success") {
                const row = document.querySelector("tr[data-row='" + ma + "']");
                if (row) row.remove();
                showMessage("Xóa sách thành công!", "success");
            } else {
                showMessage("Xóa sách thất bại!", "error");
            }
            deleteModal.style.display = "none";
        })
        .catch(err => {
            console.error("Lỗi khi xóa sách:", err);
            showMessage("Có lỗi xảy ra!", "error");
            deleteModal.style.display = "none";
        });
    });

    btnNo.addEventListener("click", () => {
        deleteModal.style.display = "none";
    });

    // ==========================
    // HÀM HIỂN THỊ THÔNG BÁO
    // ==========================
    function showMessage(msg, type) {
        messageBox.textContent = msg;
        messageBox.className = "message " + type;
        messageBox.style.display = "block";
        setTimeout(() => {
            messageBox.style.display = "none";
        }, 3000);
    }
});

