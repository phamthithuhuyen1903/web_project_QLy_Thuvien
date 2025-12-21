document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".formupdate");
    const messageBox = document.getElementById("messageBox");
    const btnUpload = document.getElementById("btnUpload");
    const fileInput = document.getElementById("fileInput");

    if (!form) return;

    // ===== Xử lý nút Upload =====
    if (btnUpload && fileInput) {
        btnUpload.addEventListener("click", () => {
            fileInput.click();
        });

        // Preview ảnh sau khi chọn
        fileInput.addEventListener("change", e => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    let imgPreview = document.querySelector(".image img");
                    if (imgPreview) {
                        imgPreview.src = ev.target.result;
                    } else {
                        const newImg = document.createElement("img");
                        newImg.src = ev.target.result;
                        newImg.style.maxWidth = "100%";
                        newImg.style.maxHeight = "100%";
                        document.querySelector(".image").appendChild(newImg);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // ===== Xử lý submit form =====
    form.addEventListener("submit", e => {
        e.preventDefault();
        const formData = new FormData(form);

        fetch("updatebook.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            messageBox.style.display = "block";
            messageBox.className = ""; // reset class

            if (data.trim() === "success") {
                messageBox.innerText = "Cập nhật thành công!";
                messageBox.classList.add("success");
                setTimeout(() => {
                    window.location.href = "danhmucsach.php";
                }, 1500);
            } else {
                messageBox.innerText = "Cập nhật thất bại!";
                messageBox.classList.add("error");
            }
        })
        .catch(err => {
            messageBox.style.display = "block";
            messageBox.className = "";
            messageBox.innerText = "Có lỗi xảy ra: " + err.message;
            messageBox.classList.add("error");
        });
    });
});
