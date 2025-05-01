<?php session_start(); ?>
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
    <title>Chi tiết đơn hàng</title>

</head>
<body>

<div class="order-details">
<?php
// Kiểm tra nếu người dùng đã đăng nhập
$khachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang'] : null;

if (!$khachhang) {
    echo "Vui lòng đăng nhập để xem chi tiết đơn hàng.";
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

// Lấy mã đơn hàng từ URL
$madonhang = isset($_GET['madonhang']) ? intval($_GET['madonhang']) : 0;
if ($madonhang == 0) {
    echo "Mã đơn hàng không hợp lệ.";
    exit();
}
// Truy vấn để lấy thông tin tình trạng đơn hàng
$sql_order_status = "SELECT tinhtrang FROM donhang WHERE madonhang = $madonhang";
$result_order_status = $mysqli->query($sql_order_status);

$tinhtrang = '';
if ($result_order_status->num_rows > 0) {
    $row_order_status = $result_order_status->fetch_assoc();
    $tinhtrang = $row_order_status['tinhtrang'];
}

// Truy vấn để lấy thông tin sản phẩm chi tiết đơn hàng
$sql = "
    SELECT sp.masanpham, sp.tensanpham, sp.hinhanh1, ctdh.soluong, ctdh.tongtien
    FROM chitietdonhang ctdh
    JOIN sanpham sp ON ctdh.masanpham = sp.masanpham
    WHERE ctdh.madonhang = $madonhang";

$result = $mysqli->query($sql);

// Lấy chi tiết đơn hàng
$sql = "
    SELECT sp.masanpham, sp.tensanpham, sp.hinhanh1, ctdh.soluong, ctdh.tongtien
    FROM chitietdonhang ctdh
    JOIN sanpham sp ON ctdh.masanpham = sp.masanpham
    WHERE ctdh.madonhang = $madonhang";
$result = $mysqli->query($sql);

 if ($result->num_rows > 0) { ?>
             <h4>Chi tiết đơn hàng #DNNLL<?php echo $madonhang; ?></h4>

             <?php while ($row = $result->fetch_assoc()) { ?>
    <div class="order-item">
        <img src="uploads/<?php echo $row['hinhanh1']; ?>" alt="Hình ảnh sản phẩm">
        <div class="order-info">
            <h2><?php echo $row['tensanpham']; ?></h2>

            <p>Số lượng: <?php echo $row['soluong']; ?></p>
            <p>Đơn giá: <?php echo number_format($row['tongtien'], 0, ',', '.'); ?> VNĐ</p>
        </div>
        <div style="text-align: right;">
    <div class="order-price">
        Tổng 
        <?php 
            $thanhtien = $row['soluong'] * $row['tongtien'];
            echo number_format($thanhtien, 0, ',', '.'); 
        ?> VNĐ
    </div>

    <div>
        <!-- Nút đánh giá cho từng sản phẩm -->
        <?php if ($tinhtrang === 'Giao hàng thành công') { ?>
            <button class="review-button" data-id="<?php echo $row['masanpham']; ?>" onclick="openReviewForm(<?php echo $row['masanpham']; ?>)">Đánh giá</button>
        <?php } ?>
    </div>
</div>

    </div>
<?php } ?>


    <?php } else { ?>
        <p class="no-items">Không có sản phẩm nào trong đơn hàng này.</p>
    <?php } ?>
</div>

<div style="text-align:center;display:flex; align-items:center; justify-content:center;">
<?php
// Kiểm tra tình trạng đơn hàng trong bảng `donhang`
$sql_tinhtrang = "SELECT tinhtrang FROM donhang WHERE madonhang = $madonhang";
$result_tinhtrang = $mysqli->query($sql_tinhtrang);
?>
<div class="tuychon">
    <?php
if ($result_tinhtrang->num_rows > 0) {
    $row_tinhtrang = $result_tinhtrang->fetch_assoc();
    // Kiểm tra nếu `tinhtrang` là "Giao hàng thành công"

    if (strpos($row_tinhtrang['tinhtrang'], 'Giao hàng thành công') !== false) {
   //     echo '<button class="review-button" onclick="openReviewForm(' . $madonhang . ')">Đánh giá</button>';
    } 
    // Kiểm tra nếu `tinhtrang` là "Đang trên đường giao đến bạn" hoặc "Đang giao hàng"
    elseif (strpos($row_tinhtrang['tinhtrang'], 'Đang trên đường giao đến bạn') !== false || 
        strpos($row_tinhtrang['tinhtrang'], 'Đang giao hàng') !== false) {
        echo '<button class="back-button" onclick="daNhanHang(' . $madonhang . ')">Đã nhận được hàng</button>';
      
    }
   
}
?>
    <button class="back-button" onclick="window.history.back()">Quay lại</button>
</div>
    <?php
?>

<!-- Form đánh giá (ẩn ban đầu) -->
<!-- Form đánh giá (ẩn ban đầu) -->
<div id="reviewForm" style="display:none; text-align: center;">
    <h3>Đánh giá sản phẩm</h3>
    <form id="submitReviewForm" method="post" action="insert.php" enctype="multipart/form-data">
        <input type="hidden" id="madonhang" name="madonhang" value="<?php echo $madonhang; ?>" required>
        <input type="hidden" id="masanpham" name="masanpham" value="<?php echo $masanpham; ?>"" required> <!-- Khởi tạo giá trị masanpham rỗng -->
        <div class="star-rating">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <input type="hidden" id="sosao" name="sosao" value="0" required><br><br>

        <label for="noidung">Nội dung đánh giá:</label><br>
        <textarea id="noidung" name="noidung" required></textarea><br><br>
        
        <div class="image-preview">
            <img id="imagePreview" src="uploads/imup.png" alt="Xem trước ảnh" style="max-width: 100%; height: auto; display: none;">
        </div>
        
        <label for="hinhanh"></label>
        <div class="tuychon">
            <label class="image-upload">
                <input type="file" id="hinhanh" name="hinhanh" accept="image/*" onchange="previewImage(event)">
                Chọn ảnh
            </label>

            <button type="submit" name="submitReview">Gửi đánh giá</button>
        </div>
    </form>
    <div id="message" style="display:none; color: green;">Đánh giá của bạn đã được gửi thành công!</div>
</div>


<script>
    function openReviewForm(masanpham) {
    document.getElementById('masanpham').value = masanpham; // Lưu masanpham vào input ẩn
    document.getElementById('reviewForm').style.display = 'block'; // Hiện form đánh giá
}

const stars = document.querySelectorAll('.star');
stars.forEach(star => {
    star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        document.getElementById('sosao').value = value; // Lưu số sao vào input ẩn

        // Đổi màu sao
        stars.forEach(s => {
            s.style.color = s.getAttribute('data-value') <= value ? 'gold' : 'gray';
        });
    });
});

function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        imagePreview.src = e.target.result;
        imagePreview.style.display = 'block'; // Hiển thị hình ảnh xem trước
    }
    reader.readAsDataURL(file);
}

</script>

<script>
function daNhanHang(madonhang) {
    // Gửi yêu cầu AJAX để cập nhật trạng thái đơn hàng
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "capnhat_tinhtrang.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert('Bạn đã xác nhận nhận được hàng.');
            // Có thể thêm mã để cập nhật giao diện hoặc chuyển hướng
        }
    };
    xhr.send("madonhang=" + madonhang);
}
</script>


</div>

</body>
</html>

