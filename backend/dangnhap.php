<?php
session_start();
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
// Kiểm tra nếu admin đã đăng nhập hay chưa
if (isset($_SESSION['admin'])) {
    $admin = $_SESSION['admin'];

    // Kiểm tra trạng thái trang_thai của admin
    if ($admin['trang_thai'] == 0) {
        // Vô hiệu hóa tất cả click ngoại trừ div có class "sidebar"
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.body.addEventListener('click', function(e) {
                    if (!e.target.closest('.sidebar')) {
                        e.preventDefault();
                        alert('Bạn cần đăng nhập');
                    }
                });
            });
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, content="initial-scale=1.0">
    <title>Giao diện Quản trị viên</title>
</head>
<body>
<div id="header" class="<?= !isset($_SESSION['loggedin']) ? 'disabled' : '' ?>">
<?php include('header.php'); ?>
    </div>
<div class="container">
    <div class="sidebar">
        <h2 style="margin-left:10px;color:white;">Quản trị viên</h2>
        <div class="<?= $disabledClass ?>" id="disabledDiv">
        <ul id="menu">
        <img class="ground" src="uploads/menu8.png" style="position:absolute; transition: top 0.3s; opacity:0;">
        <li><a href="#">Trang chủ</a></li>
        <li><a href="#">Sản phẩm</a></li>
        <li><a href="#">Đơn hàng</a></li>
        <li><a href="#">Khách hàng</a></li>
        <li><a href="#">Đăng xuất</a></li>
    </ul>

    <!-- Phần thông báo -->
    <div id="notification" class="notification" 
           style="            
           position: absolute;
            background-color: white;
            color: green;
            width:130px;
            padding: 5px;
            border-radius: 25px;
            border: 1px solid green;
            opacity: 0;
            z-index:100;
            margin-top:-85px;
            transition: opacity 1s ease-out;
            pointer-events: none; /* Không cho phép tương tác với thông báo */
            ">Bạn cần đăng nhập</div>

<script>
    // Lấy danh sách các phần tử <a> và phần tử thông báo
    const menuItems = document.querySelectorAll('#menu li a');
    const notification = document.getElementById('notification');

    // Hàm để hiển thị thông báo tại vị trí click
    function showNotification(event) {
        const x = event.clientX;
        const y = event.clientY;

        // Đặt vị trí của thông báo theo vị trí click
        notification.style.left = x + 'px';
        notification.style.top = y + 'px';

        // Cập nhật nội dung thông báo
        notification.textContent = 'Bạn cần đăng nhập';

        // Hiển thị thông báo với độ mờ tăng lên
        notification.style.opacity = '1';

        // Ẩn thông báo sau 1 giây
        setTimeout(() => {
            notification.style.opacity = '0';
        }, 1000);
    }

    // Thêm sự kiện click vào từng phần tử <a>
    menuItems.forEach(item => {
        item.addEventListener('click', showNotification);
    });
</script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuItems = document.querySelectorAll('#menu li');
            const groundImage = document.querySelector('#menu .ground');
            const contentDiv = document.getElementById('content');

            // Hàm tải nội dung trang
            function loadPage(page) {
                fetch(page)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        contentDiv.innerHTML = data; // Hiển thị nội dung tải về
                    })
                    .catch(error => {
                        contentDiv.innerHTML = '<p>Error loading page: ' + error.message + '</p>';
                    });
            }

            menuItems.forEach((item, index) => {
                const link = item.querySelector('a');
                link.addEventListener('click', function () {
                    // Gọi hàm để tải nội dung trang
                    loadPage(link.getAttribute('data-page'));

                    // Cập nhật lớp active cho menu
                    menuItems.forEach(li => {
                        li.classList.remove('active');
                        li.querySelector('a').classList.remove('active'); // Xóa lớp active khỏi thẻ a
                    });
                    item.classList.add('active');
                    link.classList.add('active'); // Thêm lớp active vào thẻ a

                    // Cập nhật vị trí cho hình ảnh ground
                    const topPosition = index * 40; // Tính vị trí theo chỉ số mục menu
                    groundImage.style.top = `${topPosition}px`;

                    groundImage.style.opacity = 1; // Hiển thị hình ảnh
                });

                item.addEventListener('mouseenter', function () {
                    const topPosition = index * 40; // Tính vị trí theo chỉ số mục menu
                    groundImage.style.top = `${topPosition}px`;
                    groundImage.style.opacity = 1; // Hiển thị hình ảnh
                });

                item.addEventListener('mouseleave', function () {
                    if (!item.classList.contains('active')) {
                        groundImage.style.opacity = 0; // Ẩn hình ảnh khi rời khỏi mục
                    }
                });
            });

            // Tải trang mặc định khi trang vừa tải
            loadPage('trangchu.php');

            // Đảm bảo rằng mục li.active hiển thị hình ảnh ngay từ đầu
            const defaultActiveItem = menuItems[0]; // Trang chủ là mục đầu tiên
            defaultActiveItem.classList.add('active');
            defaultActiveItem.querySelector('a').classList.add('active'); // Thêm lớp active cho thẻ a

            const defaultIndex = Array.from(menuItems).indexOf(defaultActiveItem);
            groundImage.style.top = `${defaultIndex * 40}px`;
            groundImage.style.opacity = 1; // Hiển thị hình ảnh
        });
    </script>

    </div>
    </div>
<div class="content">
    <div class="login-form">
        <h2>Đăng nhập</h2>
        <form action="login_xuly.php" method="POST">
            <label for="email">Tài khoản</label><br>
            <input type="text" id="email" name="email" placeholder="Nhập tài khoản"><br>
            
            <label for="password">Mật khẩu</label><br>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu"><br>
            
            <button type="submit">Đăng nhập</button>
        </form>
   </div>
</div>

</div>


</body>



</html>
<style>
    <style>
 /* Thông báo */
/* Thông báo */
  /* CSS cho thông báo */
  .notification  {
            position: absolute;
            background-color: white;
            color: green;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid green;
            opacity: 0;
            transition: opacity 1s ease-out;
            pointer-events: none; /* Không cho phép tương tác với thông báo */
        }

        /* Đặt vị trí ban đầu của ảnh nền menu */
        .ground {
            position: absolute; 
            transition: top 0.3s; 
            opacity: 0;
        }
        /* Thêm CSS cho màu xanh lá cây khi active */
        #menu a.active {
            color: orange; /* Màu xanh lá cây cho chữ */
        }
                /* Ẩn click trên index.php và header.php nếu chưa đăng nhập */
        .disabled {
            pointer-events: none;
            opacity: 1;
        }
    </style>
<style>


    ::-webkit-scrollbar {
    width: 8px; /* Độ rộng của thanh cuộn */
}

/* Phần nền của thanh cuộn */
::-webkit-scrollbar-track {
    background: none; /* Trong suốt */
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

html, body {
    height: 100%;
    margin: 0;
    overflow: hidden; /* Ngăn không cho body cuộn */
}

body {
    background: url('../fontend/uploads/nenmo2.png') no-repeat center top/cover;
}


.container {
    display: flex;
    margin: 40px;
    margin-top:80px;


}

.sidebar {
    width: 200px;
    background-color: #009451; /* Màu nền xanh */
    border-top-left-radius: 10px; /* Bo góc trái */
    border-bottom-left-radius: 10px; /* Bo góc trái */
    padding-left: 10px;
    padding-top:10px;
    padding-bottom: 10px;
    min-height: calc(80vh - 60px); /* Chiều cao tối thiểu trừ đi khoảng cách 8px từ đáy màn hình */
    margin-bottom: 60px; /* Khoảng cách 8px phía dưới */

}

.sidebar h2 {
    color: white;
    margin: 0 0 10px 0;

}

.sidebar {
    position: relative; /* Để các phần tử bên trong có thể sử dụng position absolute */
}

/* Đặt vị trí của img.ground để nó không che chữ trong li>a */
#menu {
    position: relative;
}

#menu .ground {
    position: absolute;
    width: 100%;
    height: 90px; /* Chiều cao của hình ảnh */
    top: -10px;
    left:-0px;
    text-align:center;
    margin-top: -25px;
    z-index: 0; /* Đảm bảo hình ảnh nằm phía sau chữ */
    opacity: 0; /* Ban đầu ẩn hình ảnh */
    transition: top 0.3s, opacity 0.3s; /* Hiệu ứng chuyển đổi */
}

#menu li {
    position: relative;
    height: 40px; /* Chiều cao của từng mục menu */
    list-style: none;
    width: 100%;
   
}

#menu li a {
    display: block;
    padding: 10px;
    z-index: 1; /* Đảm bảo chữ nằm trên hình ảnh */
    position: relative;
    color: rgb(255, 255, 255);
    text-decoration: none;
    width: 100%;
    text-align: left; /* Đảm bảo chữ nằm bên trái */
}
#menu li a:hover { color: green;}

.content {
    background-image: url('uploads/tea9.png');
    background-position: center; /* Giữ vị trí trung tâm của hình nền */
    background-attachment: fixed; /* Giữ cố định khi cuộn */
    background-size: cover;
    width: calc(210vh - 60px); /* Đảm bảo container không vượt quá màn hình */
    height: 100%;
    padding-top: 50px;
    margin: 0;
    border-top-right-radius: 10px; /* Bo góc trái */
    border-bottom-right-radius: 10px; /* Bo góc trái */
    padding-right:20px;

    overflow: hidden; /* Ngăn không cho body cuộn */
    min-height: calc(80vh - 60px); /* Chiều cao tối thiểu trừ đi khoảng cách 8px từ đáy màn hình */
    margin-bottom: 60px; /* Khoảng cách 8px phía dưới */

}



.content h1 {
    margin-top: 0;
}

</style>
<style>



/* Đặt nền trong suốt cho form */
.login-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 50vh; /* Chiều cao toàn màn hình */
            text-align: center;
        }
        .login-form h2 {color: #009451; }
        .login-form label {color: #009451; }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
        }
        input {
            margin: 5px 0;
            padding: 10px;
            font-size: 14px;
            border: none;
            border-bottom: 1px solid  #009451; 
          
        }
        button {
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius:25px;
        }
        button:hover {
            background-color: #45a049;
        }
/* Áp dụng cho toàn bộ trang */
::-webkit-scrollbar {
    width: 8px; /* Độ rộng của thanh cuộn */
}

/* Phần nền của thanh cuộn */
::-webkit-scrollbar-track {
    background: none; /* Trong suốt */
}

/* Phần thanh cuộn */
::-webkit-scrollbar-thumb {
    background-color: none; /* Màu xanh lá đậm mờ với độ trong suốt */
    border-radius: 10px; /* Góc bo tròn */
}

/* Thanh cuộn khi được hover */
::-webkit-scrollbar-thumb:hover {
    background-color: none; /* Tăng độ đậm khi hover */
}

</style>
