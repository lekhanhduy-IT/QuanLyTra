<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Đơn Hàng</title>
</head>


<section class="donhang-vanchuyen">
<section class="donhang">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltra";
$port ='3406';
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy ID đơn hàng từ URL
if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    
    // Truy vấn để lấy thông tin đơn hàng và khách hàng
    $orderQuery = "SELECT donhang.*, khachhang.makhachhang, khachhang.tenkhachhang, donhang.tongtien ,khachhang.avatar
                   FROM donhang 
                   JOIN khachhang ON donhang.makhachhang = khachhang.makhachhang 
                   WHERE donhang.madonhang = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $order = $result->fetch_assoc();
    } else {
        echo "Đơn hàng không tồn tại.";
        exit;
    }
} else {
    echo "ID đơn hàng không hợp lệ.";
    exit;
}

// Xử lý form khi gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tinhtrang = $_POST['tinhtrang'];

    // Cập nhật thông tin đơn hàng bao gồm makhachhang và tongtien
    $updateQuery = "UPDATE donhang SET makhachhang = ?, tongtien = ?, tinhtrang = ? WHERE madonhang = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("iisi", $order['makhachhang'], $order['tongtien'], $tinhtrang, $orderId);

    if ($updateStmt->execute()) {
        // Sau khi cập nhật donhang, tiếp tục cập nhật bảng tinhtrang
        $insertStatusQuery = "INSERT INTO tinhtrang (madonhang, tinhtrang, ngaytao) VALUES (?, ?, NOW())";
        $statusStmt = $conn->prepare($insertStatusQuery);
        $statusStmt->bind_param("is", $orderId, $tinhtrang);
        $statusStmt->execute();

        echo "<script>window.location.href='sua_donhang.php?id={$order['madonhang']}';</script>";
    } else {
        echo "Lỗi cập nhật: " . $conn->error;
    }
}
?>

<a href="donhang.php" class="button">Quay lại</a>

<h2>Cập nhật Đơn Hàng <?php echo htmlspecialchars($order['madonhang']); ?></h2>

<form action="" method="POST">
    <label><img src="../../fontend/<?php echo htmlspecialchars($order['avatar']); ?>"></label>
    <label for="makhachhang" style="font-size:13px; color:green;">MKHALONGTEA0#<?php echo htmlspecialchars($order['makhachhang']); ?></label>

    <lable><?php echo htmlspecialchars($order['tenkhachhang']); ?></lable>
    <label for="tongtien">Tổng Tiền: <?php echo htmlspecialchars(number_format($order['tongtien'], 0)) . " VND"; ?></label>
                                                   
    <label for="tinhtrang">Tình Trạng:</label>
    <div class="tuychon">
    <?php
    $currentStatus = $order['tinhtrang'];
    $sql = "SELECT matinhtrang, tinhtrang FROM tinhtrang GROUP BY tinhtrang";
    $result = $conn->query($sql);

    echo '<select name="tinhtrang" id="tinhtrang">';
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $selected = ($row["tinhtrang"] == $currentStatus) ? 'selected' : '';
            echo '<option style="width:80%;" value="' . $row["tinhtrang"] . '" ' . $selected . '>' . $row["tinhtrang"] . '</option>';
        }
    } else {
        echo '<option value="">Không có trạng thái nào</option>';
    }
    echo '</select>';
    ?>

    <button type="submit" style="background:#27ae60;margin-right:10px;margin-left:10px;">Cập nhật</button>
   
    </div>
</form>
</section>



<section class="lichsugiaohang">
    <?php
    // Truy vấn bảng tinhtrang
    $sql_tinhtrang = "SELECT matinhtrang, tinhtrang, ngaytao FROM tinhtrang WHERE madonhang = ?";
    $stmt_tinhtrang = $conn->prepare($sql_tinhtrang);
    $stmt_tinhtrang->bind_param("i", $orderId);
    $stmt_tinhtrang->execute();
    $result_tinhtrang = $stmt_tinhtrang->get_result();

    // Hiển thị danh sách tình trạng đơn hàng
    if ($result_tinhtrang->num_rows > 0) {
        echo "<h2>Thông tin vận chuyển</h2>";
        echo "<ul>";
        while ($row_tinhtrang = $result_tinhtrang->fetch_assoc()) {
            if (!empty($row_tinhtrang["tinhtrang"])) {
            echo "<li style='display: flex; justify-content: space-between;'>";
            echo "<div style='flex: 1; text-align: right; margin-right:10px;color:blue;'>".$row_tinhtrang["ngaytao"]."</div>";
            echo "<div style='flex: 1; text-align: left;'>".$row_tinhtrang["tinhtrang"]."</div>";
            
            // Nút xóa
            echo "<form method='post' style='margin-left: 10px;'>";
            echo "<input type='hidden' name='tinhtrang_id' value='".$row_tinhtrang["matinhtrang"]."'>"; // Giả sử có id của tình trạng
            echo "<button class='btn-xoa'style='background:transparent;' type='submit' name='delete_tinhtrang' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\");'>Xóa</button>";
            echo "</form>";

            echo "</li>";
        }
    }
        echo "</ul>";
    } else {
        echo "Không có tình trạng nào cho đơn hàng này.";
    }

    // Xử lý yêu cầu xóa
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_tinhtrang'])) {
        $tinhtrang_id = $_POST['tinhtrang_id'];

        // Xóa trong bảng tinhtrang
        $sql_delete_tinhtrang = "DELETE FROM tinhtrang WHERE matinhtrang= ?";
        $stmt_delete_tinhtrang = $conn->prepare($sql_delete_tinhtrang);
        $stmt_delete_tinhtrang->bind_param("i", $tinhtrang_id);
        $stmt_delete_tinhtrang->execute();

        // Xóa trong bảng donhang nếu cần
        $sql_delete_donhang = "DELETE FROM donhang WHERE madonhang = (SELECT madonhang FROM tinhtrang WHERE madonhang = ?)";
        $stmt_delete_donhang = $conn->prepare($sql_delete_donhang);
        $stmt_delete_donhang->bind_param("i", $tinhtrang_id);
        $stmt_delete_donhang->execute();

        // Thông báo xóa thành công
        echo "Đã xóa tình trạng đơn hàng thành công.";
        // Có thể làm mới trang hoặc thực hiện truy vấn lại để cập nhật thông tin
    }
    ?>
</section>

</section>




<section class="chitietdonhang">
    <?php
    // Truy vấn bảng chitietdonhang
    $sql_chitiet = "SELECT * FROM chitietdonhang WHERE madonhang = ?";
    $stmt_chitiet = $conn->prepare($sql_chitiet);
    $stmt_chitiet->bind_param("i", $orderId); // "i" là kiểu integer
    $stmt_chitiet->execute();
    $result_chitiet = $stmt_chitiet->get_result();

    // Kiểm tra và hiển thị chi tiết đơn hàng
    if ($result_chitiet->num_rows > 0) {
        echo "<h2>Chi tiết đơn hàng</h2>";
        echo"<br>";
        ?>
        <div class="product">
        <?php
        while ($row_chitiet = $result_chitiet->fetch_assoc()) {
         
            echo "<div class='product-detail'>";
            echo "<img src='../../fontend/uploads/" . $row_chitiet["hinhanh1"] . "' alt='" . $row_chitiet["tensanpham"] . "' class='product-image'>";
            echo "<div class='product-info'>";
            echo "Mã chi tiết: " . $row_chitiet["machitietdonhang"] . "<br>";
            echo "Mã đơn hàng: " . $row_chitiet["madonhang"] . "<br>";
            echo "Mã sản phẩm: " . $row_chitiet["masanpham"] . "<br>";
            echo "Tên sản phẩm: " . $row_chitiet["tensanpham"] . "<br>";
            echo "Số lượng: " . $row_chitiet["soluong"] . "<br>";
            echo "Tổng tiền: " . number_format($row_chitiet["tongtien"], 0) . " VNĐ<br>";
            echo "</div>"; // Đóng div .product-info
            echo "</div>"; // Đóng div .product-detail

        }
        ?>
        </div>
        <?php
    } else {
        echo "Không tìm thấy chi tiết đơn hàng cho mã đơn hàng: " . htmlspecialchars($orderId) . "<br>";
    }
    $stmt_chitiet->close();
    ?>
</section>

    <section class="tinhtrang">  
    <table class="themtinhtrang">
        <thead>
            <tr class="danhsach">
                <td colspan="6">Danh Sách Tình Trạng</td>
            </tr>
            <tr>
                <th><input id="newStatus" placeholder="Nhập tình trạng mới..." class="tinhtrangmoi"></th>
                <th><button onclick="addStatus()">Thêm</button></th>
            </tr>
        </thead>
        <tbody>
        <?php
if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $sql = "SELECT matinhtrang, tinhtrang FROM tinhtrang GROUP BY tinhtrang ASC";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        // Kiểm tra nếu tinhtrang không rỗng
        if (!empty($row['tinhtrang'])) {
            echo "<tr>
                <td>
                    <input type='text' value='{$row['tinhtrang']}' 
                           data-id='{$row['matinhtrang']}' class='edit-tinhtrang'>
                </td>
                <td class='action-buttons'>
                    <button class='updateAllBtn'>Lưu</button>
                    <button class='btn-xoa' data-id='{$row['matinhtrang']}'>Xóa</button>
                </td>
            </tr>";
        }
    }
}
?>

        </tbody>
    </table>
</section>

<script>
    document.querySelector('.updateAllBtn').addEventListener('click', function() {
        let updates = [];
        document.querySelectorAll('.edit-tinhtrang').forEach(input => {
            updates.push({
                matinhtrang: input.getAttribute('data-id'),
                tinhtrang: input.value
            });
        });

        fetch('suatinhtrang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ updates: updates })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload(); // Tải lại trang để cập nhật danh sách
            // Bạn có thể thêm mã để làm mới bảng hoặc xử lý sau khi cập nhật ở đây
        })
        .catch(error => console.error('Error:', error));
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    $('.delete-btn').on('click', function() {
        var id = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xóa?')) {
            $.ajax({
                url: 'xoatinhtrang.php',
                method: 'POST',
                data: { matinhtrang: id },
                success: function(response) {
                    alert('Xóa thành công!');
                    location.reload(); // Tải lại trang để cập nhật danh sách
                },
                error: function() {
                    alert('Có lỗi xảy ra!');
                }
            });
        }
    });
});
</script>

<script>
function addStatus() {
    const status = document.getElementById('newStatus').value;
    if (status) {
        // Gửi yêu cầu AJAX đến PHP để thêm tình trạng
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "themtinhtrang.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert('Thêm tình trạng thành công!');
                location.reload(); // Làm mới trang để cập nhật danh sách
            }
        };
        xhr.send("tinhtrang=" + encodeURIComponent(status));
    } else {
        alert("Vui lòng nhập tình trạng!");
    }
}
</script>

    </div>


<?php
$conn->close();
?>
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

section {background: #fff; padding:20px; border-radius:10px;}
section img { border-radius:10px;}
/* Căn chỉnh tiêu đề */
h2 {
    text-align: center;
    color: green;
    margin-bottom: 20px;
}

/* Form cập nhật đơn hàng */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form label {
    font-weight: bold;
    color: #2c3e50;
}

form input, form select, form button {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

form input:focus, form select:focus {
    outline: none;
    border-color: #27ae60;
}

form button {
    background-color: #27ae60;
    color: white;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
}

.tuychon {
    display: flex;
    justify-content: center; /* Căn về hai đầu trái và phải */
    align-items: center; /* Căn giữa theo chiều dọc */
    text-align: center;
    padding: 0px;
    top: 0px;
    margin-bottom: 20px; /* Thêm khoảng cách giữa các lớp */
    box-sizing: border-box; /* Đảm bảo padding và border không làm thay đổi kích thước tổng thể */
    background: #fff;
}

button{    font-family: "Times New Roman", sans-serif; font-weight:14px;}
.button {
    background-color: #27ae60;
    color: white;
    cursor: pointer;
    border: none;
    padding:8px;
    text-decoration: none;
    position: absolute;
    left:80px;
    top: 60px;
    border-radius:5px;
    text-align: center;
    transition: background-color 0.3s ease;
}


.button:hover {background:green;}
.btn-xoa {color:red; background:transparent;}
form button:hover {
    background-color: #219150;
}

/* Quay lại button */
form button:nth-child(2) {
    background-color: #e74c3c;
}

form button:nth-child(2):hover {
    background-color: #c0392b;
}

/* Hình ảnh avatar */
form img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}

/* Thông tin vận chuyển */
.lichsugiaohang ul {
    list-style: none;
    padding: 0;
    margin-top:30px;
    width:100%;
}

.lichsugiaohang li {
    background-color: #ecf0f1;
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
    width:100%;
    color: #34495e;
}
.donhang{ width:800px; text-align:center; margin-right:30px; border: 1px solid green; }
.donhang-vanchuyen {display: flex; margin: 0 auto; }

/* Chi tiết đơn hàng */
.chitietdonhang {
}
.product {
    text-align:center;
    align-items: center;
    width: 100%;
    gap: 10px;
    margin-top: 0px;
    display: flex; /* Sử dụng flexbox để sắp xếp ảnh và thông tin */
    flex-wrap: wrap; /* Cho phép các phần tử con tự động xuống dòng */
}

.product-detail {
    align-items: center;

    margin-top:10px;
    display: flex; /* Sử dụng flexbox để sắp xếp ảnh và thông tin */
    border: 1px solid #ccc; /* Đường viền cho từng chi tiết sản phẩm */
    padding: 10px; /* Khoảng cách bên trong khung */
    padding-bottom:-10px;
    margin-bottom: 0px; /* Khoảng cách giữa các chi tiết sản phẩm */
    border-radius: 5px; /* Bo góc cho khung viền */
    background-color: #27ae60;
    color: white;
    width:30%;
}




.product-image {

    width: 100px; /* Chiều rộng cố định cho ảnh */
    height: 100px; /* Tự động điều chỉnh chiều cao theo tỷ lệ */
    margin-right: 15px; /* Khoảng cách giữa ảnh và thông tin */
}

.product-info {
     margin-top: 10px;
    flex: 1; /* Chiếm không gian còn lại trong khung */

}

.chitietdonhang {
    margin-top: 20px;
    padding-top:20px;

}

.chitietdonhang h2 {
text-align:center;
margin: 0 auto;
    color: #27ae60;
}

.chitietdonhang img {
    width: 100px;
    height: auto;
    margin-top: 10px;
}

.chitietdonhang div {
    margin-bottom: 20px;
    border-bottom: 1px solid #bdc3c7;
    padding-bottom: 10px;
}

.chitietdonhang div:last-child {
    border-bottom: none;
}

/* Danh sách tình trạng đơn hàng */
.tinhtrang {
    margin-top: 30px;
}

.tinhtrang table {
    width: 100%;
    border-collapse: collapse;
}

.tinhtrang th, .tinhtrang td {
    padding: 10px;
    border: 1px solid #bdc3c7;
    text-align: center;
}
.tinhtrang .tinhtrangmoi {
    width: 90%;
    padding: 10px;
    border-radius: 25px;
    border: 1px solid green;
}
.tinhtrang input[type="text"] {
    width: 90%;
    padding: 10px;
    border-radius: 25px;
    border: 1px solid #bdc3c7;
}

.tinhtrang input[type="text"]:focus {
    border-color: green; 
    border: none;
    /* Thay đổi màu viền khi trường được chọn (nếu cần) */
}
.tinhtrang button {
    padding: 10px 20px;
    color:  #3498db;
    border: none;
    background:transparent;
    border-radius: 5px;
    cursor: pointer;
}
.tinhtrang .btn-xoa{
    padding: 10px 20px;
    color:  red;
    border: none;
    background:transparent;
    border-radius: 5px;
    cursor: pointer;
}


.tinhtrang button:hover {
    background-color: #3498db;
    color: white;
}


.tinhtrang .delete-btn {
    background-color: #e74c3c;
}

.tinhtrang .delete-btn:hover {
    background-color: #c0392b;
}

/* Responsive */
@media (max-width: 768px) {
    .wapper {
        width: 100%;
        padding: 15px;
    }

    form, .lichsugiaohang, .chitietdonhang, .tinhtrang {
        padding: 10px;
    }

    .chitietdonhang img {
        width: 80px;
    }
}
.danhsach {
    background:  #009451; 
    color: white;
}

</style>