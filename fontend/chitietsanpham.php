<?php
session_start();

// Kiểm tra xem có yêu cầu reload không
if (isset($_SESSION['reload']) && $_SESSION['reload'] === true) {
    // Xóa biến session để không reload nhiều lần
    unset($_SESSION['reload']);
    echo "<script>location.reload();</script>";
}


// Kiểm tra nếu người dùng đã đăng nhập
// Lấy thông tin người dùng từ session
$khachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang'] : null; // Nếu không tìm thấy, gán giá trị null
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cartCount = localStorage.getItem('cart-count') || 0;
    document.getElementById('cart-count').innerText = cartCount;
});
</script>

<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>


    <!-- Include Header -->
<div class="wapper">

<!-- Container -->
<div class="container product-detail">
    <div class="row">
        <?php
        // Kết nối cơ sở dữ liệu
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "qltra";
        $port ='3406';
        $conn = new mysqli($servername, $username, $password, $dbname, $port);

        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }

        // Lấy mã sản phẩm từ tham số URL
        $masanpham = isset($_GET['masanpham']) ? intval($_GET['masanpham']) : 0;

        // Truy vấn thông tin sản phẩm
        $sql = "SELECT * FROM sanpham WHERE masanpham = $masanpham";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Hiển thị thông tin sản phẩm
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='col-md-6'>
                    <!-- Bootstrap Carousel for Product Images -->
                    <div id='productCarousel' class='carousel slide' data-bs-ride='carousel'>
                        <div class='carousel-inner'>
                            <div class='carousel-item active'>
                                <img src='uploads/".$row['hinhanh1']."' class='d-block w-100' alt='Product Image 1'>
                            </div>";
                            if (!empty($row['hinhanh2'])) {
                                echo "<div class='carousel-item'>
                                    <img src='uploads/".$row['hinhanh2']."' class='d-block w-100' alt='Product Image 2'>
                                </div>";
                            }
                            if (!empty($row['hinhanh3'])) {
                                echo "<div class='carousel-item'>
                                    <img src='uploads/".$row['hinhanh3']."' class='d-block w-100' alt='Product Image 3'>
                                </div>";
                            }
                echo "
                        </div>
                        <!-- Controls -->
                        <button class='carousel-control-prev' type='button' data-bs-target='#productCarousel' data-bs-slide='prev'>
                            <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                            <span class='visually-hidden'>Previous</span>
                        </button>
                        <button class='carousel-control-next' type='button' data-bs-target='#productCarousel' data-bs-slide='next'>
                            <span class='carousel-control-next-icon' aria-hidden='true'></span>
                            <span class='visually-hidden'>Next</span>
                        </button>
                    </div>
                </div>
                <div class='col-md-6'>
                    <h1 class='product-title'>".$row['tensanpham']."</h1>
                    <p class='product-price'>".number_format($row['gia'], 0, ',', '.')." VND</p>
                    <div class='product-description'>
                        <p>".$row['mota1']."</p>
                        <p>".$row['mota2']."</p>
                        <p>".$row['mota3']."</p>
                        <p>".$row['mota4']."</p>
                    </div>
                    <ul class='product-specs'>
                        <li>Trọng lượng: ". number_format($row['trongluong'],0)."g</li>
                        <li>Loại sản phẩm: ".$row['tenloai']."</li>
                        <li>Số lượng: ".$row['soluong']."</li>
                        <li>Đã bán ".$row['luotmua']."</li>
                    </ul>
                    <div class='product-rating'>
                    </div>";
                if ($row['soluong'] > 0) {
                    echo "<button class='buy-button' onclick='themVaoGioHang(" . $row['masanpham'] . ", \"" . $row['tensanpham'] . "\", " . $row['gia'] . ", \"" . $row['hinhanh1'] . "\", " . $row['soluong'] . ")'>Thêm giỏ hàng</button>";
                } else {
                    echo "<button class='btn btn-secondary' disabled>Thêm giỏ hàng</button>";
                }
                echo "
                </div>
                ";
            }
        } else {
            echo "<p>Không tìm thấy sản phẩm.</p>";
        }

        // Truy vấn đánh giá
        $sql_reviews = "SELECT d.masanpham, d.id, d.noidung, d.sosao, d.hinhanh, d.ngaygui,k.avatar, k.tenkhachhang
        FROM danhgia d
        JOIN khachhang k ON d.makhachhang = k.makhachhang
        WHERE d.masanpham = $masanpham AND d.trangthai = 1
        ORDER BY d.ngaygui DESC"; // Sắp xếp theo ngày gửi
        $result_reviews = $conn->query($sql_reviews);
        ?>
    </div>

    <!-- Product Detail Section -->
    <div class="row motachitiet">
        <div class="col-md-12">
            <h2>Thông tin chi tiết</h2>
            <?php
                    $sql = "SELECT * FROM sanpham WHERE masanpham = $masanpham";
                    $result = $conn->query($sql);
            
            
            // Hiển thị thông tin chi tiết sản phẩm
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p>".$row['motachitiet']."</p>";
            }
            ?>
        </div>
    </div>

    <!-- Thumbnails Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="product-thumbnails">
                <?php if (!empty($row['hinhanh1'])): ?>
                    <img src="uploads/<?php echo $row['hinhanh1']; ?>" onclick="showImage('<?php echo $row['hinhanh1']; ?>')">
                <?php endif; ?>
                <?php if (!empty($row['hinhanh2'])): ?>
                    <img src="uploads/<?php echo $row['hinhanh2']; ?>" onclick="showImage('<?php echo $row['hinhanh2']; ?>')">
                <?php endif; ?>
                <?php if (!empty($row['hinhanh3'])): ?>
                    <img src="uploads/<?php echo $row['hinhanh3']; ?>" onclick="showImage('<?php echo $row['hinhanh3']; ?>')">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div style="display: flex;width:100%; margin-top:50px;color:#fff;">
    <h2>Danh Sách Đánh Giá Sản Phẩm</h2>
    </div>


    <div class="view">
    <?php
    if ($result_reviews->num_rows > 0) {
        // Hiển thị từng đánh giá
        while ($row = $result_reviews->fetch_assoc()) {
            // Check if the logged-in customer matches the review's customer
            $isCurrentUser = $khachhang && $row['tenkhachhang'] == $khachhang['tenkhachhang']; 
            ?>
            <div class="review-card">
                <div class="review-header">
                    <img src="<?php echo htmlspecialchars($row['avatar']); ?>" alt="Hình ảnh đánh giá" class="review-image">
                    <div class="review-info">
                        <h2><?php echo htmlspecialchars($row['tenkhachhang']); ?></h2>
                        <div class="star-rating">
                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                echo ($i < $row['sosao']) ? '☆' : ''; // Đánh giá sao
                            }
                            ?>
                        </div>
                        <p class="review-date"><?php echo date("d/m/Y", strtotime($row['ngaygui'])); ?></p>
                    </div>
                    <?php if ($isCurrentUser): ?>
    <div class="dropdown">
        <span class="three-dots" onclick="toggleDropdown(event)">⋮</span>
        <div class="dropdown-menu" style="display: none;">
            <button onclick="deleteReview(<?php echo $row['id']; ?>)">Xóa</button>
        </div>
    </div>
<?php endif; ?>
                </div>
                <div class="review-content">
                    <?php if ($row['hinhanh']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['hinhanh']); ?>" alt="Hình ảnh đánh giá">
                    <?php endif; ?>
                    <p><?php echo nl2br(htmlspecialchars($row['noidung'])); ?></p>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>Không có đánh giá nào.</p>";
    }
    ?>
</div>

<?php

// Xử lý xóa đánh giá
// Xử lý xóa đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reviewId'])) {
    $reviewId = intval($_POST['reviewId']);
    
    // Kết nối cơ sở dữ liệu
    $conn = new mysqli("localhost", "root", "", "qltra");

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Xóa đánh giá
    $sql_delete = "DELETE FROM danhgia WHERE id = $reviewId"; 
    if ($conn->query($sql_delete) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $conn->close();
    exit; 
}


// Kiểm tra xem có yêu cầu reload không
if (isset($_SESSION['reload']) && $_SESSION['reload'] === true) {
    unset($_SESSION['reload']);
    echo "<script>location.reload();</script>";
}

// Kiểm tra người dùng đã đăng nhập
$khachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang'] : null; 
?>



<script>
function toggleDropdown(event) {
    const dropdown = event.target.nextElementSibling;
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

function deleteReview(id) {
    // Thực hiện xóa đánh giá ở đây (gọi API hoặc xử lý ở server)
    
    // Sau khi xóa, reload trang sau 0.1 giây
    setTimeout(function() {
        location.reload();
    }, 100); // 100 milliseconds = 0.1 seconds
}
// Function to delete a review (implement this function as needed)
function deleteReview(reviewId) {
    if (confirm("Bạn có chắc chắn muốn xóa đánh giá này?")) {
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ reviewId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Đánh giá đã được xóa.");
                location.reload(); // Reload the page to reflect changes
            } else {
                alert("Có lỗi xảy ra khi xóa đánh giá: " + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

</script>

<style>

    .dropdown {
      position: relative;
      top: -40px;
      margin-left:auto;

    }
.three-dots {
    cursor: pointer;
    font-size: 14px; /* Adjust size as needed */
    margin-right: auto; /* Push to the right */

}
.dropdown-menu {
    position: absolute;
    background: white;
    text-align:center;
    left:-160px;

    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}
.dropdown-menu button {
    background: none;
    border: none;
    padding: 10px;
    cursor: pointer;
}
.dropdown-menu button:hover {
    background: #f0f0f0;
}
</style>
</div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to show image when clicking thumbnail
        function showImage(imageSrc) {
            document.getElementById('largeImage').src = imageSrc;
        }
    </script>

<?php include('fullsanpham.php');?>
<?php include('footer.php');?>
</div>
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


.navbar ul {
        list-style: none;
        display: flex;
        padding-right: 8px;

    }

    .avatar-menu {
        border-radius: 50px;
        position:fixed;
        padding: 5px;
        top: 70px;
        right: 38px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
        z-index: 2000;
    }
    .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
    }


html, body {
    height: 100%;
    margin: 0;
    overflow: hidden; /* Ngăn không cho body cuộn */
}

body {
    background: url('uploads/nenmo2.png') no-repeat center top/cover;
}

.wapper {
    height: calc(100% - 0px); /* Chiều cao wapper bằng chiều cao body trừ đi padding-top */
    overflow-y: auto; /* Cho phép cuộn trong wapper */
    /* Bạn có thể thêm các thuộc tính khác cho wapper tại đây */
}


        .product-detail {
            margin: 50px 0;
            justify-content: center;
            padding-left: 12%;
            margin-top: 100px;


            
        }

        .product-title {
            font-size: 2rem;
            font-weight: 600;
            color: white;
            margin-top:-6px;
            
        }

        .product-price {
            color: yellow;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .product-description {
            margin-top: 20px;
            color: white;
            
        }

        .buy-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
        }
        button{margin-right:10px; border:none;}
        .buy-button:hover {
            background-color: #218838;
        }

        .product-specs {
            list-style: none;
            padding: 0;
            color: white;

        }

        .product-specs li {
            margin-bottom: 10px;
        }

        .product-rating {
            font-size: 1.2rem;
            color: #ffc107;
        }
        .carousel-item img {
         width: 100%; /* Chiếm toàn bộ chiều rộng */
         height: 450px; /* Giới hạn chiều cao */
         object-fit: cover; /* Cắt ảnh để giữ tỷ lệ */
         border-radius: 10px;
         margin-top:6px;
           }

        .product-thumbnails img {
            width:30%;
            height: 200px;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 10px;

        }
        .carousel-control-prev,
        .carousel-control-next {
        border-radius: 50%; /* Làm cho nút hình tròn */

        }

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 123, 255, 0.5); /* Màu xanh lá đậm với độ mờ */
    border-radius: 50%; /* Làm cho biểu tượng hình tròn */

}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
    background-color: rgba(0, 123, 255, 0.8); /* Tăng độ mờ khi hover */
}
        .product-thumbnails img:hover {
            border: 1px solid yellow;
        }

        .product-detail-banner img {
            width: 20%;
            height: 400px;
        }

        .motachitiet {
            margin-top: 40px;
            color: white;
            text-align: justify;
        }


        .sodonhang {
    position: absolute;
    z-index: 4000;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    cursor: pointer;
    right: 50px;
    top: 5px;
    font-size: 11px;
    text-align: center;
    color: white;
    background: green;
    padding-bottom:18px;
    padding-top:0px;
    border: 1px solid white;
    opacity: 0; /* Ẩn biểu tượng ban đầu */
    transition: opacity 0.3s ease; /* Hiệu ứng chuyển tiếp */
}
#cart-count{
    position: absolute;
    z-index: 4000;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    cursor: pointer;
    right: 100px;
    top: 5px;
    font-size: 11px;
    text-align: center;
    color: white;
    background: green;
    padding-bottom:18px;
    padding-top:0px;
    border: 1px solid white;
    opacity: 0; /* Ẩn biểu tượng ban đầu */
    transition: opacity 0.3s ease; /* Hiệu ứng chuyển tiếp */
}

.view{
    display: flex;
    margin: 0 auto;
    padding: 0px;
    margin-top:20px;

}
.view {
    display: flex;
    gap: 20px;
}

.review-card {
    margin-bottom: 20px; /* Khoảng cách giữa các đánh giá */
    width: 100%;
    background-color: rgba(255, 255, 255, 0.9); /* Màu trắng nhạt với độ mờ */
    backdrop-filter: blur(0.5px); /* Hiệu ứng nhòe */
    border-radius: 10px; /* Bo góc mềm mại */
    padding: 20px; /* Khoảng cách bên trong thẻ */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Tạo hiệu ứng đổ bóng nhẹ */
    height:30%;
}


.review-header {
    display: flex; /* Sắp xếp avatar và thông tin theo chiều ngang */
    align-items: center; /* Canh giữa theo chiều dọc */
    justify-content: left; /* Căn giữa khoảng cách trên dưới và giữa các phần tử */
    padding: 10px 0; /* Khoảng cách trên dưới lớp cha */
}


.review-image {
    object-fit: cover;

    width: 50px; /* Điều chỉnh kích thước avatar */
    height: 50px; /* Điều chỉnh kích thước avatar */
    border-radius: 50%; /* Tạo hình tròn cho avatar */
    margin-right: 15px; /* Khoảng cách giữa avatar và thông tin */
    margin-bottom: 15px;
}



.review-header h2 {
    margin: 0; /* Xóa khoảng cách mặc định của h2 */
    font-size: 14px; /* Kích thước chữ cho tên khách hàng */
}
.review-info {
    display: flex;
    flex-direction: column; /* Sắp xếp thông tin theo chiều dọc */
}

.star-rating {
    color: green; /* Màu sắc cho sao */
}
.review-content img {
    object-fit: cover;

    width: 100%; /* Chiều rộng hình ảnh bằng chiều rộng của card */
    height: 200px;
    border-radius: 8px; /* Bo tròn các góc hình ảnh */
    margin-bottom: 10px; /* Khoảng cách giữa hình ảnh và nội dung */
    margin-top: -20px;

}

.review-content {
    margin-top: 10px; /* Khoảng cách giữa header và nội dung đánh giá */
}    

</style>