function loadContent(url) {
  fetch(url)
    .then(res => res.text())
    .then(html => {
      document.getElementById("mainContent").innerHTML = html;
    });
}

window.onload = function () {
  document.getElementById("linkTheLoai").onclick = function (e) {
    e.preventDefault(); // không chuyển trang
    loadContent("theloai.php"); // tải nội dung vào khung
  };

  document.getElementById("linkDanhMuc").onclick = function (e) {
    e.preventDefault();
    loadContent("danhmucsach.php");
  };
};
