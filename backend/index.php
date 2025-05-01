<?php
// Kết nối cơ sở dữ liệu
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
// Truy vấn để lấy trang_thai của admin có id=1
$query = "SELECT trang_thai FROM admin WHERE id = 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Kiểm tra trang_thai
    if ($row['trang_thai'] == 0) {
        // Chuyển hướng đến trang dangnhap.php
        header("Location: logout.php");
        exit;
    } else {
        // Nếu trang_thai = 1, hiển thị thông báo "Đăng nhập thành công"
        echo '
        <div id="success-message" style="position: fixed; 
                                         top: 200px; right: 500px;
                                         background-color: #4CAF50;
                                         color:white; padding: 10px;
                                          border-radius: 25px; 
                                          margin: 0 auto; 
                                          
                                          border:1px solid :#4CAF50; ">
            Đăng nhập thành công
        </div>
        <script>
            // Tạo hiệu ứng biến mất sau 1 giây
            setTimeout(function() {
                var message = document.getElementById("success-message");
                if (message) {
                    message.style.display = "none";
                }
            }, 1000);
        </script>
        ';
    }
} else {
    echo "Không tìm thấy dữ liệu admin.";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", content="initial-scale=1.0">
    <title>Giao diện Quản trị viên</title>
    <style>

 /* Thiết kế thông báo */

        /* Thêm CSS cho màu xanh lá cây khi active */
        #menu a.active {
            color: orange; /* Màu xanh lá cây cho chữ */
        }
        .ground.active {
             display: block; 
             


         }
 
    </style>
</head>
<body>
<script>
    // Tự động ẩn thông báo sau 1 giây
    setTimeout(function() {
    var alert = document.getElementById('alert');
    if (alert) { // Kiểm tra xem alert có tồn tại không
        alert.classList.add('hide');
    }
}, 1000);
 // Thời gian hiển thị là 1 giây
</script>

<div class="container">
    <div class="sidebar">
    <div class="logo">
        <h2><img class="logo" src="uploads/logo5.png" onclick="window.location.href='index.php';"></h2>
    </div>
        <h2 style="margin-left:10px;color:white;">Quản trị viên</h2>
        <ul id="menu">
            <img class="ground" src="uploads/menu8.png" style="position:absolute; transition: top 0.3s; opacity:0;">
            <li><a href="javascript:void(0);" data-page="main.php">Trang chủ</a></li> 
            <li><a href="javascript:void(0);" data-page="quanlysanpham/sanpham.php">Sản phẩm</a></li>
            <li><a href="javascript:void(0);" data-page="quanlydonhang/donhang.php">Đơn hàng</a></li>
            <li><a href="javascript:void(0);" data-page="quanlykhachhang/khachhang.php">Khách hàng</a></li>
            <li><a href="javascript:void(0);" data-page="quanlydanhgia/danhgia.php">Đánh giá</a></li>

            <li><a href="javascript:void(0);" data-page="logout.php">Đăng xuất</a></li>
        </ul>
    </div>
    <iframe class="content" id="content" src="main.php"  frameborder="0" width="100%" height="auto">
    </iframe>

</div>


<script>
    document.querySelectorAll('#menu li a').forEach(link => {
        link.addEventListener('click', function() {
            var page = this.getAttribute('data-page');
            document.getElementById('content').src = page;
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = document.querySelectorAll('#menu li');
        const groundImage = document.querySelector('#menu .ground');
        const contentDiv = document.getElementById('content');

        // Hàm tải nội dung trang
        function loadPage(page, index) {
            fetch(page)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    contentDiv.innerHTML = data; // Hiển thị nội dung tải về

                    // Cập nhật vị trí cho hình ảnh ground
                    const topPosition = index * 40; // Tính vị trí theo chỉ số mục menu
                    groundImage.style.top = `${topPosition}px`;
                })
                .catch(error => {
                    contentDiv.innerHTML = '<p>Error loading page: ' + error.message + '</p>';
                });
        }

        menuItems.forEach((item, index) => {
            const link = item.querySelector('a');
            link.addEventListener('click', function () {
                // Gọi hàm để tải nội dung trang và cập nhật vị trí ground
                loadPage(link.getAttribute('data-page'), index);

                // Cập nhật lớp active cho menu
                menuItems.forEach(li => {
                    li.classList.remove('active');
                    li.querySelector('a').classList.remove('active'); // Xóa lớp active khỏi thẻ a
                });
                item.classList.add('active');
                link.classList.add('active'); // Thêm lớp active vào thẻ a
            });

            item.addEventListener('mouseenter', function () {
                const topPosition = index * 40; // Tính vị trí theo chỉ số mục menu
                groundImage.style.top = `${topPosition}px`;
                groundImage.style.margin_right = -10;
                groundImage.style.opacity = 1; // Hiển thị hình ảnh
            });

            // Không cần sự kiện mouseleave
        });

        // Tải trang mặc định khi trang vừa tải
        loadPage('trangchu.php', 0); // Gọi loadPage với chỉ số 0 cho trang chủ

        // Đảm bảo rằng mục li.active hiển thị hình ảnh ngay từ đầu
        const defaultActiveItem = menuItems[0]; // Trang chủ là mục đầu tiên
        defaultActiveItem.classList.add('active');
        defaultActiveItem.querySelector('a').classList.add('active'); // Thêm lớp active cho thẻ a

        const defaultIndex = Array.from(menuItems).indexOf(defaultActiveItem);
        groundImage.style.top = `${defaultIndex * 40}px`;
        groundImage.style.opacity = 1; // Hiển thị hình ảnh ngay từ đầu
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = document.querySelectorAll('#menu li');
        const groundImage = document.querySelector('#menu .ground');
        const contentDiv = document.getElementById('content');
        
        // Hàm tải nội dung trang
        function loadPage(page, index) {
            fetch(page)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    contentDiv.innerHTML = data; // Hiển thị nội dung tải về

                    // Cập nhật vị trí cho hình ảnh ground
                    const topPosition = index * 40; // Tính vị trí theo chỉ số mục menu
                    groundImage.style.top = `${topPosition}px`;
                })
                .catch(error => {
                    contentDiv.innerHTML = '<p>Error loading page: ' + error.message + '</p>';
                });
        }

        menuItems.forEach((item, index) => {
            const link = item.querySelector('a');
            link.addEventListener('click', function () {
                // Gọi hàm để tải nội dung trang và cập nhật vị trí ground
                loadPage(link.getAttribute('data-page'), index);

                // Cập nhật lớp active cho menu
                menuItems.forEach(li => {
                    li.classList.remove('active');
                    li.querySelector('a').classList.remove('active'); // Xóa lớp active khỏi thẻ a
                });
                item.classList.add('active');
                link.classList.add('active'); // Thêm lớp active vào thẻ a
            });

            item.addEventListener('mouseenter', function () {
                const topPosition = index * 40; // Tính vị trí theo chỉ số mục menu
                groundImage.style.top = `${topPosition}px`;
                groundImage.style.opacity = 1; // Hiển thị hình ảnh
            });

            item.addEventListener('mouseleave', function () {
                // Không làm gì ở đây, để hình ảnh luôn hiển thị
            });
        });

        // Tải trang mặc định khi trang vừa tải
        loadPage('trangchu.php', 0); // Gọi loadPage với chỉ số 0 cho trang chủ

        // Đảm bảo rằng mục li.active hiển thị hình ảnh ngay từ đầu
        const defaultActiveItem = menuItems[0]; // Trang chủ là mục đầu tiên
        defaultActiveItem.classList.add('active');
        defaultActiveItem.querySelector('a').classList.add('active'); // Thêm lớp active cho thẻ a

        const defaultIndex = Array.from(menuItems).indexOf(defaultActiveItem);
        groundImage.style.top = `${defaultIndex * 40}px`;
        groundImage.style.opacity = 1; // Hiển thị hình ảnh ngay từ đầu
    });
</script>







</body>



</html>
<style>
    .logo {
        top:10px;
        left: 20px;
        height: 50px;
        position: fixed;
    }
    .logo:hover { 
        cursor: pointer;
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
    padding: 0;
    overflow: hidden; /* Ngăn không cho body cuộn */
}

body {
    background: url('../fontend/uploads/nenmo2.png') no-repeat center top/cover;
}

.container {
    display: flex;
    justify-content: center;   /* Căn giữa theo chiều ngang */
    align-items: center;       /* Căn giữa theo chiều dọc */
    width: 100vw;              /* Chiều rộng 100% của viewport */
    height: 100vh;             /* Chiều cao 100% của viewport */
    margin: 0;                 /* Đảm bảo không có khoảng cách */
    box-sizing: border-box;     /* Bao gồm cả padding và border trong kích thước phần tử */

}


.sidebar {
    width: 200px;
    background-color: #009451; /* Màu nền xanh */
    /* border-top-left-radius: 10px; /* Bo góc trái */
    /* border-bottom-left-radius: 10px; /* Bo góc trái */
    padding-left: 10px;
    padding-top:180px;
    padding-bottom: 10px;
    height: 100%;


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
    overflow-y: scroll; 
    /*border-top-right-radius: 10px; /* Bo góc phải */
    /*border-bottom-right-radius: 10px; /* Bo góc phải */
    padding-top: 0px;
    background-color: #fff; /* Màu nền cho nội dung */
    overflow-y: auto; /* Cho phép cuộn dọc khi nội dung vượt quá chiều cao */
   height: 100%;
}



.content h1 {
    margin-top: 0;
}

</style>