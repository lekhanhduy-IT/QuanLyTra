<?php
session_start();

// Lưu thông báo vào cookie trước khi hủy session
setcookie('logout_success_message', "Đăng xuất thành công!", time() + 5, "/"); // Cookie tồn tại trong 5 giây

// Hủy session của người dùng
session_unset(); // Xóa tất cả các biến session
session_destroy(); // Hủy session

// Chuyển hướng người dùng về trang chủ (index.php)
header("Location: index.php");
exit();
?>
