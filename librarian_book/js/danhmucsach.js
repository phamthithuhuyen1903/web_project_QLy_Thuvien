
function loadTable() {
    fetch("danhmucsach.php?action=load")
    .then(res => res.text())
    .then(data => {
        document.getElementById("tablebook").innerHTML = data;
    });
}
loadTable(); 

// Bắt sự kiện click nút xóa
document.addEventListener("click", function (e) {
    let btn = e.target.closest(".btn-delete");
    if (btn) {
        let ma = btn.dataset.id;
        showDeleteConfirm(ma);
    }
});

/* Hàm xác nhận xóa */
function showDeleteConfirm(ma) {
    const box = document.getElementById("deleteConfirmBox");
    box.style.display = "flex"; // hiện hộp xác nhận

    // Nếu chọn Yes
    document.getElementById("yesDelete").onclick = () => {
        let formData = new FormData();
        formData.append("action", "delete");
        formData.append("ma", ma);

        fetch("danhmucsach.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            data = data.trim(); // loại bỏ khoảng trắng thừa

            if (data === "success") {
                showPopupMessage("✔️ Xóa thành công", "success");
                loadTable(); // tải lại bảng sau khi xóa
            } else if (data === "not_allowed") {
                showPopupMessage("❌ Không thể xóa sách vì đang tồn tại trong phiếu mượn hoặc yêu thích.", "error");
            } else {
                showPopupMessage("❌ Xóa không thành công", "error");
            }
        })
        .catch(err => {
            console.error("Lỗi khi xóa:", err);
            showPopupMessage("❌ Lỗi kết nối server!", "error");
        });

        box.style.display = "none"; // ẩn hộp sau khi xử lý
    };

    // Nếu chọn No
    document.getElementById("noDelete").onclick = () => {
        box.style.display = "none"; // ẩn hộp nếu chọn No
    };
}

/* Hàm hiển thị popup thông báo */
function showPopupMessage(message, type = "success") {
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

    fetch("danhmucsach.php?action=search&keyword=" + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(html => {
            document.getElementById("tablebook").innerHTML = html;
        })
        .catch(err => console.error("Lỗi tìm kiếm:", err));
});

// XEM THÔNG TIN SÁCH
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("viewbook");     
  const closeBtn = document.getElementById("closeView"); 
  const tableBody = document.getElementById("tablebook");
  

  // Bắt sự kiện click nút xem
  tableBody.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-view");
    if (!btn) return;

    e.preventDefault();
    const url = btn.getAttribute("href");

    fetch(url)
      .then(res => {
        if (!res.ok) throw new Error("Network response was not ok");
        return res.json();
      })
      .then(data => {
        // Gán dữ liệu vào form chi tiết
        document.getElementById("tensach").textContent = data.ten_sach || "";
        document.getElementById("tacgia").textContent = data.ten_tg || "";
        document.getElementById("nxb").textContent = data.nha_xb || "";
        document.getElementById("namxb").textContent = data.nam_xb || "";
        document.getElementById("soluong").textContent = data.so_luong || "";
        document.getElementById("tinhtrang").textContent =
          data.tinh_trang == 1 ? "Còn" : "Hết";
        document.getElementById("mota").textContent = data.mo_ta || "";

        // Ảnh sách
        const img = document.getElementById("image");
        const imgSrc = data.image ? `../../image/${data.image}` : "../../image/default.jpg";
        img.src = imgSrc;

        // Hiển thị modal
        modal.style.display = "block";
      })
      .catch(err => {
        console.error("Lỗi khi tải thông tin sách:", err);
        alert("Không thể tải thông tin sách.");
      });
  });

  // Đóng modal khi click nút đóng
  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Đóng modal khi click ra ngoài
  window.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
});
// Load bảng sách
function loadTable() {
    fetch("danhmucsach.php?action=load")
    .then(response => response.text())
    .then(html => {
        document.getElementById("tablebook").innerHTML = html;
    });
}

// Cập nhật số lượng khi mượn/trả
function updateQuantity(ma_sach, soluong, type) {
    fetch("danhmucsach.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=updateQuantity&ma=" + ma_sach + "&soluong=" + soluong + "&type=" + type
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            alert("Cập nhật số lượng thành công!");
            loadTable(); // gọi lại để refresh bảng
        } else if (data.trim() === "error_not_enough") {
            alert("Không đủ sách để mượn!");
        } else {
            alert("Lỗi khi cập nhật số lượng!");
        }
    })
    .catch(error => {
        console.error("Có lỗi xảy ra:", error);
        alert("Không thể kết nối đến server!");
    });
}

// Khi trang load lần đầu, hiển thị bảng sách
document.addEventListener("DOMContentLoaded", function() {
    loadTable();
});

