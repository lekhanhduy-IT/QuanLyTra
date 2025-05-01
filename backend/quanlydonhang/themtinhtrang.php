<?php
include '../config.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tinhtrang'])) {
    $tinhtrang = $_POST['tinhtrang'];
    
    // Tránh SQL Injection
    $stmt = $conn->prepare("INSERT INTO tinhtrang (tinhtrang) VALUES (?)");
    $stmt->bind_param("s", $tinhtrang);
   $stmt->execute();
    $stmt->close();
}
?>
