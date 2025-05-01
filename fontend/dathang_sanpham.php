<?php
session_start();
$khachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang'] : null;
if (!$khachhang) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập trước khi đặt hàng.']);
    exit();
}

// Kết nối cơ sở dữ liệu
$host = 'localhost';
$db = 'qltra';
$user = 'root'; // Thay thế bằng tên người dùng DB của bạn
$pass = ''; // Thay thế bằng mật khẩu DB của bạn
$port ='3406';
$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Nhận dữ liệu từ yêu cầu Ajax
$masanpham = $_POST['masanpham'];
$soluong = $_POST['soluong'];
$tongtien = $_POST['tongtien'];

// Truy vấn thông tin sản phẩm từ bảng sanpham
$stmt = $mysqli->prepare("SELECT hinhanh1, tensanpham, soluong FROM sanpham WHERE masanpham = ?");
$stmt->bind_param("i", $masanpham);
$stmt->execute();
$stmt->bind_result($hinhanh1, $tensanpham, $currentQuantity);
$stmt->fetch();
$stmt->close();

if (!$tensanpham) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
    exit();
}

if ($currentQuantity < $soluong) {
    echo json_encode(['success' => false, 'message' => 'Số lượng yêu cầu vượt quá số lượng có sẵn.']);
    exit();
}

// Insert vào bảng donhang
$stmt = $mysqli->prepare("INSERT INTO donhang (makhachhang, tongtien, ngaytao, tinhtrang) VALUES (?, ?, NOW(), 'Đang xử lý')");
$stmt->bind_param("id", $khachhang['makhachhang'], $tongtien);
if ($stmt->execute()) {
    // Lấy id đơn hàng vừa tạo
    $madonhang = $stmt->insert_id;

    // Lấy giá trị tinhtrang sau khi insert vào bảng donhang
    $tinhtrang = 'Đang xử lý'; // Giá trị đã đặt trong câu lệnh INSERT

    // Insert vào bảng chitietdonhang (thêm hinhanh1 và tensanpham)
    $stmt2 = $mysqli->prepare("INSERT INTO chitietdonhang (madonhang, masanpham, soluong, tongtien, hinhanh1, tensanpham) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("iiidss", $madonhang, $masanpham, $soluong, $tongtien, $hinhanh1, $tensanpham);
    $stmt2->execute();

    // Cập nhật số lượng sản phẩm trong bảng sanpham
    $stmt_update_quantity = $mysqli->prepare("UPDATE sanpham SET soluong = soluong - ? WHERE masanpham = ?");
    $stmt_update_quantity->bind_param("ii", $soluong, $masanpham);
    $stmt_update_quantity->execute();

    // Tăng lượt mua của sản phẩm
    $stmt_update_sales = $mysqli->prepare("UPDATE sanpham SET luotmua = luotmua + ? WHERE masanpham = ?");
    $stmt_update_sales->bind_param("ii", $soluong, $masanpham);
    $stmt_update_sales->execute();

    // Insert vào bảng tinhtrang
    $stmt_tinhtrang = $mysqli->prepare("INSERT INTO tinhtrang (madonhang, tinhtrang, ngaytao) VALUES (?, ?, NOW())");
    $stmt_tinhtrang->bind_param("is", $madonhang, $tinhtrang);
    $stmt_tinhtrang->execute();

    echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi đặt hàng.']);
}
?>
