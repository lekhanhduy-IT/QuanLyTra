<?php
$servername = "localhost";
$username = "root"; // Tên người dùng cơ sở dữ liệu
$password = ""; // Mật khẩu cơ sở dữ liệu
$dbname = "qltra"; // Tên cơ sở dữ liệu
$port ='3406';
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem 'id' đã được truyền qua URL hay chưa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Chuẩn bị truy vấn để xóa sản phẩm
    $delete_query = "DELETE FROM sanpham WHERE masanpham = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    // Thực thi truy vấn
    if ($stmt->execute()) {
        // Xóa thành công, chuyển hướng về trang sanpham.php
        header("Location: sanpham.php");
        exit();
    } else {
        echo "Lỗi khi xóa sản phẩm: " . $conn->error;
    }

    // Đóng statement
    $stmt->close();
} else {
    echo "ID sản phẩm không hợp lệ.";
}

// Đóng kết nối
$conn->close();
?>
