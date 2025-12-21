// document.addEventListener("DOMContentLoaded", () => {
//     const mainContent = document.getElementById("mainContent");

//     document.querySelectorAll("a[data-page]").forEach(link => {
//         link.addEventListener("click", function(e) {
//             e.preventDefault();
//             const page = this.getAttribute("data-page");

//             fetch(page)
//                 .then(res => res.text())
//                 .then(html => {
//                     mainContent.innerHTML = html;

//                     // Nếu file có JS riêng, nạp lại
//                     if (page.includes("danhmucsach.php")) {
//                         const script = document.createElement("script");
//                         script.src = "../js/danhmucsach.js";
//                         script.defer = true;
//                         document.body.appendChild(script);
//                     }
//                 })
//                 .catch(err => {
//                     mainContent.innerHTML = "<p>Lỗi tải nội dung</p>";
//                     console.error(err);
//                 });
//         });
//     });
// });
