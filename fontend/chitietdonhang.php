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
    <style>
/* Đảm bảo thanh cuộn luôn xuất hiện */
body {
    overflow-y: scroll;
}

/* Áp dụng cho toàn bộ trang */
::-webkit-scrollbar {
    width: 8px; /* Độ rộng của thanh cuộn */
}

/* Phần nền của thanh cuộn */
::-webkit-scrollbar-track {
    background: white; /* Nền trắng */
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
    padding-top:80px;
    padding-bottom:20px;
    background: url('uploads/nenmo2.png') no-repeat center top/cover;

}

* {
    box-sizing: border-box; /* Đảm bảo padding và border không làm tăng kích thước */
    margin: 0;
    padding: 0;
}


        h4 {
            text-align: center;
            color: white;
            margin-bottom: 10px;
            
        }

        .order-details {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .order-item {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 10px;
            padding: 20px;
            width: 90%;
            max-width: 800px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            display: flex;
            justify-content: space-between; /* Căn về hai đầu trái và phải */
            align-items: center; /* Căn giữa theo chiều dọc */

            
        }

        .or-der{
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 0 auto;
            margin-bottom: 20px;
            padding: 20px;
            width: 90%;
            max-width: 800px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size:13px;
            color: green;
        }
        .order-item img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .order-item img:hover {cursor: pointer;}

        .order-info {
            flex: 1;
            padding-left: 20px;
        }

        .order-info h2 {
            margin: 0;
            font-size: 18px;
            color: #4CAF50;
        }

        .order-info p {
            margin: 5px 0;
        }

        .order-price {
            font-weight: bold;
            color: red;
        }

        .no-items {
            text-align: center;
            font-size: 18px;
            color: #888;
        }

        .back-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            margin-right: 10px;
        }

        .back-button:hover {
            background-color: #45a049;
        }

#reviewForm {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    z-index: 2000;
    top: 150px;
    left:35%;
    width: 400px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
    position:fixed;
    border: 1px solid #4CAF50;
}

h3 {
    color: #4CAF50; /* Màu xanh lá */
}

.star-rating {
    direction: ltr;
    font-size: 2em;
    unicode-bidi: bidi-override;
    display: inline-block;
}

.star {
    cursor: pointer;
    color: #ccc; /* Màu mặc định cho các ngôi sao chưa chọn */
    transition: color 0.2s;
}


.star.selected,
,
.star:hover ~ .star {
    color: gold;
}

textarea {
    width: calc(100% - 20px);
    height: 80px;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
    font-size: 1em;
}

.image-upload {
    cursor: pointer;
    color: #4CAF50; /* Màu xanh lá */
    border: 2px dashed #4CAF50;
    padding: 10px;
    border-radius: 4px;
    display: inline-block;
    transition: background-color 0.2s;
}
.image-upload {
        display: inline-block;
        background-color: #fff; /* Màu nền tùy chỉnh */
        color:  #4CAF50;
        padding: 5px 10px;
        border-radius: 25px;
        font-size: 16px;
    }

    .image-upload input[type="file"] {
        display: none; /* Ẩn nút input */
    }

    .image-upload:hover {
        background-color: #45a049; /* Thay đổi màu khi hover */
    }
    .image-preview {
    width: 100px;
    height: 100px;
    margin: 0 auto; /* Căn giữa phần tử */
    padding-bottom: 20px; 
    background-image: url('uploads/imup.png');
    background-size: 50%; /* Kích thước của ảnh nền */
    background-repeat: no-repeat; /* Không lặp lại ảnh nền */
    background-position: center; /* Căn giữa ảnh nền */
    display: flex; /* Sử dụng flexbox */
    justify-content: center; /* Căn giữa nội dung theo chiều ngang */
    align-items: center; /* Căn giữa nội dung theo chiều dọc */
    }
.image-upload:hover {
    background-color: #f0f9e8; /* Màu nền khi hover */
}

#imagePreview img {
    width: 100%;
    margin-top: 10px;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}


button {
    background-color: #4CAF50; /* Màu xanh lá */
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 1em;
}

button:hover {
    background-color: #45a049; /* Màu khi hover */
}

.tuychon {
    display: flex;
    justify-content: center; /* Căn về hai đầu trái và phải */
    align-items: center; /* Căn giữa theo chiều dọc */
    width: 100%;
    text-align: justify;
    gap:20px;
}
textarea:focus {
    outline: none; /* Bỏ viền khi textarea đang được nhập */
}

    </style>
</head>
<body>
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
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

// Lấy mã khách hàng từ biến $khachhang
$makhachhang = $khachhang['makhachhang'];
$madonhang = isset($_GET['madonhang']) ? intval($_GET['madonhang']) : 0;

if ($madonhang == 0) {
    echo "Mã đơn hàng không hợp lệ.";
    exit();
}

// Truy vấn danh sách đơn hàng
$sql = "
    SELECT dh.madonhang, dh.ngaytao, dh.tongtien, tt.tinhtrang, MIN(sp.hinhanh1) AS hinhanh1
    FROM donhang dh
    JOIN chitietdonhang ctdh ON dh.madonhang = ctdh.madonhang
    JOIN sanpham sp ON ctdh.masanpham = sp.masanpham
    LEFT JOIN tinhtrang tt ON dh.madonhang = tt.madonhang
    WHERE dh.makhachhang = ? AND dh.madonhang = ?
    GROUP BY dh.madonhang
    ORDER BY dh.ngaytao DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $makhachhang, $madonhang);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Duyệt qua từng đơn hàng
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="or-der">
        <div class="tuy-chon" style="display: flex; justify-content: space-between;width:100%">
    <div style="flex: 1; display: flex; flex-direction: column; align-items: left;">
        <h3>Đơn hàng</h3>
        <div>#DNNLL<?php echo $row['madonhang']; ?></div>
        <div><?php echo date("d/m/Y H:i:s", strtotime($row['ngaytao'])); ?></div>
        <div><?php echo number_format($row['tongtien'], 0, ',', '.'); ?> VNĐ</div>
    </div>

    <div style="flex: 1; display: flex; flex-direction: column; align-items:center;">
        <h3>Thông tin vận chuyển</h3>
        <ul style="list-style-type: none; padding: 0;">
            <?php
            // Truy vấn bảng tình trạng
            $sql_tinhtrang = "SELECT tinhtrang, ngaytao FROM tinhtrang WHERE madonhang = ?";
            $stmt_tinhtrang = $mysqli->prepare($sql_tinhtrang);
            $stmt_tinhtrang->bind_param("i", $row['madonhang']);
            $stmt_tinhtrang->execute();
            $result_tinhtrang = $stmt_tinhtrang->get_result();

            if ($result_tinhtrang->num_rows > 0) {
                while ($row_tinhtrang = $result_tinhtrang->fetch_assoc()) {
                    if (!empty($row_tinhtrang["tinhtrang"])) {
                        echo "<li style='display: flex; justify-content: space-between;'>";
                        echo "<div style='flex: 1; text-align: right; margin-right:10px;color:blue;'>".date("d/m/Y H:i:s", strtotime($row_tinhtrang["ngaytao"]))."</div>";
                        echo "<div style='flex: 1; text-align: left;'>".$row_tinhtrang["tinhtrang"]."</div>";
                        echo "</li>";
                    }
                }
            } else {
                echo "<li>Không có tình trạng nào cho đơn hàng này.</li>";
            }
            ?>
        </ul>
    </div>
</div>
            </div>
        </div>
        <?php
    }
} else {
    echo "Không có đơn hàng nào.";
}

include('header.php');
$mysqli->close();
?>

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

$mysqli = new mysqli($host, $user, $pass, $db);
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
                <div class="order-item" >
                <img src="uploads/<?php echo $row['hinhanh1']; ?>" alt="Hình ảnh sản phẩm" onclick="window.location.href='chitietsanpham.php?masanpham=<?php echo $row['masanpham']; ?>'">
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
            <button class="review-button" data-id="<?php echo $row['masanpham']; ?>"    data-image="uploads/<?php echo $row['hinhanh1']; ?>"  onclick="openReviewForm(<?php echo $row['masanpham']; ?>)">Đánh giá</button>
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

<!-- Form đánh giá (ẩn ban đầu) -->
<div id="reviewForm" class="formriew" style="display:none; text-align: center;">
    <h3>Đánh giá sản phẩm</h3>
    <form id="submitReviewForm" method="post" action="insert.php" enctype="multipart/form-data">
        <input type="hidden" id="madonhang" name="madonhang" value="<?php echo $madonhang; ?>" required>
        <input type="hidden" id="masanpham" name="masanpham" required>

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
// Hàm mở form đánh giá
// Hàm mở form đánh giá
function openReviewForm(masanpham) {
    document.getElementById('masanpham').value = masanpham; // Lưu masanpham vào input ẩn
    document.getElementById('reviewForm').style.display = 'block'; // Hiện form đánh giá
}

// Sự kiện cho review-button
document.querySelectorAll('.review-button').forEach(button => {
    button.addEventListener('click', function() {
        const masanpham = this.getAttribute('data-id'); // Lấy ID của sản phẩm
        openReviewForm(masanpham); // Mở form đánh giá
    });
});

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

// Hàm hiển thị ảnh xem trước
function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        imagePreview.src = e.target.result;
        imagePreview.style.display = 'block'; // Hiển thị hình ảnh xem trước
    };
    reader.readAsDataURL(file);
}

// Thêm sự kiện nhấp chuột vào imagePreview để mở hộp thoại chọn ảnh
document.getElementById('imagePreview').addEventListener('click', function() {
    document.getElementById('hinhanh').click(); // Mở hộp thoại chọn ảnh
});

// Đóng reviewForm khi nhấp ra ngoài
document.addEventListener('click', function(event) {
    const reviewForm = document.getElementById("reviewForm");
    const isClickInsideForm = reviewForm.contains(event.target);
    const reviewButtons = document.querySelectorAll('.review-button');
    const isClickOnButton = Array.from(reviewButtons).some(button => button.contains(event.target));

    // Nếu nhấp bên ngoài reviewForm và không nhấp vào review-button, ẩn nó
    if (!isClickInsideForm && !isClickOnButton) {
        reviewForm.style.display = "none";
    }
});

// Hàm hiển thị thông báo và ẩn sau 1 giây
function showMessage() {
    const messageDiv = document.getElementById("message");
    messageDiv.style.display = "block";
    setTimeout(() => {
        messageDiv.style.display = "none";
    }, 1000);
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

