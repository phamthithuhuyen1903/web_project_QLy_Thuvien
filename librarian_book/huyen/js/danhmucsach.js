
function loadTable() {
    fetch("danhmucsach.php?action=load")
    .then(res => res.text())
    .then(data => {
        document.getElementById("tablebook").innerHTML = data;
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

        fetch("danhmucsach.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                showPopupMessage( "Xóa thành công", "success");
                loadTable(); // tải lại bảng sau khi xóa
            } else {
                showPopupMessage("Xóa không thông", "error");
            }
        });

        box.style.display = "none"; // ản hộp sau khi xử lý
    };

    document.getElementById("noDelete").onclick = () => {
        box.style.display = "none"; // ản hộp nếu chọn No
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
        const imgSrc = data.image ? `../image/${data.image}` : "../image/default.jpg";
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
