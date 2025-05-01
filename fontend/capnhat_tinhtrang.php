<?php
// Kết nối tới cơ sở dữ liệu
$host = 'localhost';
$db = 'qltra';
$user = 'root'; // Thay bằng tên người dùng DB của bạn
$pass = ''; // Thay bằng mật khẩu DB của bạn
$port = '3406';
$mysqli = new mysqli($host, $user, $pass, $db, $port);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Lấy mã đơn hàng từ yêu cầu POST
$madonhang = isset($_POST['madonhang']) ? intval($_POST['madonhang']) : 0;
if ($madonhang == 0) {
    echo "Mã đơn hàng không hợp lệ.";
    exit();
}

// Cập nhật bảng `donhang` với tình trạng "Giao hàng thành công" và ngày tạo
$sql_update_donhang = "UPDATE donhang SET tinhtrang = 'Giao hàng thành công', ngaytao = NOW() WHERE madonhang = $madonhang";
if ($mysqli->query($sql_update_donhang) === TRUE) {
    // Cập nhật bảng `tinhtrang` với mã đơn hàng vừa cập nhật và ngày tạo
    $sql_insert_tinhtrang = "INSERT INTO tinhtrang (madonhang, tinhtrang, ngaytao) VALUES ($madonhang, 'Giao hàng thành công', NOW())";
    if ($mysqli->query($sql_insert_tinhtrang) === TRUE) {
        echo "Cập nhật tình trạng thành công.";
    } else {
        echo "Lỗi khi cập nhật bảng tinhtrang: " . $mysqli->error;
    }
} else {
    echo "Lỗi khi cập nhật bảng donhang: " . $mysqli->error;
}

$mysqli->close();
?>
