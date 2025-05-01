<?php
session_start();
$khachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang']['makhachhang'] : null;

if (!$khachhang) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đặt hàng.']);
    exit();
}

$host = 'localhost';
$db = 'qltra';
$user = 'root'; // Thay thế bằng tên người dùng DB của bạn
$pass = ''; // Thay thế bằng mật khẩu DB của bạn
$port ='3406';
$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Nhận dữ liệu từ AJAX
$tongtien = $_POST['tongtien'];
$giohang = json_decode($_POST['giohang'], true);

// Lưu đơn hàng vào bảng donhang
$tinhtrang = "Đang xử lý";

// Sử dụng NOW() trong câu lệnh SQL
$stmt = $mysqli->prepare("INSERT INTO donhang (makhachhang, tongtien, ngaytao, tinhtrang) VALUES (?, ?, NOW(), ?)");
$stmt->bind_param("ids", $khachhang, $tongtien, $tinhtrang);
$stmt->execute();

$madonhang = $stmt->insert_id; // Lấy ID đơn hàng vừa tạo

// Lưu chi tiết đơn hàng vào bảng chitietdonhang và cập nhật sản phẩm
foreach ($giohang as $sanpham) {
    // Lưu chi tiết đơn hàng
    $stmt = $mysqli->prepare("INSERT INTO chitietdonhang (madonhang, masanpham, hinhanh1, tensanpham, soluong, tongtien) VALUES (?, ?, ?, ?, ?, ?)");
    $tongTienSanPham = $sanpham['gia'] * $sanpham['soluong'];
    $stmt->bind_param("iissid", $madonhang, $sanpham['masanpham'], $sanpham['hinhanh1'], $sanpham['tensanpham'], $sanpham['soluong'], $tongTienSanPham);
    $stmt->execute();

    // Cập nhật số lượng sản phẩm
    $stmt_update_quantity = $mysqli->prepare("UPDATE sanpham SET soluong = soluong - ? WHERE masanpham = ?");
    $stmt_update_quantity->bind_param("ii", $sanpham['soluong'], $sanpham['masanpham']);
    $stmt_update_quantity->execute();

    // Tăng luotmua của sản phẩm
    $stmt_update_sales = $mysqli->prepare("UPDATE sanpham SET luotmua = luotmua + ? WHERE masanpham = ?");
    $stmt_update_sales->bind_param("ii", $sanpham['soluong'], $sanpham['masanpham']);
    $stmt_update_sales->execute();
}

// Chèn vào bảng tinhtrang với NOW() cho cột ngaytao
$stmt_tinhtrang = $mysqli->prepare("INSERT INTO tinhtrang (madonhang, tinhtrang, ngaytao) VALUES (?, ?, NOW())");
$stmt_tinhtrang->bind_param("is", $madonhang, $tinhtrang);
$stmt_tinhtrang->execute();

echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công!']);
?>
