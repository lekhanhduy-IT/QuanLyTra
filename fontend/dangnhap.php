<?php
session_start();

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
// Xử lý đăng nhập
// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Sử dụng mã hóa MD5 cho mật khẩu

    $query = "SELECT * FROM khachhang WHERE email = ? AND matkhau = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $khachhang = $result->fetch_assoc();
        $_SESSION['khachhang'] = $khachhang; // Lưu thông tin khách hàng vào session
        $_SESSION['login_success_message'] = "Đăng nhập thành công!"; // Lưu thông báo đăng nhập
        header("Location: index.php"); // Chuyển hướng về trang index.php
        exit();
    } else {
        $error = "Email hoặc mật khẩu không đúng!";
    }
}



// Xử lý đăng ký
if (isset($_POST['register'])) {
    $tenkhachhang = $_POST['tenkhachhang'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $diachi = $_POST['diachi'];
    $dienthoai = $_POST['dienthoai'];

    $query = "INSERT INTO khachhang (tenkhachhang, email, matkhau, diachi, dienthoai) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $tenkhachhang, $email, $password, $diachi, $dienthoai);

    if ($stmt->execute()) {
        // Lưu thông báo vào session để hiển thị sau khi chuyển hướng
        $_SESSION['success_message'] = "Đăng ký thành công!";
        header('Location: dangnhap.php');
        exit();
    } else {
        $error = "Đăng ký không thành công: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập / Đăng Ký</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
body {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f0f0f0;
    font-family: Arial, sans-serif;
    background: url('uploads/nenmo2.png') no-repeat center center; 
    background-size: cover; /* Để ảnh lấp đầy mà không bị méo */
    background-repeat: no-repeat; /* Không lặp lại ảnh nền */
    background-position: center; /* Căn giữa ảnh nền */
    border-radius: 10px 0 0 10px; /* Chỉ bo góc bên trái */

}

.container {
    margin-top: 80px;
    width: 800px; 
    height: 450px;
    display: flex;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.thumb {
    width: 100%; 
    height: 100%; 
    background: url('uploads/thumb6.png') no-repeat center center; 
    background-size: cover; /* Để ảnh lấp đầy mà không bị méo */
    background-repeat: no-repeat; /* Không lặp lại ảnh nền */
    background-position: center; /* Căn giữa ảnh nền */
    border-radius: 10px 0 0 10px; /* Chỉ bo góc bên trái */
    transition: background 0.5s ease-in-out; /* Hiệu ứng chuyển nền mượt */

}


/* Đặt các thành phần trong form theo chiều dọc */
.form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 300px;
    margin: 0px auto;
    margin-right: 0px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    background-color: #fff;
    border-radius: 0 10px 10px 0 ; /* Chỉ bo góc bên phải */
}

.form-container h2 {
    margin-bottom: 20px;
}

.form-container form {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.form-container input, .form-container button {
    margin-bottom: 15px;
    padding: 10px;
    font-size: 14px;
    border-radius: 5px;
    outline: none;
    opacity: 0;
    transform: translateY(50px);
    animation: slideUp 0.6s forwards ease-out;
    border: none;
            border-radius: 5px;
            color: #ffffff;
            border-radius: 25px ;
            background: rgba(2, 62, 11, 0.685);
        }

        input::placeholder {
    color: #fff;
}

.form-container button {
    background-color:  #00a651;
    color: white;
    border: none;
    cursor: pointer;
}

.form-container button:hover {
    background-color: #0056b3;
}



/* Hiệu ứng xuất hiện từ dưới lên */
@keyframes slideUp {
    0% {
        opacity: 0;
        transform: translateY(50px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Để tạo khoảng cách thời gian giữa các phần tử */
input:nth-child(1) {
    animation-delay: 0.2s;
}
input:nth-child(2) {
    animation-delay: 0.4s;
}
button {
    animation-delay: 0.6s;
}


input:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    outline: none;
}

.hidden {
    display: none;
}




.btn:hover {
    background-color: #45a049;
}

.toggle-btn {
    cursor: pointer;
    color: gray;
    text-align: center;
    display: block;
    margin-top: 10px;
}
.toggle-btn:hover {
    cursor: pointer;
    color: blue;}
.error, .success {
    color: red;
    text-align: center;
}

.success {
    color: green;
}

@keyframes slide-up {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
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
    </style>
</head>
<body>
    <?php include('header.php');?>
    <div class="container" id="container">
        <div class="thumb"></div>
        <div class="form-container" id="login-form">
            <h2>Đăng Nhập</h2>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit" name="login" class="btn">Đăng Nhập</button>
                <p class="toggle-btn" onclick="toggleForms()">Chưa có tài khoản? Đăng ký</p>
            </form>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </div>
        <div class="form-container hidden" id="register-form">
            <h2>Đăng Ký</h2>
            <form method="POST" action="">
                <input type="text" name="tenkhachhang" placeholder="Tên khách hàng" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <input type="text" name="diachi" placeholder="Địa chỉ" required>
                <input type="text" name="dienthoai" placeholder="Điện thoại" required>
                <button type="submit" name="register" class="btn">Đăng Ký</button>
                <p class="toggle-btn" onclick="toggleForms()">Đã có tài khoản? Đăng nhập</p>
            </form>
            <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </div>
    </div>

    <script>
function toggleForms() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const thumb = document.querySelector('.thumb');

    if (loginForm.classList.contains('hidden')) {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        thumb.style.background = "url('uploads/thumb4.png') no-repeat center center"; // Nền cho login
        thumb.style.backgroundSize = "cover"; /* Để ảnh lấp đầy mà không bị méo */
        thumb.style.backgroundRepeat = "no-repeat"; /* Không lặp lại ảnh nền */
        thumb.style.backgroundPosition = "center"; /* Căn giữa ảnh nền */
    } else {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        thumb.style.background = "url('uploads/thumb3.png') no-repeat center center"; // Nền cho register
        thumb.style.backgroundSize = "cover"; /* Để ảnh lấp đầy mà không bị méo */
        thumb.style.backgroundRepeat = "no-repeat"; /* Không lặp lại ảnh nền */
        thumb.style.backgroundPosition = "center"; /* Căn giữa ảnh nền */
    }
}
</script>

<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="success-message" id="successMessage">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Xóa thông báo sau khi hiển thị
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

<script>
        // Kiểm tra xem có tín hiệu dangnhapClick không
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('dangnhapClick')) {
            // Tự động click nút "Đăng Nhập"
            document.querySelector('button[name="login"]').click();
        }
    </script>
</body>
</html>
