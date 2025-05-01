<?php
$servername = "localhost";
$username = "root"; // Tên người dùng cơ sở dữ liệu
$password = ""; // Mật khẩu cơ sở dữ liệu
$dbname = "qltra"; // Tên cơ sở dữ liệu
$port ='3406';
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Số sản phẩm trên mỗi trang
$limit = 10;

// Xác định trang hiện tại
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = $search ? " WHERE tensanpham LIKE '%$search%'" : '';

// Lấy tổng số sản phẩm để tính toán số trang
$total_query = "SELECT COUNT(*) FROM sanpham" . $search_condition;
$total_result = $conn->query($total_query);
$total_sanpham = $total_result->fetch_row()[0];
$total_pages = ceil($total_sanpham / $limit);

// Lấy danh sách sản phẩm với phân trang và điều kiện tìm kiếm
$query = "SELECT * FROM sanpham" . $search_condition . " LIMIT $start, $limit";
$result = $conn->query($query);
?>
<div class="contai-ner">
    <div class="tuychon">
        <h2>Danh sách sản phẩm</h2>
<div>
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a id="page<?= $i; ?>" class="page <?= ($i == $page) ? 'active' : ''; ?>" href="javascript:void(0);" data-page="sanpham.php?page=<?= $i; ?>" onclick="loadContent('load_sanpham.php?page=<?= $i; ?>&search=<?= urlencode($search); ?>', <?= $i; ?>);"><?= $i; ?></a>
    <?php endfor; ?>
</div>


<script>
    function searchProducts() {
        var searchValue = document.getElementById('searchInput').value;
        var url = 'load_sanpham.php?search=' + encodeURIComponent(searchValue);

        // Gọi hàm loadContent để cập nhật bảng
        loadContent(url, 1); // Giả sử trang 1 khi tìm kiếm
    }

    function loadContent(url, clickedPage) {
        // Tạo yêu cầu AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);

        // Xử lý kết quả trả về từ máy chủ
        xhr.onload = function () {
            if (this.status == 200) {
                // Chèn nội dung mới vào bảng (bạn có thể thay đổi phần tử này theo layout của bạn)
                document.querySelector('table').innerHTML = this.responseText;

                // Cập nhật màu sắc của các nút
                var pages = document.querySelectorAll('.page');
                pages.forEach(function(page) {
                    page.classList.remove('active-page');  // Xóa class active-page cho tất cả nút
                });

                // Thêm class active-page cho nút đã được click
                document.getElementById('page' + clickedPage).classList.add('active-page');
            }
        };

        // Gửi yêu cầu
        xhr.send();
    }
</script>

        <div class="add"  onclick="window.location.href='../trangchu.php';" ><img src="../../fontend/uploads/add.png"></div>

        <form method="GET" action="">
    <input type="text" id="searchInput" name="search" placeholder="Nhập tên sản phẩm..." value="<?= htmlspecialchars($search); ?>" oninput="searchProducts()">
       </form>

    </div>

    <!-- Di chuyển bảng dưới đây -->
    <table>
        <tr>
            <th>Mã</th>
            <th>Tên</th>
            <th>Loại</th>
            <th>Giá</th>
            <th>Trọng lượng</th>
            <th>Số lượng</th>
            <th>Ảnh</th>
            <th>Mô tả</th>
            <th>Trạng thái</th>
            <th>Ghim</th>
            <th>Chức năng</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['masanpham']; ?></td>
            <td><?= $row['tensanpham']; ?></td>
            <td><?= $row['tenloai']; ?></td>
            <td><?= $row['gia']; ?></td>
            <td><?= $row['trongluong']; ?></td>
            <td><?= $row['soluong']; ?></td>
            <td><img src="../../fontend/uploads/<?= $row['hinhanh1']; ?>" width="50"></td>
            <td>
           <?= strlen($row['mota1']) > 10 ? htmlspecialchars(substr($row['mota1'], 0, 10)) . '...' : htmlspecialchars($row['mota1']); ?>
           </td>

            <td><?= $row['trangthai'] ? 'Hiện' : 'Ẩn'; ?></td>
            <td><?= $row['ghim'] ? 'Có' : 'Không'; ?></td>
            <td>
                <a href="edit.php?id=<?= $row['masanpham']; ?>">Sửa</a> 
                <a href="delete.php?id=<?= $row['masanpham']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<link href="../css/style.css" rel="stylesheet">

<style>
    table { width:100%;border-collapse: collapse; margin-top:0px; text-align: center; }
    th, td { padding: 8px; text-align: center; border: 1px solid #ddd; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    .tuychon {
    display: flex;
    justify-content: space-between; /* Căn về hai đầu trái và phải */
    align-items: center; /* Căn giữa theo chiều dọc */
    text-align: justify;
    padding: 0px;
    margin-bottom: 0px; /* Thêm khoảng cách giữa các lớp */
    box-sizing: border-box; /* Đảm bảo padding và border không làm thay đổi kích thước tổng thể */
    background: #fff;
    margin-top: -10px;
    z-index: 2;
}
.add img {width:30px;}
.add img:hover{ cursor: pointer;} 
.tuychon h2 {
    margin: 0; /* Xóa khoảng cách mặc định của thẻ h2 */
}

.tuychon div {
    display: flex; /* Sử dụng flexbox cho phần phân trang */
    gap: 10px; /* Khoảng cách giữa các liên kết phân trang */
}

.tuychon form {
    display: flex; /* Sử dụng flexbox cho form tìm kiếm */
    align-items: center; /* Căn giữa theo chiều dọc */
    gap: 0px; /* Khoảng cách giữa ô input và nút */
    margin-top: 15px;
    margin-right: 10px;
}

.tuychon input[type="text"] {
    padding: 5px; /* Khoảng cách bên trong ô nhập */
    border: 1px solid green; /* Viền màu xanh lá */
    border-radius: 50px; /* Bo góc bên trái của ô nhập */
    outline: none; /* Bỏ đường viền mặc định khi focus */
    width: 250px; /* Độ rộng của ô nhập */
}
a{ text-decoration: none;
}
.tuychon button {
    background-color: green; /* Nền màu xanh lá */
    color: white; /* Màu chữ trắng */
    padding: 5px 20px; /* Khoảng cách bên trong nút */
    border: 1px solid green; /* Viền nút cùng màu với ô nhập */
    border-radius: 0 20px 20px 0; /* Bo góc bên phải của nút */
    cursor: pointer; /* Thay đổi con trỏ khi hover */
    outline: none; /* Bỏ đường viền mặc định khi focus */
    text-decoration: none;
}

.tuychon button:hover {
    background-color: darkgreen; /* Màu nền tối hơn khi hover */
}


    .contai-ner {
        padding:0px;
        margin-left: -0px;
        background: white;
        border-top-right-radius: 10px; /* Bo góc phải */
        border-bottom-right-radius: 10px; /* Bo góc phải */

    }
/* CSS chung cho nút phân trang */
.page {
    display: flex; /* Sử dụng flexbox để căn chỉnh */
    justify-content: center; /* Căn giữa theo chiều ngang */
    align-items: center; /* Căn giữa theo chiều dọc */
    width: 20px; /* Chiều rộng của nút */
    height: 20px; /* Chiều cao của nút */
    background-color: green; /* Màu nền xanh lá cây */
    color: white; /* Màu chữ trắng */
    border: none; /* Không có viền */
    border-radius: 50%; /* Tạo nút tròn */
    text-align: center; /* Căn giữa chữ */
    text-decoration: none; /* Không có gạch chân */
    font-size: 14px; /* Kích thước chữ */
    cursor: pointer; /* Con trỏ chuột trở thành hình tay khi di chuột lên nút */
    transition: background-color 0.3s; /* Hiệu ứng chuyển màu nền khi hover */
}


/* Màu sắc khi hover nút */
.page:hover {
        background-color: lightgreen;
    }

    .active-page {
        background-color: orange !important;
        color: black;
    }

/* Màu sắc cho nút của trang hiện tại */




</style>


