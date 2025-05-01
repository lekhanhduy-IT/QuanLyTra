// Kiểm tra trạng thái đăng nhập từ localStorage
document.addEventListener('DOMContentLoaded', function() {
    const isLoggedIn = localStorage.getItem('isLoggedIn');

    // Nếu chưa đăng nhập
    if (!isLoggedIn) {
        // Vô hiệu hóa click trên các thẻ a, button, div trong toàn bộ backend, trừ form đăng nhập
        document.querySelectorAll('a, button, div').forEach(function(element) {
            // Kiểm tra xem phần tử có thuộc form đăng nhập không
            if (!element.closest('.login-form')) {
                element.addEventListener('click', function(event) {
                    event.preventDefault(); // Ngăn chặn thao tác click
                    showMessage(event); // Hiển thị thông báo yêu cầu đăng nhập
                });
            }
        });
    }
});

// Hàm hiển thị thông báo yêu cầu đăng nhập tại vị trí click
function showMessage(event) {
    const message = document.createElement('span');
    message.textContent = "Bạn cần đăng nhập";
    message.style.color = 'red';
    message.style.position = 'absolute';
    message.style.left = event.pageX + 'px';
    message.style.top = event.pageY + 'px';
    message.style.background = 'rgba(255, 255, 255, 0.9)';
    message.style.padding = '5px';
    message.style.borderRadius = '5px';
    message.style.border = '1px solid red';
    message.style.zIndex = '1000';
    
    document.body.appendChild(message);

    // Xóa thông báo sau 2 giây
    setTimeout(function() {
        document.body.removeChild(message);
    }, 2000);
}
