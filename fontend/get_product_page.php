<?php
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
$page = $_GET['page'];
$limit = 4;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM sanpham WHERE ghim = 0 LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '
        <div class="productitem">
            <img src="uploads/'.$row['hinhanh'].'" alt="'.$row['tensanpham'].'" data-masanpham="'.$row['masanpham'].'">
            <h4>'.$row['tensanpham'].' / '.$row['trongluong'].' (g)</h4>
            <span class="price">'.$row['gia'].' VND</span>
        </div>';
    }
}
?>