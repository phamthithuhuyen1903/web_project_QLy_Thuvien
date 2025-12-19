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

/* ==========================
   FORM THÊM
   ========================== */
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
    
    fetch("theloai.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=add&ma=${encodeURIComponent(ma)}&ten=${encodeURIComponent(ten)}`
    })
    .then(res => res.text())
    .then(data => {

        if (data === "success") {
            showFormMessage("addMessage", "Đã thêm thành công", "success");
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

/* ==========================
   POPUP YES / NO
   ========================== */
function showDeleteConfirm(ma) {
    const html = `
        <div class="confirm-box">
            <div class="confirm-content">
                <p>Bạn có chắc chắn muốn xóa không?</p>
                <div class="confirm-actions">
                    <button id="yesDelete">Yes</button>
                    <button id="noDelete">No</button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", html);

    document.getElementById("yesDelete").onclick = () => {
        fetch("theloai.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=delete&ma=${ma}`
        })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                loadTable();
            }
        });

        document.querySelector(".confirm-box").remove();
    };

    document.getElementById("noDelete").onclick = () => {
        document.querySelector(".confirm-box").remove();
    };
}

/* ==========================
   XÓA
   ========================== */
document.addEventListener("click", function(e) {
    let btn = e.target.closest(".btn-delete");

    if (btn) {
        let ma = btn.dataset.id;
        showDeleteConfirm(ma);
    }
});

/* ==========================
   MỞ FORM SỬA
   ========================== */
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

/* ==========================
   CẬP NHẬT LOẠI SÁCH
   ========================== */
document.getElementById("btnUpdate").addEventListener("click", () => {

    let ma = document.getElementById("editMaLoai").value;
    let ten = document.getElementById("editTenLoai").value.trim();

    if (ten === "") {
        showFormMessage("editMessage", "Tên loại không được để trống", "error");
        return;
    }

    fetch("theloai.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=update&ma=${ma}&ten=${encodeURIComponent(ten)}`
    })
    .then(res => res.text())
    .then(data => {
        if (data === "success") {
            showFormMessage("editMessage", "Cập nhật thành công", "success");
            loadTable();

        } else {
            showFormMessage("editMessage", "Có lỗi xảy ra", "error");
        }
    });
});

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
