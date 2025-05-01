<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltra";
$port ='3406';

$conn = new mysqli($servername, $username, $password, $dbname,  port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$masanpham = $_GET['masanpham'];

// Truy vấn sản phẩm theo masanpham
$sql = "SELECT * FROM sanpham WHERE masanpham = '$masanpham'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo '
    <div class="image">
        <img src="uploads/'.$row['hinhanh'].'" alt="'.$row['tensanpham'].'">
    </div>
    <div class="product-info">
        <h3>'.$row['tensanpham'].' / '.$row['trongluong'].' (g)</h3>
        <ul>
            <li>'.$row['mota1'].'</li>
            <li>'.$row['mota2'].'</li>
            <li>'.$row['mota3'].'</li>
            <li>'.$row['mota4'].'</li>
        </ul>
        <button class="order-btn">Đặt Hàng Ngay</button>
    </div>';
}
?>