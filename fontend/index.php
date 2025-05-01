
<?php
session_start();
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

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ Trà Đạo</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

   <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    min-height: 100vh; /* Đảm bảo chiều cao tối thiểu của body là 100% viewport height */
    display: flex;
    flex-direction: column;
    justify-content: space-between;

}
.success-message {
            position: fixed;
            top: 300px;
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            color: green;
            border-radius: 20px;
            padding: 10px 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.5s ease, visibility 0.5s;
            visibility: hidden;
            z-index: 1000;
        }

        .success-message.show {
            opacity: 1;
            visibility: visible;
        }

.banner {
    margin-top: 0px;
    background: url('uploads/tea3.png') no-repeat center center/cover;
    height: 520px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #175800;
    text-align: center;
    position: relative;
    z-index: 300; /* Đè lên products */
}

.banner-content h2 {
    font-size: 40px;
    margin-bottom: 20px;
}

.products {
    background: url('uploads/tea4.png') no-repeat center bottom/cover;
    height: 750px;
    top: -200px;
    padding: 200px 0;
    position: relative;
    z-index: 10; /* Giữ products ở phía dưới */
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #175800;
    padding-left: 100px;
    padding-right: 100px;

}
.products p {  color: #175800;}
.products h2 { font-family: 'Roboto Slab', serif;}


.product-list-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    position: relative;
}

.product-list {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
    width: 80%; /* Đảm bảo có không gian cho nút điều hướng */
    overflow: hidden; /* Ẩn các sản phẩm không hiển thị */
}

.product-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 0 0 30%; /* Chiều rộng mỗi sản phẩm, điều chỉnh theo nhu cầu */
    display: none; /* Ẩn mặc định tất cả các sản phẩm */
}

.product-item.active {
    display: flex; /* Chỉ hiển thị sản phẩm đang hoạt động */
}

.circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background-color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    font-weight: bold;
    color: #175800;
    margin-bottom: 10px;
    border: 1px solid rgba(13, 131, 9, 0.896);
    position: relative;
    right: -30px; /* Đẩy ô tròn sang phải */
    /* Animation */
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-img {
    width: 130px;
    height: 100px;
    position: relative;
    left: -30px; /* Đẩy hình ảnh lệch qua trái */
    margin-bottom: -80px;
    top: -90px;
    transition: transform 0.3s ease; /* Thêm hiệu ứng chuyển động mượt */
    /* Animation */
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-img:hover {
    transform: translateY(-15px); /* Hình ảnh sẽ nảy lên 15px khi hover */
}

.product-item:hover .product-img {
    transform: translateY(-15px); /* Hình ảnh sẽ nảy lên 15px khi hover */
}

.description {
    margin-top: 10px;
    max-width: 180px;
    color: #444;
    font-size: 16px;
    text-align: center;
}

.arrow {
    cursor: pointer;
    background: rgba(2, 62, 11, 0.385);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 50%;
    font-size: 20px;
    user-select: none;
    margin: 0 10px; /* Cách giữa các nút điều hướng */
}

.arrow:hover {
    background-color: #bbb;
}

/* Bố trí nút điều hướng bên trái và bên phải */
.left-arrow {
    position: absolute;
    left: 10px; /* Khoảng cách từ trái */
    z-index: 10; /* Đảm bảo nút ở trên cùng */
}

.right-arrow {
    position: absolute;
    right: 10px; /* Khoảng cách từ phải */
    z-index: 10; /* Đảm bảo nút ở trên cùng */
}


/* đôi nét về chúng tôi */

.about {
    background: url('uploads/tea7.png') no-repeat center top/cover;
    height: 800px;
    position: relative;
    color: #fff;

    top: -400px;
    padding: 220px 0;
    z-index: 30; /* Đè lên products nhưng bị đè bởi contact */
    justify-content: center;
    align-items: center;
    text-align: center;
}

.about p {
    background: linear-gradient(to right,rgb(207, 231, 128), rgba(207, 231, 128, 0.958), rgba(0, 0, 0, 0)); /* Nền nhòe đen từ trái sang phải */
    padding: 20px; /* Khoảng cách bên trong */
    padding-right: 100px;
    margin: 20px; /* Khoảng cách bên ngoài */
    border-radius: 10px; /* Bo tròn các góc */
    width: 400px;
    justify-content: center;
    text-align: justify; /* Căn đều hai bên */
    margin-left: auto;
    margin-right: 20%;
    color: rgb(0, 0, 0);
    font-weight: 100px;
    overflow: hidden; 
    /* backdrop-filter: blur(5px); /* Hiệu ứng làm mờ nền */

}



.product-details {
    background: url('uploads/tea9.png') no-repeat center top/cover;
    height: 900px;
    position: relative;

    top: -550px;
    z-index: 40; 
    color: #333;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


.container {
    padding-top: 150px;
    width: 70%;
    margin: 0 auto;
}

.product-header {
    text-align: center;
    margin-bottom: 30px;
}

.product-header h2 {
    font-size: 36px;
    font-family: 'Lora', serif;
    color: #4A752C;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.product-tabs {
    list-style: none;
    padding: 0;
    margin-top:20px;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;


}

.product-tabs li {
    display: inline-block;
    margin: 0 10px;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.product-tabs a {
    text-decoration: none;
    font-size: 18px;
    color: #666;
    padding: 5px 10px;
    border-bottom: 2px solid transparent;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.product-tabs a:hover, .product-tabs a:focus {
    border-bottom: 2px solid #4A752C;
    color: #4A752C;
}

.featured-product {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 50px;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.featured-product .image img {
    width: 300px;
    border-radius: 50%;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.product-info {
    width: 50%;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.product-info h3 {
    font-size: 28px;
    color: #4A752C;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.product-info ul {
    list-style-type: disc;
    padding-left: 20px;
    margin: 15px 0;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.product-info ul li {
    margin-bottom: 10px;
    font-size: 18px;
    color: #555;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.order-btn {
    background-color: #467109;
    color: white;
    padding: 10px 30px;
    border: none;
    border-radius: 25px;
    font-size: 18px;
    cursor: pointer;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.order-btn:hover {
    background-color: #6EA323;
}


.productlist {
    display: flex;
    flex-wrap: wrap; /* Cho phép hiển thị trên nhiều hàng */
    justify-content: space-between;
    align-items: flex-start;
}

.productitem {
    text-align: center;
    width: 22%;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.productitem img {
    width: 100px;
    border-radius: 50%;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    /* Animation */
    opacity: 0;
    animation: fadeInUp 0.4s ease-in-out forwards;
    transition: transform 0.3s ease; /* Chuyển đổi transform mượt */
}

.productitem img:hover {
    transform: scale(1.1); /* Hiệu ứng zoom mà không di chuyển */
    cursor: pointer;
}

/* Animation keyframes */
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(20px); /* Di chuyển ban đầu */
    }
    100% {
        opacity: 1;
        transform: translateY(0); /* Trạng thái cuối không còn di chuyển */
    }
}


.highlight {
    color: yellow;
}
.proitem {
    text-align: center;
    width: 22%;
}

.proitem img {
    margin-top: 50px;
    width: 150px;
    border-radius: 50%;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;
    transition: transform 0.3s ease; /* Thêm hiệu ứng chuyển tiếp */
}

.proitem:hover img {
    transform: translateY(20px) scale(1.1); /* Phóng to khi hover */
}

.proitem {

    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;
    transition: transform 0.3s ease; /* Thêm hiệu ứng chuyển tiếp */
}

.proitem:hover{
    transform: translateY(20px) scale(1.1); /* Phóng to khi hover */
    cursor: pointer;

}

.productitem h4 {
    margin-top: 10px;
    font-size: 18px;
    color: #4A752C;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.productitem .price {
    display: block;
    margin-top: 10px;
    font-size: 16px;
    color: #FF4A4A;
    font-weight: bold;
        /* Animation */
        opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;

}

.navigation{
    margin-top:-100px;
    display: flex;
    align-items: center;
    
}
#prev {
    position: absolute;
    left: 60px; /* Khoảng cách từ trái */
    z-index: 10; /* Đảm bảo nút ở trên cùng */
    cursor: pointer;
    background-color: #467109;
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 50%;
    font-size: 20px;
    user-select: none;
    margin: 0 10px; /* Cách giữa các nút điều hướng */
}

#prevhover {
    background-color: #bbb;
}
.order-btn {
    text-decoration: none;
}
#next{
    position: absolute;
    right: 60px; /* Khoảng cách từ phải */
    z-index: 10; /* Đảm bảo nút ở trên cùng */
    cursor: pointer;
    background-color: #467109;
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 50%;
    font-size: 20px;
    user-select: none;
    margin: 0 10px; /* Cách giữa các nút điều hướng */
}

#next:hover {
    background-color: #bbb;
}



  /* Section Customer */
  .customer-section {
    background: url('uploads/ykkh.png') no-repeat center top/cover;
    height: 700px;
    position: relative;

    top: -700px;
    padding: 150px 0;
    z-index: 60; /* Đè lên products nhưng bị đè bởi contact */
    justify-content: center;
    align-items: center;
    text-align: center;
        }


        .customer-carousel {
            position: relative;
            max-width: 700px;
            margin: 0 auto;
            overflow: hidden;
            color: #fff;
        }

.customer-testimonial {
            
    margin-top:40px;
    display: none; /* Ẩn tất cả các ý kiến khách hàng */
}

.customer-testimonial.active {
    display: block; /* Chỉ hiển thị phần tử có lớp active */
}

.carousel-dots {
    text-align: center; /* Căn giữa các dấu chấm */
               }

.dot {
    cursor: pointer; /* Hiển thị con trỏ khi di chuột vào dấu chấm */
    height: 10px;
    width: 10px;
    margin: 0 5px;
    background-color: #bbb; /* Màu nền cho dấu chấm */
    border-radius: 50%; /* Tạo hình tròn cho dấu chấm */
    display: inline-block;
     }

.dot.active {
    background-color: #717171; /* Màu cho dấu chấm đang được chọn */
            }


    .customer-photo img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid white;
    margin-bottom: 15px;
    object-fit: cover;
    
    /* Animation */
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.4s ease-in-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    .customer-name  {
    margin: 10px 0;
    font-size: 18px;
    font-weight: bold;
   
    /* Animation */
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.5s ease-in-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

p {
    
    font-size: 16px;
    font-weight:100;
    color: white;
    /* Animation */
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 1s ease-in-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

        .carousel-dots {
            margin-top: 15px;
        }

        .carousel-dots span {
            display: inline-block;
            width: 12px;
            height: 12px;
            background-color: white;
            border-radius: 50%;
            margin: 0 6px;
            cursor: pointer;
        }

        .carousel-dots .active {
            background-color: #f2ff00; /* Highlight active dot */
        }

.contact-section {
    background: url('uploads/tea13.png') no-repeat center top/cover;
    height: 800px;
    position: relative;
    padding-bottom: -200px;
    top: -900px;
    z-index: 50; /* Đè lên products nhưng bị đè bởi contact */
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
    padding: 150px 0;
    text-align: center;
    position: relative;
    z-index: 400; /* Đè lên tất cả các phần khác */
    padding-left: 100px;
    padding-right: 100px;

}

 /* Section Contact */

     .la {
      position:  absolute;
      transition: width 0.5s ease;
      transform: translateX(-50%);
      z-index: 900;
      width: 50px;
      height: 50px;
     }

        
    footer form {
            max-width: 400px;
            margin: 0 auto;
          /*  background-color: rgba(0, 0, 0, 0.7); */
            padding: 20px;
            border-radius: 10px;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: transparent;
            border: 1px solid rgb(244, 237, 237);
            color: #ffffff;
            border-radius: 25px ;
            background: rgba(2, 62, 11, 0.685);
        }

        .form-input::placeholder {
    color: #fff;
}


        .submit-button {
            background-color: #00a651;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 50px;
        }
        .btn-dangky{
            background-color: #00a651;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 50px;
            margin-left: 55px;
        }
        .btn-dangky:hover{
            background-color: #00a610;
            color: white;
  
        }
        .contact-info {
            margin-top: 20px;
            text-align: center;
        }

        .social-icons a {
            margin: 0 5px;
            color: white;
            text-decoration: none;
        }
     footer {

        
    background: url('uploads/footer.png') no-repeat center top/cover;
    position: relative;
    padding-bottom: -500px;
    margin-top: -1050px;
    z-index: 50; /* Đè lên products nhưng bị đè bởi contact */
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
    padding: 120px 0;
    text-align: center;
    position: relative;
    z-index: 400; /* Đè lên tất cả các phần khác */


     }

     .a-lienhe {
        background-color: #00a651;
       color: white;
       width: 100px; 
       justify-content: center;
      align-items: center;
      text-align: center;
      margin: 0 auto;
      border-radius: 50px;
      padding: 2px;
     font-size: 18px;
     }

     footer img {
        width: 40px;
        margin-top: 20px;
        background: rgba(2, 62, 11, 0.685);
       border-radius: 50px;
     }

     
   </style>
</head>
<body>
    <!-- Header -->
<?php 
ob_start();
include('header.php');?>
<img class="la" src="uploads/la3.png" style="top: 820px;left: 850px; width: 200px;height: 200px;">
<img class="la" src="uploads/la4.png" style="top: 1510px;left: 150px; width: 300px;height: 100px;">

<?php
if (isset($_SESSION['login_success_message'])) {
    echo '<div class="success-message" id="successMessage">' . $_SESSION['login_success_message'] . '</div>';
    unset($_SESSION['login_success_message']); // Xóa thông báo sau khi hiển thị
} elseif (isset($_SESSION['logout_success_message'])) {
    echo '<div class="success-message" id="successMessage">' . $_SESSION['logout_success_message'] . '</div>';
    unset($_SESSION['logout_success_message']); // Xóa thông báo sau khi hiển thị
}
?>
<?php
// Kiểm tra cookie thông báo đăng xuất
if (isset($_COOKIE['logout_success_message'])) {
    echo '<div class="success-message" id="successMessage">' . $_COOKIE['logout_success_message'] . '</div>';
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var message = document.getElementById('successMessage');
        if (message) {
            // Hiển thị thông báo với hiệu ứng mờ dần
            message.classList.add('show');

            // Sau 2 giây, ẩn thông báo
            setTimeout(function () {
                message.classList.remove('show');
            }, 2000);

            // Đóng thông báo khi chạm ra ngoài
            document.addEventListener('click', function (event) {
                if (!message.contains(event.target)) {
                    message.classList.remove('show');
                }
            });
        }
    });
</script>

    <!-- Banner Section -->
    <section class="banner">
        <div class="banner-content">
        </div>
    </section>

    <!-- Product Section -->
    <section class="products">
        <h2>SẢN PHẨM TRÀ HẠ TIỂU ĐƯỜNG</h2>
        <p>Lorem ipsum dolor sit amet...</p>
    
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
        // Truy vấn lấy sản phẩm có điều kiện ghim = 1
        $sql = "SELECT tensanpham, hinhanh1, mota1 FROM sanpham WHERE ghim = 1";
        $result = $conn->query($sql);
        
        // Tạo mảng để lưu trữ sản phẩm
        $products = [];
        
        if ($result->num_rows > 0) {
            // Đưa dữ liệu vào mảng
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        } else {
            echo "Không có sản phẩm nào được ghim.";
        }
        
        $conn->close();
        ?>
        
        <div class="product-list-wrapper">
            <button class="arrow left-arrow" onclick="showPrevProducts()">&#10094;</button>
            
            <div class="product-list" id="productList">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $index => $product): ?>
                        <div class="product-item" data-index="<?php echo $index; ?>">
                            <div class="circle"><?php echo htmlspecialchars($product["tensanpham"]); ?></div>
                            <img src="uploads/<?php echo htmlspecialchars($product["hinhanh1"]); ?>" alt="<?php echo htmlspecialchars($product["tensanpham"]); ?>" class="product-img">
                            <p class="description"><?php echo htmlspecialchars($product["mota1"]); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <button class="arrow right-arrow" onclick="showNextProducts()">&#10095;</button>
        </div>
        
        <script>
            let currentIndex = 0;
const itemsPerPage = 3;
const totalItems = document.querySelectorAll('.product-item').length;

function updateProductVisibility() {
    const products = document.querySelectorAll('.product-item');
    products.forEach((product, index) => {
        if (index >= currentIndex && index < currentIndex + itemsPerPage) {
            product.classList.add('active');
        } else {
            product.classList.remove('active');
        }
    });
}

function showNextProducts() {
    if (currentIndex + itemsPerPage < totalItems) {
        currentIndex += itemsPerPage;
    } else {
        currentIndex = 0; // Quay lại sản phẩm đầu tiên
    }
    updateProductVisibility();
}

function showPrevProducts() {
    if (currentIndex - itemsPerPage >= 0) {
        currentIndex -= itemsPerPage;
    } else {
        currentIndex = totalItems - itemsPerPage; // Quay lại sản phẩm cuối cùng
    }
    updateProductVisibility();
}

// Hiển thị mặc định 3 sản phẩm đầu tiên khi tải trang
document.addEventListener("DOMContentLoaded", function() {
    updateProductVisibility();
});

        </script>
    </section>
    
    
    
    <!-- About Section -->
    <section class="about" id="about-section">
    <h2>ĐÔI NÉT VỀ CHÚNG TÔI</h2>
    <div class="background-overlay"></div>
    <p>Along Tea+, nơi mang đến những loại trà cao cấp và tự nhiên nhất. Chúng tôi cam kết sử dụng nguyên liệu hữu cơ, đảm bảo sức khỏe cho người tiêu dùng. Với đa dạng sản phẩm từ trà xanh, trà đen đến trà ô long, mỗi tách trà đều được chế biến tỉ mỉ. Đội ngũ nhân viên tận tình của chúng tôi sẽ giúp bạn chọn lựa loại trà phù hợp nhất. Hãy đến và trải nghiệm hương vị trà độc đáo tại Along Tea+ ngay hôm nay!</p>
    <button onclick="window.location.href='dangnhap.php';" class="btn-dangky">Đăng ký</button>
</section>

<section class="product-details">

<?php
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

// Fetch products where ghim = 0
$sql = "SELECT * FROM sanpham WHERE ghim = 0";
$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<div class="container">
    <div class="product-header">
        <h2>Thông Tin Về Sản Phẩm</h2>
        <ul class="product-tabs">
            <!-- Tab sẽ được cập nhật bằng JavaScript -->
        </ul>
    </div>

    <div class="featured-product" id="featured-product">
        <!-- Sản phẩm nổi bật sẽ được cập nhật bằng JavaScript -->
    </div>

    <div class="productlist" id="productlist">
        <!-- Sản phẩm sẽ được hiển thị ở đây -->
    </div>

    <div class="navigation">
        <button id="prev" class="nav-btn">❮</button>
        <button id="next" class="nav-btn">❯</button>
    </div>
</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const products = <?php echo json_encode($products); ?>; // Pass PHP array to JavaScript
    const itemsPerPage = 4; // Số sản phẩm hiển thị trên mỗi trang
    let currentIndex = 0; // Chỉ số hiện tại

    // Hàm cập nhật sản phẩm nổi bật
    function updateFeaturedProduct(index) {
    const product = products[index];
    const featuredProduct = document.getElementById('featured-product');
    featuredProduct.innerHTML = `
        <div class="image">
            <img src="uploads/${product.hinhanh1}" alt="${product.tensanpham}">
        </div>
        <div class="product-info">
            <h3>${product.tensanpham} (${Math.floor(product.trongluong).toLocaleString()}g)</h3>
            <ul>
                <li>${product.mota1}</li>
                <li>${product.mota2}</li>
                <li>${product.mota3}</li>
                <li>${product.mota4}</li>
            </ul>
            <a href="chitietsanpham.php?masanpham=${product.masanpham}" class="order-btn">Đặt hàng ngay</a>
        </div>
    `;
}


    // Hàm cập nhật danh sách sản phẩm
    function updateProductList() {
        const productlist = document.getElementById('productlist');
        const tabs = document.querySelector('.product-tabs');
        productlist.innerHTML = ''; // Xóa danh sách sản phẩm cũ
        tabs.innerHTML = ''; // Xóa tab cũ

        // Lấy sản phẩm để hiển thị
        const currentProducts = products.slice(currentIndex, currentIndex + itemsPerPage);

        currentProducts.forEach((product, index) => {
            // Cập nhật tab
            const tab = document.createElement('li');
            tab.innerHTML = `<a href="#" class="product-link" data-id="${currentIndex + index}">${product.tensanpham}</a>`;
            tabs.appendChild(tab);

            // Cập nhật sản phẩm hiển thị
            const productItem = document.createElement('div');
            productItem.classList.add('productitem');
            productItem.innerHTML = `
                <img src="uploads/${product.hinhanh1}" alt="${product.tensanpham}">
                <h4>${product.tensanpham} / <span>${Math.floor(product.trongluong).toLocaleString()}g</span></h4>
                <span class="price">${Math.floor(product.gia).toLocaleString()} VND</span>

            `;
            productItem.addEventListener('click', () => updateFeaturedProduct(currentIndex + index));
            productlist.appendChild(productItem);
        });

        // Cập nhật sản phẩm nổi bật cho sản phẩm đầu tiên
        if (currentProducts.length > 0) {
            updateFeaturedProduct(currentIndex);
        }

        // Thêm sự kiện click cho các tab sản phẩm
        const productLinks = document.querySelectorAll('.product-link');
        productLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Ngăn chặn hành vi mặc định của liên kết
                const productId = this.getAttribute('data-id');
                updateFeaturedProduct(productId); // Cập nhật sản phẩm nổi bật
            });
        });
    }

    // Hàm cập nhật điều hướng
    function updateNavigation() {
        document.getElementById('prev').disabled = currentIndex === 0; // Vô hiệu hóa nút Quay lại nếu đang ở trang đầu
        document.getElementById('next').disabled = currentIndex + itemsPerPage >= products.length; // Vô hiệu hóa nút Tiếp nếu đã ở trang cuối
    }

    // Nút "Tiếp" để hiển thị nhóm tiếp theo của sản phẩm
    document.getElementById('next').addEventListener('click', function() {
        if (currentIndex + itemsPerPage < products.length) {
            currentIndex += itemsPerPage; // Tăng chỉ số nhóm
            updateProductList(); // Cập nhật danh sách sản phẩm
            updateNavigation(); // Cập nhật điều hướng
        }
    });

    // Nút "Quay lại" để hiển thị nhóm trước đó của sản phẩm
    document.getElementById('prev').addEventListener('click', function() {
        if (currentIndex - itemsPerPage >= 0) {
            currentIndex -= itemsPerPage; // Giảm chỉ số nhóm
            updateProductList(); // Cập nhật danh sách sản phẩm
            updateNavigation(); // Cập nhật điều hướng
        }
    });

    // Khởi động hiển thị sản phẩm
    updateProductList(); // Cập nhật danh sách sản phẩm khi khởi động
    updateNavigation(); // Cập nhật điều hướng
});
</script>


    <!-- Customer Section -->
    <div class="customer-section" id="customer-section">
        <div class="customer-carousel">
            <h2>Ý KIẾN KHÁCH HÀNG</h2>
            <div class="customer-testimonial active">
                <div class="customer-photo">
                    <img src="uploads/kh1.webp" alt="Customer Photo 1">
                </div>
                <div class="customer-name">Nguyễn Trần Huyền My</div>
                <p>"Dịch vụ rất tuyệt vời, tôi cảm thấy rất hài lòng với sản phẩm!"</p>
            </div>
    
            <div class="customer-testimonial">
                <div class="customer-photo">
                    <img src="uploads/kh2.avif" alt="Customer Photo 2">
                </div>
                <div class="customer-name">Lê Quang Huy</div>
                <p>"Sản phẩm chất lượng cao và nhân viên hỗ trợ rất nhiệt tình."</p>
            </div>
    
            <div class="customer-testimonial">
                <div class="customer-photo">
                    <img src="uploads/kh3.avif" alt="Customer Photo 3">
                </div>
                <div class="customer-name">Trần Thị Lan</div>
                <p>"Tôi rất ấn tượng với thiết kế và hương vị của trà."</p>
            </div>
    
            <div class="carousel-dots">
                <span class="dot active" onclick="showTestimonial(0)"></span>
                <span class="dot" onclick="showTestimonial(1)"></span>
                <span class="dot" onclick="showTestimonial(2)"></span>
            </div>
        </div>
    </div>
    

    <script>
        // Function to show the selected testimonial with fade effect
        let currentTestimonialIndex = 0;

function showTestimonial(index) {
    const testimonials = document.querySelectorAll('.customer-testimonial');
    const dots = document.querySelectorAll('.dot');

    // Ẩn tất cả các ý kiến khách hàng
    testimonials.forEach((testimonial) => {
        testimonial.classList.remove('active');
    });

    // Ẩn tất cả các dấu chấm
    dots.forEach((dot) => {
        dot.classList.remove('active');
    });

    // Hiển thị ý kiến khách hàng hiện tại và dấu chấm tương ứng
    testimonials[index].classList.add('active');
    dots[index].classList.add('active');
}
    </script>
    <!-- Customer Section -->
    <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltra";
$port ='3406';

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch best-selling products
$sql = "SELECT * FROM sanpham WHERE ghim = 0 ORDER BY luotmua DESC LIMIT 8";
$result = $conn->query($sql);
?>

<section class="contact-section" id="product-section">
    <h2>SẢN PHẨM BÁN CHẠY</h2>
    <div class="productlist">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="proitem" onclick="window.location.href='chitietsanpham.php?masanpham=<?php echo $row['masanpham']; ?>'">

                    <img src="uploads/<?php echo $row['hinhanh1']; ?>" alt="<?php echo $row['tensanpham']; ?>">
                    <h4><?php echo $row['tensanpham']; ?> / <span class="highlight"><?php echo $row['trongluong']; ?>g</span></h4>
                    <span class="price"><?php echo number_format($row['gia'], 0, ',', '.'); ?> VND</span> <!-- Format price -->
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Không có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</section>

<?php
$conn->close();
?>


<footer id="contact-section">
    <h2 class="a-lienhe">Liên hệ</h2>
    <form>
        <input type="text" class="form-input" placeholder="Họ tên...">
        <input type="text" class="form-input" placeholder="Số điện thoại...">
        <input type="email" class="form-input" placeholder="Email...">
        <textarea class="form-input" placeholder="Nội dung..."></textarea>
        <button type="submit" class="submit-button">Gửi</button>
    </form>
    <div class="contact-info">
        <p>237 Nguyễn Văn Cừ, Cần Thơ</p>
        <p>0123 456 789 - 0987 654 321</p>
        <div class="social-icons">
            <a href="#"><img src="uploads/fb.png"></a>  <a href="#"><img src="uploads/tt.png"></a> <a href="#"><img src="uploads/in.png"></a>
        </div>
    </div>
</footer>
<?php 
ob_end_flush(); // Kết thúc buffer và xuất nội dung
?>
</body>
</html>
