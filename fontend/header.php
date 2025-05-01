<?php
$current_page = basename($_SERVER['PHP_SELF']); // Lấy tên tệp hiện tại
?>

<style>
    /* Logo */
    .logo {
        top: 10px;
        left: 10px;
        height: 50px;
    }
    .logo:hover { 
        cursor: pointer;
    }

    /* Header */
    .header {
        <?php if ($current_page == 'index.php' || $current_page == 'sanpham.php'): ?>
            background: linear-gradient(to bottom, #000000a8, rgba(0, 0, 0, 0.548), rgba(0, 0, 0, 0.01));
        <?php else: ?>
            background: linear-gradient(to bottom, #023c11a8, rgba(2, 101, 12, 0.548), rgba(2, 123, 38, 0.01));
        <?php endif; ?>
        color: #fff;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        z-index: 5000;
        width: 100%;
        top: 0;
    }

    /* Navbar */
    .navbar ul {
        list-style: none;
        display: flex;
    }

    .navbar ul li {
        margin-right: 20px;
    }

    .navbar ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }

    /* User Menu */
    .avatar-menu {
        object-fit: cover;
        border-radius: 50px;
        position:fixed;
        padding: 5px;
        top: 70px;
        right: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
        z-index: 2000;
    }

    .logout-text, .menu-icon.cart, .menu-icon.order {
        opacity: 0;
        visibility: hidden;
        transform: translateX(10px);
        transition: all 0.3s ease;
    }

    .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        object-fit: cover;
    }

    .avatar-menu.active .logout-text, 
.avatar-menu.active .menu-icon.cart, 
.avatar-menu.active .menu-icon.order {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
    transition: transform 0.3s ease, opacity 0.3s ease, visibility 0.3s ease; /* Thêm hiệu ứng chuyển tiếp */
}

.avatar-menu .logout-text, 
.avatar-menu .menu-icon.cart, 
.avatar-menu .menu-icon.order {
    opacity: 0; /* Ẩn các phần tử khi không có lớp active */
    visibility: hidden; /* Ẩn các phần tử khi không có lớp active */
    transform: translateX(20px); /* Đặt vị trí ban đầu của các phần tử bên phải */
}

    .avatar-menu.active {
        background: linear-gradient(to bottom, #023c11a8, rgba(2, 101, 12, 0.548), rgba(2, 123, 38, 0.01));
    }

    .logout-text {
        font-size: 16px;
        color: #333;
    }

    .menu-icon.cart, .menu-icon.order {
        background-color: #f0f0f0;
        border: 2px solid #ddd;
    }

    .menu-icon.avatar {
        background-color: #eee;
        border: 2px solid #00a610;
    }

    .avatar-menu.active {
        gap: 10px;
    }
    
    /* Icon bút tròn để cập nhật avatar */
.edit-icon {
    position: absolute;
    z-index: 4000;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    right: 5px;
    top: 30px;
    border: 1px solid white;
    opacity: 0; /* Ẩn biểu tượng ban đầu */
    transition: opacity 0.3s ease; /* Hiệu ứng chuyển tiếp */
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
    font-size: 12px;
    text-align: center;
    color: white;
    background: green;
    padding:2px;
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
    font-size: 12px;
    text-align: center;
    color: white;
    background: green;
    padding:2px;
    border: 1px solid white;
    opacity: 0; /* Ẩn biểu tượng ban đầu */
    transition: opacity 0.3s ease; /* Hiệu ứng chuyển tiếp */
}

/* Hiển thị .edit-icon khi hover vào .menu-icon hoặc .edit-icon */
.menu-icon:hover .edit-icon,
.edit-icon:hover {
    opacity: 1; /* Hiển thị khi hover vào cả hai phần tử */
}

.avatar-menu.active #cart-count {
        opacity: 1; /* Hiển thị khi menu avatar đang mở */
    }
.avatar-menu.active .sodonhang {
        opacity: 1; /* Hiển thị khi menu avatar đang mở */
    }
    .avatar-menu.active .edit-icon {
        opacity: 1; /* Hiển thị khi menu avatar đang mở */
    }

</style>

<header class="header">
    <div class="logo">
        <h2><img class="logo" src="uploads/logo5.png" onclick="window.location.href='index.php';"></h2>
    </div>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Trang chủ</a></li>
            <li><a href="index.php#about-section">Giới Thiệu</a></li>
            <li><a href="sanpham.php">Sản Phẩm</a></li>
            <li><a href="index.php#customer-section">Đóng góp</a></li>
            <li><a href="index.php#contact-section">Liên Hệ</a></li>
            <li><a href="hoso.php">Hồ sơ</a></li>
            <?php if (!isset($khachhang)): ?>
                <li><a href="dangnhap.php">Đăng nhập</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Menu avatar chỉ hiển thị khi khách hàng đã đăng nhập -->
<?php if (isset($khachhang)): ?>
    <div class="avatar-menu">
        <span style="color:white;margin-left:5px; cursor: pointer;" onclick="window.location.href='dangxuat.php';" class="logout-text"><a>Đăng xuất</a></span>
        <span id="cart-count"></span>
        <img onclick="window.location.href='giohang.php';"   src="uploads/cart.jpg" alt="Giỏ hàng" class="menu-icon cart" title="Giỏ hàng">

        <?php
// Kiểm tra session và lấy số lượng đơn hàng
          $so_donhang = isset($_SESSION['so_donhang']) ? $_SESSION['so_donhang'] : 0;
         ?>

<!-- Nút hiển thị số lượng đơn hàng -->
<div class="sodonhang">
<?php echo $so_donhang; ?>
</div>

        <img onclick="window.location.href='donhang.php';"   src="uploads/order.webp" alt="Đơn hàng" class="menu-icon order" title="Đơn hàng">
        <!-- Hiển thị avatar của khách hàng -->
        <img src="uploads/edit.png" class="edit-icon" onclick="openFilePicker()"></img>

        <?php
    // Kiểm tra nếu avatar không tồn tại hoặc là rỗng, hiển thị ảnh mặc định
    $avatar = !empty($khachhang['avatar']) ? $khachhang['avatar'] : 'uploads/user.png';
        ?>
<img id="customer-avatar" src="<?php echo $avatar; ?>" alt="Avatar" class="menu-icon avatar" onclick="toggleMenu()">

    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    let cartCount = localStorage.getItem('cart-count') || 0;
    document.getElementById('cart-count').innerText = cartCount;
});
</script>

    <script>
    function toggleMenu() {
        document.querySelector('.avatar-menu').classList.toggle('active');
    }

    // Đóng menu khi click ra ngoài
    document.addEventListener('click', function(event) {
        const avatarMenu = document.querySelector('.avatar-menu');
        const avatarIcon = document.querySelector('.menu-icon.avatar');

        // Kiểm tra nếu click ngoài avatar-menu và avatarIcon
        if (!avatarMenu.contains(event.target) && !avatarIcon.contains(event.target)) {
            avatarMenu.classList.remove('active'); // Xóa lớp active nếu click ra ngoài
        }
    });

    function openFilePicker() {
        const avatarMenu = document.querySelector('.avatar-menu');
        
        // Kiểm tra xem avatar-menu có đang mở không
        if (!avatarMenu.classList.contains('active')) {
            return; // Nếu không mở, thoát khỏi hàm
        }

        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*';
        fileInput.onchange = function(event) {
            const file = event.target.files[0];
            if (file) {
                // Tạo một form để gửi file đến update_avatar.php
                const formData = new FormData();
                formData.append('new_avatar', file);
                formData.append('makhachhang', <?php echo json_encode($khachhang['makhachhang']); ?>); // Chuyển ID khách hàng từ PHP sang JavaScript

                fetch('update_avatar.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.avatar) {
                        // Cập nhật avatar mới
                        document.getElementById('customer-avatar').src = data.avatar;
                    } else {
                        console.error('Error:', data);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        };
        fileInput.click(); // Mở hộp thoại chọn tệp
    }
</script>

<?php endif; ?>
