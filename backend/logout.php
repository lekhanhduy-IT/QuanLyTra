<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Địa chỉ máy chủ
$username = "root"; // Tên người dùng
$password = ""; // Mật khẩu
$dbname = "qltra"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Cập nhật cột trang_thai
$sql = "UPDATE admin SET trang_thai = 0 WHERE id = 1";

if ($conn->query($sql) === TRUE) {
    // Xuất mã JavaScript để mở tab mới mà không bị giới hạn trong iframe
    echo '<script>
            // Kiểm tra nếu trang đang nằm trong một iframe
            if (window.top !== window.self) {
                // Điều hướng toàn bộ cửa sổ đến trang đăng nhập, thoát khỏi iframe
                window.top.location.href = "dangnhap.php";
            } else {
                // Nếu không trong iframe, điều hướng thông thường
                window.location.href = "dangnhap.php";
            }
          </script>';
} else {
    echo "Lỗi: " . $conn->error;
}

// Đóng kết nối
$conn->close();
?>
