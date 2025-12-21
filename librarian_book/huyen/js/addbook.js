document.addEventListener("DOMContentLoaded", function() {
    const btnUpload = document.querySelector(".btnupload");
    const inputFile = document.getElementById("hinhanh");
    const preview = document.getElementById("preview");
    const messageBox = document.getElementById("messageBox");
    const formAdd = document.querySelector(".fromadd");

    //ẩn thông báo khi người dùng nhập lại
    formAdd.querySelectorAll("input, select, textarea").forEach(element => {
        element.addEventListener("input",() => {
            messageBox.style.display = "none";
            messageBox.textContent = "";
            messageBox.className = "";
        });
    }); 
    btnUpload.addEventListener("click", function() {
        inputFile.click();
    });

    inputFile.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = "<img src='" + e.target.result + "' width='120'>";
            };
            reader.readAsDataURL(file);
        }
    });

    formAdd.addEventListener("submit", function(event) {
        event.preventDefault();

        // // // reset thông báo trước khi gửi
        // messageBox.style.display = "none";
        // messageBox.textContent = "";
        // messageBox.className = "";

        const formData = new FormData(formAdd);

        fetch("addbook.php", {   // gọi trực tiếp file xử lý PHP
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            const result = data.trim(); // loại bỏ khoảng trắng, xuống dòng

            if (result === "success") {
                messageBox.textContent = "Thêm thành công!";
                messageBox.className = "success";
                messageBox.style.display = "block";

                setTimeout(() => {
                    window.location.href = "danhmucsach.php";
                }, 1500);
            } else if (result === "duplicate") {
                messageBox.textContent = "Mã sách đã tồn tại, vui lòng nhập lại!";
                messageBox.className = "error";
                messageBox.style.display = "block";
            } else {
                messageBox.textContent = "Thêm sách không thành công!";
                messageBox.className = "error";
                messageBox.style.display = "block";
            }
        })
        .catch(error => {
            messageBox.textContent = "Có lỗi xảy ra khi gửi dữ liệu!";
            messageBox.className = "error";
            messageBox.style.display = "block";
            console.error("Lỗi:", error);
        });
    });
});
