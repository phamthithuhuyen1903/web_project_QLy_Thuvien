function showFormMessage(id, message, type) {
    const box = document.getElementById(id);
    box.innerText = message;

    box.className = "form-message";

    if (type === "success") {
        box.classList.add("form-success");
    } else {
        box.classList.add("form-error");
    }

    box.style.display = "block";
}


/*FORM THÊM*/
document.querySelector(".add").addEventListener("click", () => {
    document.getElementById("addFormModal").style.display = "flex";
});

document.getElementById("btnClose").addEventListener("click", () => {
    document.getElementById("addFormModal").style.display = "none";
});

document.getElementById("btnSave").addEventListener("click", () => {
    let ma = document.getElementById("maLoai").value.trim();
    let ten = document.getElementById("tenLoai").value.trim();
    if(ma=="" && ten==""){
        showFormMessage("addMessage", "Vui lòng điền đầy đủ thông tin", "error");
        return;
    }
    if(ma===""){
        showFormMessage("addMessage", "Mã loại không được để trống", "error");
        return;
    }
    if(ten===""){
        showFormMessage("addMessage", "Tên loại không được để trống", "error");
        return;
    }
    
    let formData = new FormData();
    formData.append("action", "add");
    formData.append("ma", ma);
    formData.append("ten", ten);
    
    fetch("theloai.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {

        if (data === "success") {
            showFormMessage("addMessage", "Thêm thành công", "success");
            loadTable();

            //sau 2 giây quay về trang chủ
            setTimeout(() => {
                window.location.href = "theloai.php";
            }, 1000);

        } else if (data === "duplicate") {
            showFormMessage("addMessage", "Mã loại đã tồn tại", "error");

        } else {
            showFormMessage("addMessage", "Thêm không thành công", "error");
        }
    });
});

/* FORM SỬA */
document.addEventListener("click", function(e) {
    let btn = e.target.closest(".btn-edit");
    if (btn) {
        let row = btn.closest("tr");
        document.getElementById("editMaLoai").value = row.children[0].innerText;
        document.getElementById("editTenLoai").value = row.children[1].innerText;
        document.getElementById("editMessage").style.display = "none";
        document.getElementById("editFormModal").style.display = "flex";
    }
});

document.getElementById("btnCloseEdit").addEventListener("click", () => {
    document.getElementById("editFormModal").style.display = "none";
});
document.getElementById("btnUpdate").addEventListener("click", () => {
    let ma = document.getElementById("editMaLoai").value;
    let ten = document.getElementById("editTenLoai").value.trim();
    if (ten === "") {
        showFormMessage("editMessage", "Tên loại không được để trống", "error");
        return;
    }
    let formData = new FormData();
    formData.append("action", "update");
    formData.append("ma", ma);
    formData.append("ten", ten);

    fetch("theloai.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data === "success") {
            showFormMessage("editMessage", "Cập nhật thành công", "success");
            loadTable();
            //sau 2 giây quay về trang chủ
            setTimeout(() => {
                window.location.href = "theloai.php";
            }, 1000);

        } else {
            showFormMessage("editMessage", "Có lỗi xảy ra", "error");
        }
    });
});
/* FORM XÓA */
document.addEventListener("click", function(e) {
    let btn = e.target.closest(".btn-delete");
    if (btn) {
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

        fetch("theloai.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                showPopupMessage( "Xóa thành công", "success");
                loadTable(); // tải lại bảng sau khi xóa
            } else {
                showPopupMessage("Xóa không thành công", "error");
            }
        });

        box.style.display = "none"; // ẩn hộp sau khi xử lý
    };

    document.getElementById("noDelete").onclick = () => {
        box.style.display = "none"; // ẩn hộp nếu chọn No
    };
}
// hàm hiện hộp thoại thông báo
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


/* ==========================
   TÌM KIẾM THEO TÊN
   ========================== */
document.getElementById("search").addEventListener("keyup", function () {
    let keyword = this.value.trim();

    if (keyword === "") {
        loadTable();
        return;
    }

    fetch("theloai.php?action=search&keyword=" + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(html => {
            document.getElementById("tabletheloai").innerHTML = html;
        });
});

/* ==========================
   LOAD BẢNG
   ========================== */
function loadTable() {
    fetch("theloai.php?action=load")
        .then(res => res.text())
        .then(html => {
            document.getElementById("tabletheloai").innerHTML = html;
        });
}

loadTable();
