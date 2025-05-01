<?php
include '../config.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matinhtrang = $_POST['matinhtrang'];

    // Xóa tình trạng
    $sql = "DELETE FROM tinhtrang WHERE matinhtrang = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $matinhtrang);
    $stmt->execute();

    echo json_encode(['status' => 'success']);
}
?>
