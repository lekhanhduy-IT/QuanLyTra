<?php
include '../config.php'; // Kết nối cơ sở dữ liệu

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['updates'])) {
    foreach ($data['updates'] as $update) {
        $matinhtrang = $update['matinhtrang'];
        $tinhtrang = $update['tinhtrang'];

        // Sử dụng prepared statement để bảo vệ chống SQL injection
        $stmt = $conn->prepare("UPDATE tinhtrang SET tinhtrang = ? WHERE matinhtrang = ?");
        $stmt->bind_param("si", $tinhtrang, $matinhtrang);
        $stmt->execute();
    }

    echo json_encode(['message' => 'Cập nhật thành công!']);
} else {
    echo json_encode(['message' => 'Không có dữ liệu để cập nhật!']);
}
?>
