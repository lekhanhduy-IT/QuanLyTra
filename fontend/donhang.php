<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập
$khachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang'] : null;

if (!$khachhang) {
    echo "Vui lòng đăng nhập để xem danh sách đơn hàng.";
    exit();
}

// Kết nối tới cơ sở dữ liệu
$host = 'localhost';
$db = 'qltra';
$user = 'root'; // Thay bằng tên người dùng DB của bạn
$pass = ''; // Thay bằng mật khẩu DB của bạn
$port ='3406';

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Lấy danh sách đơn hàng và hình ảnh đại diện cho mỗi đơn hàng
$makhachhang = $khachhang['makhachhang'];
$sql = "
    SELECT dh.madonhang, dh.ngaytao, dh.tongtien, dh.tinhtrang, MIN(sp.hinhanh1) AS hinhanh1, COUNT(DISTINCT ctdh.masanpham) AS so_loaisanpham
    FROM donhang dh
    JOIN chitietdonhang ctdh ON dh.madonhang = ctdh.madonhang
    JOIN sanpham sp ON ctdh.masanpham = sp.masanpham
    WHERE dh.makhachhang = $makhachhang
    GROUP BY dh.madonhang
    ORDER BY dh.ngaytao DESC";


$result = $mysqli->query($sql);
// Đếm số lượng đơn hàng của khách hàng hiện tại
$sql_count = "
    SELECT COUNT(*) AS so_donhang
    FROM donhang
    WHERE makhachhang = $makhachhang";
    
$result_count = $mysqli->query($sql_count);
$so_donhang = 0;

if ($result_count && $row = $result_count->fetch_assoc()) {
    $so_donhang = $row['so_donhang'];
}

// Lưu số lượng đơn hàng vào session để sử dụng trên các trang khác
$_SESSION['so_donhang'] = $so_donhang;

?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cartCount = localStorage.getItem('cart-count') || 0;
    document.getElementById('cart-count').innerText = cartCount;
});
</script>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đơn hàng</title>
</head>
<body>
<?php include('header.php');?>


<h3>Danh sách đơn hàng của bạn</h3>

<div class="order-list">
    <?php if ($result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="order-card">
                <img src="uploads/<?php echo $row['hinhanh1']; ?>" alt="Hình ảnh sản phẩm" class="product-image">
                <div class="order-header">Đơn hàng #DNNLL<?php echo $row['madonhang']; ?></div>
                <div class="order-total"> <?php echo $row['so_loaisanpham']; ?> sản phẩm</div>
                <div class="order-date">Ngày đặt: <?php echo date("d/m/Y H:i:s", strtotime($row['ngaytao'])); ?></div>
                <div class="order-total">Tổng tiền: <?php echo number_format($row['tongtien'], 0, ',', '.'); ?> VNĐ</div>
                <div class="order-status"> <?php echo $row['tinhtrang']; ?></div>
                <button class="details-button" onclick="xemChiTiet(<?php echo $row['madonhang']; ?>)">Xem chi tiết</button>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p class="no-orders">Bạn chưa có đơn hàng nào.</p>
    <?php } ?>
</div>

<script>
    function xemChiTiet(madonhang) {
        window.location.href = 'chitietdonhang.php?madonhang=' + madonhang;
    }
</script>
</body>
</html>
<style>
/* Tùy chỉnh thanh cuộn */
/* Áp dụng cho toàn bộ trang */
::-webkit-scrollbar {
    width: 8px; /* Độ rộng của thanh cuộn */
}

/* Phần nền của thanh cuộn */
::-webkit-scrollbar-track {
    background: white; /* Trong suốt */
}

/* Phần thanh cuộn */
::-webkit-scrollbar-thumb {
    background-color: rgba(0, 128, 0, 0.7); /* Màu xanh lá đậm mờ với độ trong suốt */
    border-radius: 10px; /* Góc bo tròn */
}

/* Thanh cuộn khi được hover */
::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 128, 0, 0.9); /* Tăng độ đậm khi hover */
}



body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: url('uploads/nenmo2.png') no-repeat center top/cover;
}

* {
    box-sizing: border-box; /* Đảm bảo padding và border không làm tăng kích thước */
    margin: 0;
    padding: 0;
}

        h3 {
            margin-top: 60px;
            margin-bottom: 10px;
            text-align: center;
            color: white;
        }

        .order-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .order-card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 10px;
            padding: 20px;
            width: 20%;
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-10px);
        }

        .order-header {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }

        .order-date, .order-total, .order-status {
            margin: 10px 0;
        }

        .order-status {
            font-weight: bold;
            color: #333;
        }

        .details-button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .details-button:hover {
            background-color: #45a049;
        }

        .no-orders {
            text-align: center;
            font-size: 18px;
            color: green;
        }

        .product-image {
            width: 100%;
            height: 200px;
            margin-bottom: 10px;
            margin-top: -0px;
            margin-right: -0px;
            margin-left: -0px;
            border-radius: 10px 10px 0  0;
        }
    </style>