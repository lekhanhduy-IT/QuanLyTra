<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập
$khachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang'] : null;

if (!$khachhang) {
    echo "Vui lòng đăng nhập để xem giỏ hàng.";
    exit();
}
?>

<?php 
// Kết nối tới cơ sở dữ liệu
$host = 'localhost';
$db = 'qltra';
$user = 'root'; // replace with your DB username
$pass = ''; // replace with your DB password
$port ='3406';

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$sql = "SELECT * FROM sanpham";
$result = $mysqli->query($sql);


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Giỏ hàng - Quản lý trà</title>
 
</head>
<body>
    
<?php include('header.php'); ?>
<div class="wapper">
    <main>
        <div class="cart-container">
            <h1>Giỏ hàng của bạn</h1>
            <div id="cart-items"></div>
            <div id="cart-total"></div>
            <div class="tuy-chon">
            <button id="order-button" onclick="datHang()">Đặt hàng</button>
            <button id="clear-cart-button" onclick="xoaTatCa()">Xóa tất cả</button>
            </div>
        </div>
    </main>

<script>
    
// Hiển thị giỏ hàng từ localStorage
function hienThiGioHang() {
    let khachhang = <?php echo json_encode($khachhang['makhachhang']); ?>;
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang)) || [];

    let cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = '';

    let total = 0;

    if (gioHang.length === 0) {
        cartItemsContainer.innerHTML = '<p>Giỏ hàng của bạn trống.</p>';
        return;
    }

    gioHang.forEach((item, index) => {
        let itemTotal = item.gia * item.soluong;
        total += itemTotal;

        cartItemsContainer.innerHTML += `
<div class="cart-item" >
    <img src="uploads/${item.hinhanh1}" alt="${item.tensanpham}" onclick="window.location.href='chitietsanpham.php?masanpham=${item.masanpham}'">
    <p>${item.tensanpham}</p>
    <div class="tuychon">
    <button onclick="capNhatSoLuong(${index}, 1)">+</button>
    <input type="number" min="1" max="${item.soluong}" value="${item.soluong}" onchange="capNhatSoLuong(${index}, this.value)">
    <button onclick="capNhatSoLuong(${index}, -1)">-</button>
    <p style="color: green;">Tổng: ${itemTotal.toLocaleString()} VNĐ</p>
    </div>
    <button onclick="datHangSingle(${item.masanpham})">Đặt hàng</button>
    <button onclick="xoaSanPham(${index})" style="margin-left: 5px;">Xóa</button>
</div>

        `;
    });

    document.getElementById('cart-total').innerText = `Tổng cộng: ${total.toLocaleString()} VNĐ`;
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
function capNhatSoLuong(index, change) {
    let khachhang = <?php echo json_encode($khachhang['makhachhang']); ?>;
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang));
    let currentQuantity = gioHang[index].soluong;

    // Tính toán số lượng mới
    let newQuantity = currentQuantity + change;

    // Kiểm tra số lượng mới không nhỏ hơn 1 và không vượt quá số lượng trong kho
    if (newQuantity < 1) {
        alert("Số lượng phải lớn hơn 0!");
        return;
    }
    if (newQuantity > gioHang[index].soluong) {
        alert("Số lượng vượt quá giới hạn!");
        return;
    }

    gioHang[index].soluong = newQuantity;
    localStorage.setItem('giohang_' + khachhang, JSON.stringify(gioHang));

    hienThiGioHang();
}

function kiemTraSoLuong(masanpham) {
    return new Promise((resolve, reject) => {
        // Gửi yêu cầu AJAX tới server để kiểm tra số lượng
        fetch(`kiemTraSoLuong.php?masanpham=${masanpham}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resolve(data.soluong);
                } else {
                    reject(data.message);
                }
            })
            .catch(error => reject("Có lỗi xảy ra."));
    });
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
async function capNhatSoLuong(index, change) {
    let khachhang = <?php echo json_encode($khachhang['makhachhang']); ?>;
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang));
    let currentQuantity = gioHang[index].soluong;

    // Tính toán số lượng mới
    let newQuantity = currentQuantity + change;

    // Kiểm tra số lượng mới không nhỏ hơn 1
    if (newQuantity < 1) {
        alert("Số lượng phải lớn hơn 0!");
        return;
    }

    // Kiểm tra số lượng thực tế trong cơ sở dữ liệu
    let soluongThucTe = await kiemTraSoLuong(gioHang[index].masanpham);
    
    // Nếu số lượng mới lớn hơn số lượng thực tế
    if (newQuantity > soluongThucTe) {
        alert("Số lượng vượt quá số lượng có sẵn!");
        return;
    }

    gioHang[index].soluong = newQuantity;
    localStorage.setItem('giohang_' + khachhang, JSON.stringify(gioHang));

    hienThiGioHang();
}


function datHang() {
    let khachhang = <?php echo json_encode($khachhang['makhachhang']); ?>;
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang)) || [];

    if (gioHang.length === 0) {
        alert('Giỏ hàng của bạn trống.');
        return;
    }

    // Tính tổng tiền của tất cả sản phẩm trong giỏ hàng
    let tongTien = gioHang.reduce((total, item) => total + (item.gia * item.soluong), 0);

    // Chuẩn bị dữ liệu cho đơn hàng
    let formData = new FormData();
    formData.append('khachhang', khachhang);
    formData.append('tongtien', tongTien);
    formData.append('giohang', JSON.stringify(gioHang)); // Gửi toàn bộ giỏ hàng lên server

    // Gửi yêu cầu AJAX để đặt hàng
    fetch('dathang_all.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            localStorage.removeItem('giohang_' + khachhang); // Xóa giỏ hàng sau khi đặt hàng thành công
            hienThiGioHang(); // Cập nhật giao diện giỏ hàng
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        alert('Có lỗi xảy ra khi đặt hàng.');
    });
}


// Xử lý nút Đặt hàng cho từng sản phẩm
function datHangSingle(masanpham) {
    let khachhang = <?php echo json_encode($khachhang['makhachhang']); ?>;
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang));
    
    let sanpham = gioHang.find(item => item.masanpham == masanpham);

    let formData = new FormData();
    formData.append('masanpham', sanpham.masanpham);
    formData.append('soluong', sanpham.soluong);
    formData.append('tongtien', sanpham.gia * sanpham.soluong);
    

    fetch('dathang_sanpham.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            xoaSanPham(gioHang.indexOf(sanpham)); // Xóa sản phẩm khỏi giỏ hàng
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        alert('Có lỗi xảy ra khi đặt hàng.');
    });
}


// Xử lý nút Đặt hàng cho giỏ hàng

// Hiển thị giỏ hàng khi trang được tải
hienThiGioHang();



// Xóa một sản phẩm khỏi giỏ hàng
function xoaSanPham(index) {
    let khachhang = <?php echo json_encode($khachhang['makhachhang']); ?>;
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang));

    // Xóa sản phẩm tại vị trí index
    gioHang.splice(index, 1);
    localStorage.setItem('giohang_' + khachhang, JSON.stringify(gioHang));

    hienThiGioHang();
}

// Xóa tất cả sản phẩm khỏi giỏ hàng
function xoaTatCa() {
    let khachhang = <?php echo json_encode($khachhang['makhachhang']); ?>;
    localStorage.removeItem('giohang_' + khachhang);
    hienThiGioHang();
}

// Đếm số lượng sản phẩm khác nhau trong giỏ hàng
function demSanPhamTrongGioHang() {
    let khachhang = <?php echo json_encode($khachhang['makhachhang']); ?>;
    let gioHang = JSON.parse(localStorage.getItem('giohang_' + khachhang)) || [];

    // Đếm số lượng sản phẩm khác nhau
    let soLuongSanPham = gioHang.length;

    // Cập nhật số lượng sản phẩm lên header
    document.getElementById('cart-count').innerText = soLuongSanPham;

    // Lưu số lượng sản phẩm vào localStorage
    localStorage.setItem('cart-count', soLuongSanPham);
}


// Hiển thị giỏ hàng và số lượng sản phẩm khi trang được tải
hienThiGioHang();
demSanPhamTrongGioHang();

</script>

<?php include('footer.php'); ?>


</div>
</body>
</html>
<style>
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
        padding-right: 50px;
    }

    /* Navbar */
    .navbar ul {
        list-style: none;
        display: flex;
        padding-right: 28px;

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
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
    }

    /* Đặt lại một số kiểu mặc định */
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



html, body {
    height: 100%;
    margin: 0;
    overflow: hidden; /* Ngăn không cho body cuộn */
    font-family: 'Roboto', sans-serif;
}

body {
    background: url('uploads/nenmo2.png') no-repeat center top/cover;
}


.wapper {
    width: 100%;
    padding-top: 40px;
    height: calc(100% - 0px); /* Chiều cao wapper bằng chiều cao body trừ đi padding-top */
    overflow-y: auto; /* Cho phép cuộn trong wapper */
    /* Bạn có thể thêm các thuộc tính khác cho wapper tại đây */
}


/* Định dạng tiêu đề */
.cart-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

h1 {
    text-align: center;
    color: #333;
}

/* Định dạng các mặt hàng trong giỏ hàng */
.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

.cart-item img {
    width: 100px;
    height: auto;
    margin-right: 15px;
    border-radius: 5px;
}

.cart-item p {
    flex-grow: 1;
    margin: 0 10px;
}

/* Định dạng input số lượng */
.cart-item input[type="number"] {
    width: 60px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    text-align: center;
    font-size: 16px;
    margin-left: 5px;
    margin-right: 5px;
}

/* Định dạng nút tăng giảm */
.cart-item button {
    display: inline-block;
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 50px;
    border: none;
    transition: background-color 0.3s;
    font-weight: bold;

    transition: background-color 0.3s ease;
}

.cart-item button:hover {
    background-color: #0056b3;
}

/* Định dạng tổng cộng */
#cart-total {
    font-size: 20px;
    text-align: right;
    margin-top: 20px;
    font-weight: bold;
}

/* Định dạng nút Đặt hàng */
#order-button {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#order-button:hover {
    background-color: #218838;
}

#clear-cart-button {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-align: center;

}

#clear-cart-button:hover {
    background-color: #218838;
}
.tuychon {
    display: flex;
    justify-content: center; /* Căn giữa theo chiều ngang */
    align-items: center; /* Căn giữa theo chiều dọc */
    width: 50%;
    text-align: justify;
    z-index: 2;
}
.tuy-chon {
    display: flex;
    justify-content: center; /* Căn giữa theo chiều ngang */
    align-items: center; /* Căn giữa theo chiều dọc */
    width:30%;
    text-align: center;
    z-index: 2;
    margin: 0 auto;

}

.sodonhang {
    position: absolute;
    z-index: 4000;
    width: 14px;
    height: 18px;
    border-radius: 50%;
    cursor: pointer;
    right: 50px;
    top: 5px;
    font-size: 11px;
    text-align: center;
    color: white;
    background: green;
    padding-bottom:0px;
    padding-top:-8px;
    border: 1px solid white;
    opacity: 0; /* Ẩn biểu tượng ban đầu */
    transition: opacity 0.3s ease; /* Hiệu ứng chuyển tiếp */
}
#cart-count{
    position: absolute;
    z-index: 4000;
    width: 14px;
    height: 18px;
    border-radius: 50%;
    cursor: pointer;
    right: 100px;
    top: 5px;
    font-size: 11px;
    text-align: center;
    color: white;
    background: green;
    padding-bottom:0px;
    padding-top:-8px;
    border: 1px solid white;
    opacity: 0; /* Ẩn biểu tượng ban đầu */
    transition: opacity 0.3s ease; /* Hiệu ứng chuyển tiếp */
}
</style>

