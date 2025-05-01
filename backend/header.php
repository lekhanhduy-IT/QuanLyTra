<?php
$servername = "localhost"; // Địa chỉ máy chủ
$username = "root"; // Tên người dùng
$password = ""; // Mật khẩu
$dbname = "qltra"; // Tên cơ sở dữ liệu
$port ='3406';
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn admin với id=1
$result = $conn->query("SELECT trang_thai FROM admin WHERE id = 1");
$row = $result->fetch_assoc();
$trang_thai = $row['trang_thai'];

// Kiểm tra trạng thái của admin
$isAdminActive = ($trang_thai != 0);
?>
<header class="header">
    <div class="logo">
        <h2><img class="logo" src="uploads/logo5.png" onclick="window.location.href='index.php';"></h2>
    </div>

</header>

<script>
    // Nếu trạng thái của admin = 0, ngăn chặn click vào các liên kết
    <?php if (!$isAdminActive): ?>
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết
            showNotification(event); // Hiển thị thông báo
        });
    });

    function showNotification(event) {
        const notification = document.getElementById('notification');
        notification.style.left = `${event.pageX}px`; // Đặt vị trí thông báo theo vị trí click
        notification.style.top = `${event.pageY}px`;
        notification.style.opacity = 1; // Hiện thông báo
        setTimeout(() => {
            notification.style.opacity = 0; // Ẩn thông báo sau 3 giây
        }, 3000);
    }
    <?php endif; ?>
</script>


<!-- Menu avatar chỉ hiển thị khi khách hàng đã đăng nhập -->


<style>
    /* Logo */
    .logo {
        top:10px;
        left: 60px;
        height: 50px;
        position: fixed;
    }
    .logo:hover { 
        cursor: pointer;
    }

    /* Header */
/* Header */
.header {
        background: linear-gradient(to bottom, #000000a8, rgba(0, 0, 0, 0.548), rgba(0, 0, 0, 0.01));
    color: #fff;
    padding: 20px;
    display: flex;
    justify-content: flex-end; /* Dồn nội dung về bên phải */
    align-items: center;
    position: fixed;
    z-index: 5000;
    width: 100%;
    top: 0;
}

/* Navbar */
.navbar {
    display: flex; /* Sử dụng flexbox cho navbar */
    margin-right: 40px;

}

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

