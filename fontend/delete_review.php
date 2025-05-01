<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập
if (!isset($_SESSION['khachhang'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Lấy dữ liệu từ request
$data = json_decode(file_get_contents('php://input'), true);
$reviewId = isset($data['reviewId']) ? intval($data['reviewId']) : 0;

// Kiểm tra ID hợp lệ
if ($reviewId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid review ID']);
    exit;
}

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltra";
$port ='3406';

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn để xóa đánh giá
$sql = "DELETE FROM danhgia WHERE id = ? AND makhachhang = ?";
$stmt = $conn->prepare($sql);

// Giả định rằng bạn đã lưu ID của khách hàng trong session
$makhachhang = $_SESSION['khachhang']['makhachhang'];
$stmt->bind_param("is", $reviewId, $makhachhang);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Review deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete review']);
}

$stmt->close();
$conn->close();
?>
