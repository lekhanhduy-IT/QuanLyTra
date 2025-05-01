<?php
session_start();

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

// Kiểm tra xem người dùng đã đăng nhập hay chưa
$khachhang = null;
if (isset($_SESSION['khachhang'])) {
    $khachhang = $_SESSION['khachhang'];
}

// Xử lý cập nhật hồ sơ
if (isset($_POST['update_profile'])) {
    $makhachhang = $_POST['makhachhang'];
    $tenkhachhang = $_POST['tenkhachhang'];
    $email = $_POST['email'];
    $diachi = $_POST['diachi'];
    $dienthoai = $_POST['dienthoai'];

    // Chỉ cập nhật những trường khách hàng đã nhập, các trường không nhập sẽ giữ nguyên
    $query = "UPDATE khachhang SET tenkhachhang = ?, email = ?, diachi = ?, dienthoai = ? WHERE makhachhang = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssii", $tenkhachhang, $email, $diachi, $dienthoai, $makhachhang);

    if ($stmt->execute()) {
        // Cập nhật session với thông tin mới
        $_SESSION['khachhang']['tenkhachhang'] = $tenkhachhang;
        $_SESSION['khachhang']['email'] = $email;
        $_SESSION['khachhang']['diachi'] = $diachi;
        $_SESSION['khachhang']['dienthoai'] = $dienthoai;

        // Lưu thông báo vào session
        $_SESSION['success_message'] = "Cập nhật thành công!";
        
        // Chuyển hướng đến trang hoso.php
        header("Location: hoso.php");
        exit();
    } else {
        $error_message = "Cập nhật không thành công: " . $conn->error;
    }
}
?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('avatarUpdated')) {
        // Xóa trạng thái đã lưu
        localStorage.removeItem('avatarUpdated');
        
        // Reload dữ liệu
        location.reload(); // Hoặc bạn có thể gọi hàm loadData() của riêng bạn
    }
});

</script>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ khách hàng</title>
    <style>
        html, body {
    height: 100%;
    margin: 0;
    overflow: hidden; /* Ngăn không cho body cuộn */
}

           body {
            background: url('uploads/nenmo2.png') no-repeat center top/cover;

            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 50px auto;
            text-align: left;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 40px;
            height:400px;
        }
        .profile {
            display: flex;
            align-items: center;
            height:300px;

        }
        .profile img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            margin-right: 20px;
            object-fit: cover;
        }
        .profile-info {
            line-height: 1.6;
        }
        .profile-info h2 {
            margin: 0;
            color: #333;
        }
        .profile-info p {
            margin: 5px 0;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
        }
        .btn-update {
            background-color: #007BFF;
        }
        .btn-save {
            background-color: #28A745;
        }
        .message {
            margin-top: 20px;
            color: green;
        }
        a {
    color: blue; /* Màu chữ xanh */
    background-color: transparent; /* Không có nền */
    text-decoration: none; /* Không có gạch chân */
    text-align: right;
}
button {
    color: white; /* Màu chữ trắng */
    background-color: #3EB489; /* Nền xanh nhạt */
    border: none; /* Không có viền */
    padding: 10px 20px; /* Khoảng cách bên trong */
    border-radius: 5px; /* Bo tròn góc */
    cursor: pointer; /* Hiển thị con trỏ tay khi di chuột */
    transition: background-color 0.3s; /* Hiệu ứng chuyển màu nền khi hover */
}

button:hover {
    background-color: #2B8C6A; /* Màu nền khi hover */
}

    </style>
    <script>
        function enableEdit() {
            document.getElementById("edit-form").style.display = "block";
            document.getElementById("view-profile").style.display = "none";
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="profile">
            <img src="<?php echo isset($khachhang) ? $khachhang['avatar'] : 'uploads/user.png'; ?>" alt="Avatar">
            <div class="profile-info" id="view-profile">
                <!-- Hiển thị thông tin khách hàng -->
                <h2><?php echo isset($khachhang) ? $khachhang['tenkhachhang'] : 'Tên khách hàng'; ?></h2>
                <p><strong>Email:</strong> <?php echo isset($khachhang) ? $khachhang['email'] : ''; ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo isset($khachhang) ? $khachhang['diachi'] : ''; ?></p>
                <p><strong>Điện thoại:</strong> <?php echo isset($khachhang) ? $khachhang['dienthoai'] : ''; ?></p>
                
<!-- Kiểm tra xem có khách hàng hay không -->
<?php if (isset($khachhang)): ?>
    <!-- Nếu có khách hàng, hiển thị nút cập nhật hồ sơ -->
    <button class="btn btn-update" onclick="enableEdit()">Cập nhật hồ sơ</button>
    <br>
    <a href="index.php">Quay lại</a>
<?php else: ?>
    <!-- Nếu không có khách hàng, hiển thị nút Đăng nhập -->
    <a href="dangnhap.php">Đăng nhập</a>
<?php endif; ?>

            </div>

            <!-- Form cập nhật hồ sơ -->
            <form id="edit-form" style="display: none;" method="POST" action="">
                <input type="hidden" name="makhachhang" value="<?php echo $khachhang['makhachhang']; ?>">
                <h3><strong>Tên khách hàng:</strong> <input type="text" name="tenkhachhang" value="<?php echo $khachhang['tenkhachhang']; ?>"></h3>
                <p><strong>Email:</strong> <input type="text" name="email" value="<?php echo $khachhang['email']; ?>"></p>
                <p><strong>Địa chỉ:</strong> <input type="text" name="diachi" value="<?php echo $khachhang['diachi']; ?>"></p>
                <p><strong>Điện thoại:</strong> <input type="text" name="dienthoai" value="<?php echo $khachhang['dienthoai']; ?>"></p>
                <button type="submit" class="btn btn-save" name="update_profile">Lưu thông tin</button>

            </form>
        </div>

        <!-- Hiển thị thông báo thành công hoặc lỗi -->
        <?php if (isset($success_message)): ?>
            <div class="message"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="message" style="color: red;"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>

    <style>
        .avatar-menu {
        border-radius: 50px;
        position:fixed;
        padding: 5px;
        top: 60px;
        right: 230px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
        z-index: 2000;
        object-fit: cover;
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

<!-- Menu avatar chỉ hiển thị khi khách hàng đã đăng nhập -->
<?php if (isset($khachhang)): ?>

    <div class="sodonhang">
<?php echo $so_donhang; ?>
</div>
    <div class="avatar-menu">
        <span style="color:white;margin-left:5px; cursor: pointer;" onclick="window.location.href='dangxuat.php';" class="logout-text"><a style="color:white;">Đăng xuất</a></span>
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

        <img id="customer-avatar" src="<?php echo $khachhang['avatar']; ?>" alt="Avatar" class="menu-icon avatar" onclick="toggleMenu()">
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
<script>
    // Lắng nghe sự kiện storage
    window.addEventListener('storage', function(event) {
        if (event.key === 'reloadDashboard' && event.newValue === 'true') {
            // Nếu có thông báo về reload trang
            
            // Reset lại giá trị trong localStorage để tránh reload nhiều lần
            localStorage.removeItem('reloadDashboard');

            // Thực hiện reload trang
            location.reload();
        }
    });

    // Kiểm tra khi load trang lần đầu, nếu có thông báo cũ
    if (localStorage.getItem('reloadDashboard') === 'true') {
        localStorage.removeItem('reloadDashboard');
        location.reload();
    }
</script>

<?php endif; ?>
</body>
</html>
