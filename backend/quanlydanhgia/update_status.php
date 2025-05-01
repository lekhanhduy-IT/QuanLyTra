<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltra";
$port ='3406';
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tinhtrang = $_POST['trangthai'];

    // Cập nhật tình trạng
    $sql_update = "UPDATE danhgia SET trangthai = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ii", $tinhtrang, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header('Location: danhgia.php');
    } else {
        echo "Không có thay đổi nào";
    }

    $stmt->close();
}

$conn->close();
?>
