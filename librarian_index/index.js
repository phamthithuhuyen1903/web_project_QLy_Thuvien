document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll('.loadPage');
    const iframe = document.getElementById('contentFrame');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Ngăn mở tab mới
            const url = this.getAttribute('href');
            iframe.src = url; // Load vào iframe
        });
    });
});
// document.addEventListener("DOMContentLoaded", function () {
//     const links = document.querySelectorAll('.menuLink');
//     const content = document.getElementById('content');

//     links.forEach(link => {
//         link.addEventListener('click', function (e) {
//             e.preventDefault();
//             const url = this.getAttribute('href');

//             fetch(url)
//                 .then(response => response.text())
//                 .then(html => {
//                     content.innerHTML = html;
//                     window.scrollTo({ top: content.offsetTop, behavior: 'smooth' });
//                 })
//                 .catch(err => {
//                     content.innerHTML = "<p>Lỗi tải nội dung.</p>";
//                 });
//         });
//     });
// });
