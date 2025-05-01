<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div class="contai-ner">
<div class="view">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltra";
$port ='3406';
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql_reviews = "
SELECT d.masanpham, d.id, s.masanpham, d.noidung, d.sosao, d.hinhanh, d.ngaygui, k.avatar, k.tenkhachhang, s.hinhanh1, d.trangthai
FROM danhgia d
JOIN khachhang k ON d.makhachhang = k.makhachhang
JOIN sanpham s ON d.masanpham = s.masanpham
ORDER BY d.ngaygui DESC
";
$result_reviews = $conn->query($sql_reviews);

if ($result_reviews->num_rows > 0) {
    // Hiển thị từng đánh giá
    while ($row = $result_reviews->fetch_assoc()) {
        ?>
        <div class="review-card">
            <div class="review-header">
                <img src="../../fontend/<?php echo $row['avatar']; ?>" alt="Hình ảnh đánh giá" class="review-image" onclick='redirectToAnotherPage(<?php echo $row["makhachhang"]; ?>)'>
                <div class="review-info">
                    <h2><?php echo htmlspecialchars($row['tenkhachhang']); ?></h2>
                    <div class="star-rating">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            echo $i < $row['sosao'] ? '★' : '☆'; // Đánh giá sao
                        }
                        ?>
                    </div>
                    <p class="review-date"><?php echo date("d/m/Y", strtotime($row['ngaygui'])); ?></p>
                </div>
                <div class="product-info">
                    <h2>Sản phẩm</h2>
                    <a href="../quanlysanpham/edit.php?id=<?= $row['masanpham']; ?>">
                        <img src="../../fontend/uploads/<?php echo $row['hinhanh1']; ?>" alt="Hình ảnh sản phẩm" class="review-product">
                    </a>
                </div>
            </div>
            <div class="review-content">
                <?php if ($row['hinhanh']): ?>
                    <img src="../../fontend/uploads/<?php echo htmlspecialchars($row['hinhanh']); ?>" alt="Hình ảnh đánh giá">
                <?php endif; ?>
                <p style="text-align:justify;"><?php echo nl2br(htmlspecialchars($row['noidung'])); ?></p>
            </div>
            <div class="tinhtrang">
                <span id="status-icon-<?php echo $row['id']; ?>" onclick="toggleStatus(<?php echo $row['id']; ?>, <?php echo $row['trangthai']; ?>)" style="cursor: pointer;">
                    <?php if ($row['trangthai'] == 1): ?>
                        <i class="fas fa-eye"></i> <!-- Biểu tượng mắt mở -->
                    <?php else: ?>
                        <i class="fas fa-eye-slash"></i> <!-- Biểu tượng mắt nhắm -->
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>Không có đánh giá nào.</p>";
}

$conn->close();
?>

<script>
// JavaScript để chuyển hướng khi nhấp vào nút liên hệ
function redirectToAnotherPage(makhachhang) {
    window.location.href = '../quanlydonhang/donhang.php?makhachhang=' + makhachhang;
}

function toggleStatus(id, currentStatus) {
    var newStatus = currentStatus === 1 ? 0 : 1; // Đảo ngược trạng thái
    var iconElement = document.getElementById('status-icon-' + id);
    
    // Cập nhật biểu tượng mắt
    iconElement.innerHTML = newStatus === 1
        ? '<i class="fas fa-eye"></i>' // Mắt mở
        : '<i class="fas fa-eye-slash"></i>'; // Mắt nhắm

    // Gửi yêu cầu AJAX để cập nhật tình trạng trong cơ sở dữ liệu
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true); // update_status.php là tập tin xử lý yêu cầu
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Cập nhật thành công");

            // Gửi tín hiệu reload đến sanpham.php
            var reloadXhr = new XMLHttpRequest();
            reloadXhr.open("POST", "notify_reload.php", true); // notify_reload.php sẽ xử lý reload
            reloadXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            reloadXhr.send(); // Không cần gửi dữ liệu gì, chỉ cần gửi tín hiệu

            location.reload(); // Load lại trang danhgia.php
        }
    };
    xhr.send("id=" + id + "&trangthai=" + newStatus);
}
</script>

    </div>
</div>
 </div>
</div>
</div>
</div>
<style>
    

    .contai-ner {
        padding:0px;
        margin-left: -0px;
        background: white;
        border-top-right-radius: 10px; /* Bo góc phải */
        border-bottom-right-radius: 10px; /* Bo góc phải */

    }
.view {
    background: #fff;
    display: flex;
    flex-direction: column; /* Hiển thị các phần tử theo cột */
    gap: 20px; /* Khoảng cách giữa các phần tử */
    text-align: center;
}


.review-card {
    margin-bottom: 20px; /* Khoảng cách giữa các đánh giá */
    width: 80%;
    background-color: rgba(255, 255, 255, 0.9); /* Màu trắng nhạt với độ mờ */
    backdrop-filter: blur(0.5px); /* Hiệu ứng nhòe */
    border-radius: 10px; /* Bo góc mềm mại */
    padding-top: 0px;
    padding: 20px; /* Khoảng cách bên trong thẻ */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Tạo hiệu ứng đổ bóng nhẹ */
    border: 1px solid green;
    margin: 0 auto;
    height:30%;
}

.review-header {
    display: flex; /* Sắp xếp avatar và thông tin theo chiều ngang */
    align-items: center; /* Canh giữa theo chiều dọc */
    justify-content: left; /* Căn giữa khoảng cách trên dưới và giữa các phần tử */
    padding: 10px 0; /* Khoảng cách trên dưới lớp cha */
    margin-top: -10px;
}

.product-info {
    display: flex; /* Kích hoạt flexbox */
    flex-direction: column; /* Hiển thị các phần tử theo cột */
    justify-content: center; /* Căn giữa các phần tử theo chiều ngang */
    align-items: center; /* Căn giữa */
    margin-left: auto;
    justify-content: center; 
}
    .product-info h2 {
    margin: 0; /* Xóa khoảng cách mặc định của h2 */
    font-size: 14px; /* Kích thước chữ cho tên khách hàng */
}
.review-image {
    object-fit: cover;

    width: 50px; /* Điều chỉnh kích thước avatar */
    height: 50px; /* Điều chỉnh kích thước avatar */
    border-radius: 50%; /* Tạo hình tròn cho avatar */
    margin-right: 15px; /* Khoảng cách giữa avatar và thông tin */
    margin-bottom: 15px;
}

.review-product {
    object-fit: cover;
    width: 40px; /* Điều chỉnh kích thước avatar */
    height: 40px; /* Điều chỉnh kích thước avatar */
    border-radius: 5px;; /* Tạo hình tròn cho avatar */
    margin-bottom: 15px;
}

.review-header h2 {
    margin: 0; /* Xóa khoảng cách mặc định của h2 */
    font-size: 14px; /* Kích thước chữ cho tên khách hàng */
}
.review-info {
    display: flex;
    flex-direction: column; /* Sắp xếp thông tin theo chiều dọc */
    text-align: left;
}

.star-rating {
    color: green; /* Màu sắc cho sao */
    font-size: 18px;
    margin-top: -5px;
}
.review-date {margin-top: -5px;}

.review-content img {
    object-fit: cover;

    width: 100%; /* Chiều rộng hình ảnh bằng chiều rộng của card */
    height: 400px;
    border-radius: 8px; /* Bo tròn các góc hình ảnh */
    margin-bottom: 10px; /* Khoảng cách giữa hình ảnh và nội dung */
    margin-top: -20px;

}

.review-content {
    margin-top: 10px; /* Khoảng cách giữa header và nội dung đánh giá */
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
    background-color: rgba(0, 128, 0, 0.7); /* Màu xanh lá đậm mờ với độ trong suốt */
    border-radius: 10px; /* Góc bo tròn */
}

/* Thanh cuộn khi được hover */
::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 128, 0, 0.9); /* Tăng độ đậm khi hover */
}

</style>

