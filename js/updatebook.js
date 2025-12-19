document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".fromadd");
    if (!form) return;

    // Hộp thông báo
    const messageBox = document.createElement("div");
    messageBox.id = "messageBox";
    messageBox.style.display = "none";
    messageBox.style.position = "fixed";
    messageBox.style.top = "20px";
    messageBox.style.left = "50%";
    messageBox.style.transform = "translateX(-50%)";
    messageBox.style.background = "#4CAF50";
    messageBox.style.color = "#fff";
    messageBox.style.padding = "10px 20px";
    messageBox.style.borderRadius = "5px";
    document.body.appendChild(messageBox);

    // Xử lý nút Upload
    const btnUpload = document.getElementById("btnUpload");
    const fileInput = document.getElementById("fileInput");

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
                    const imgPreview = document.querySelector(".image img");
                    if (imgPreview) {
                        imgPreview.src = ev.target.result;
                    } else {
                        const newImg = document.createElement("img");
                        newImg.src = ev.target.result;
                        newImg.width = 100;
                        document.querySelector(".image").appendChild(newImg);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Xử lý submit form
    form.addEventListener("submit", e => {
        e.preventDefault();
        const formData = new FormData(form);

        fetch("updatebook.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") {
                messageBox.innerText = "Cập nhật thành công!";
                messageBox.style.background = "#4CAF50";
                messageBox.style.display = "block";

                setTimeout(() => {
                    messageBox.style.display = "none";
                    window.location.href = "danhmucsach.php";
                }, 2000);
            } else {
                messageBox.innerText = "Cập nhật thất bại!";
                messageBox.style.background = "#f44336";
                messageBox.style.display = "block";
            }
        })
        .catch(err => {
            messageBox.innerText = "Có lỗi xảy ra: " + err.message;
            messageBox.style.background = "#f44336";
            messageBox.style.display = "block";
        });
    });
});
