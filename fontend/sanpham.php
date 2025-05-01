
<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập
// Lấy thông tin người dùng từ session
$khachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang'] : null; // Nếu không tìm thấy, gán giá trị null
?>
<?php
// Database connection
$host = 'localhost';
$db = 'qltra';
$user = 'root'; // replace with your DB username
$pass = ''; // replace with your DB password
$port ='3406';

$mysqli = new mysqli($host, $user, $pass, $db,$port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch products with ghim=0
$sql = "SELECT * FROM sanpham WHERE ghim = 0";
$result = $mysqli->query($sql);

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
    <meta name="description" content="Mua sắm trà ngon và chất lượng với nhiều loại khác nhau">
    <title>Sản phẩm trà - Quản lý trà</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <link href="css/style.css" rel="stylesheet">

</head>
<style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: url('uploads/nen6.png') no-repeat center top/cover;
}

* {
    box-sizing: border-box; /* Đảm bảo padding và border không làm tăng kích thước */
    margin: 0;
    padding: 0;
}


.product-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 120px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 20px;
}

.product-card {
    background: white;
    border-radius: 16px;
    margin: 15px;
    padding: 20px;
    width: calc(25% - 30px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.product-card img {
    max-width: 100%;
    height: 200px;
    border-radius: 10px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover img {
    transform: scale(1.05);
    cursor: pointer;
}

.product-card h4 {
    font-size: 1.2em;
    margin: 15px 0;
    color: #333;
}

.product-card p {
    font-size: 0.95em;
    color: #666;
    margin: 10px 0;
}

.product-card .buy-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 50px;
    transition: background-color 0.3s;
    font-weight: bold;
}

.product-card .buy-button:hover {
    background-color: #45a049;
    cursor: pointer;
}
.tuychon {
    display: flex;
    justify-content: space-between; /* Căn về hai đầu trái và phải */
    align-items: center; /* Căn giữa theo chiều dọc */
    width: 100%;
    text-align: justify;
}



.giohang img {
    text-align: center;
    width: 45px;
    height:45px ;
    border-radius: 50px;
}
.giohang:hover {
    cursor: pointer;
}
.product-card {
    transition: transform 1s, opacity 1s;
}

    </style>

<body>
<?php include('header.php');?>
    <main>

        <div class="product-container">
        <?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        echo "<div class='product-card' data-masanpham='" . $row['masanpham'] . "'>";
        echo "<img src='uploads/" . $row['hinhanh1'] . "' alt='" . $row['tensanpham'] . "' onclick=\"window.location.href='chitietsanpham.php?masanpham=" . $row['masanpham'] . "'\">";
        echo "<h4>" . $row['tensanpham'] . "<span class='highlight'> ".  number_format($row['trongluong'],0)."g</span></h4>";
        echo "<p style='color: red;'>Giá: " . number_format($row['gia'], 0) . " VNĐ</p>";
        $mota = $row['mota1'];
        $mota_clean = preg_replace('/[^\P{C}]+/u', '', $mota); // Loại bỏ các ký tự điều khiển
echo "<p>" . (strlen($mota_clean) > 30 ? htmlspecialchars(substr($mota_clean, 0, 30)) . '...' : htmlspecialchars($mota_clean)) . "</p>";

        // Thêm liên kết tới trang chitietsanpham.php
        echo "<div class='tuychon'>";

        // Thay đổi liên kết để điều hướng đến trang giohang.php với masanpham
        echo "<a class='buy-button' onclick='muaNgay(" . $row['masanpham'] . ", \"" . $row['tensanpham'] . "\", " . $row['gia'] . ", \"" . $row['hinhanh1'] . "\", " . $row['soluong'] . ")'>Mua ngay</a>";

        echo "<a class='giohang' onclick='themVaoGioHang(" . $row['masanpham'] . ", \"" . $row['tensanpham'] . "\", " . $row['gia'] . ", \"" . $row['hinhanh1'] . "\", " . $row['soluong'] . ")'><img src='uploads/tim.png'></a>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>Không có sản phẩm nào.</p>";
}
$mysqli->close();
?>


        </div>
    </main>

    <script>
// Hàm thêm sản phẩm vào giỏ hàng
function themVaoGioHang(masanpham, tensanpham, gia, hinhanh1, soluong, event) {
    let khachhang = <?php echo json_encode($_SESSION['khachhang']['makhachhang'] ?? null); ?>;
    if (!khachhang) {
        alert("Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.");
        return;
    }

    // Lấy giỏ hàng từ localStorage
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang)) || [];

    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    let sanpham = gioHang.find(sp => sp.masanpham === masanpham);
    if (sanpham) {
        if (sanpham.soluong < soluong) {
            sanpham.soluong += 1; // Tăng số lượng sản phẩm trong giỏ
        } else {
            alert("Sản phẩm này đã đạt số lượng tối đa!");
            return; // Thoát nếu số lượng tối đa đã đạt
        }
    } else {
        gioHang.push({
            masanpham: masanpham,
            tensanpham: tensanpham,
            gia: gia,
            hinhanh1: hinhanh1,
            soluong: 1 // Bắt đầu với số lượng là 1
        });
    }

    // Lưu lại giỏ hàng vào localStorage
    localStorage.setItem('giohang_' + khachhang, JSON.stringify(gioHang));

    // Tạo hiệu ứng bay
    const productCard = document.querySelector(`.product-card[data-masanpham="${masanpham}"]`);
    const clone = productCard.cloneNode(true);
    document.body.appendChild(clone);
    clone.style.position = 'absolute';
    clone.style.zIndex = 1000;
    clone.style.transition = 'transform 1s ease, opacity 1s ease';
    clone.style.opacity = 0.8;

    // Đặt vị trí của clone tại vị trí của sản phẩm
    const rect = productCard.getBoundingClientRect();
    clone.style.top = `${rect.top}px`;
    clone.style.left = `${rect.left}px`;

    // Xác định vị trí của cart-count
    const cartCount = document.getElementById('cart-count');
    const cartRect = cartCount.getBoundingClientRect();

    // Tính toán vị trí đích
    const destinationX = cartRect.left + (cartRect.width / 2);
    const destinationY = cartRect.top + (cartRect.height / 2);

    // Tính toán khoảng cách để thu nhỏ
    const distanceX = destinationX - rect.left;
    const distanceY = destinationY - rect.top;
    const distance = Math.sqrt(distanceX * distanceX + distanceY * distanceY);
    const scale = Math.max(0.5, 1 - (distance / 200));

    // Đặt transform để clone bay và thu nhỏ về vị trí cart-count
    clone.style.transform = `translate(${distanceX}px, ${distanceY}px) scale(${scale})`;

    setTimeout(() => {
        clone.remove();
        // Cập nhật số lượng giỏ hàng
        cartCount.innerText = parseInt(cartCount.innerText) + 1;

        // Nếu nhấn vào buy-button, chuyển hướng đến giohang.php
        if (event.target.classList.contains('buy-button')) {
            window.location.href = 'giohang.php';
        } else {
            // Hiển thị thông báo nếu không phải từ buy-button
            alert("Đã thêm sản phẩm vào giỏ hàng!");
        }
    }, 1000);
}
</script>

<script>
    // Hàm mua ngay sản phẩm và chuyển hướng đến trang giỏ hàng
function muaNgay(masanpham, tensanpham, gia, hinhanh1, soluong) {
    let khachhang = <?php echo json_encode($_SESSION['khachhang']['makhachhang'] ?? null); ?>;
    if (!khachhang) {
        alert("Bạn cần đăng nhập để mua sản phẩm.");
        return;
    }

    // Lấy giỏ hàng từ localStorage
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang)) || [];

    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    let sanpham = gioHang.find(sp => sp.masanpham === masanpham);
    if (sanpham) {
        if (sanpham.soluong < soluong) {
            sanpham.soluong += 1; // Tăng số lượng sản phẩm trong giỏ
        } else {
            alert("Sản phẩm này đã đạt số lượng tối đa!");
            return; // Thoát nếu số lượng tối đa đã đạt
        }
    } else {
        gioHang.push({
            masanpham: masanpham,
            tensanpham: tensanpham,
            gia: gia,
            hinhanh1: hinhanh1,
            soluong: 1 // Bắt đầu với số lượng là 1
        });
    }

    // Lưu lại giỏ hàng vào localStorage
    localStorage.setItem('giohang_' + khachhang, JSON.stringify(gioHang));

    // Chuyển hướng thẳng đến trang giohang.php
    window.location.href = 'giohang.php';
}

</script>

<?php include('footer.php');?>
</body>
</html>
