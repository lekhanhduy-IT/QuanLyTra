<?php
session_start();
$host = 'localhost';
$db = 'qltra';
$user = 'root'; // replace with your DB username
$pass = ''; // replace with your DB password
$port ='3406';

$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Kiểm tra xem có truyền masanpham không
if (isset($_GET['masanpham'])) {
    $masanpham = $mysqli->real_escape_string($_GET['masanpham']);
    
    // Lấy số lượng sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT soluong FROM sanpham WHERE masanpham = '$masanpham'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'soluong' => $row['soluong']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Mã sản phẩm không hợp lệ.']);
}

$mysqli->close();
?>
