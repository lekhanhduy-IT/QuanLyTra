<?php
// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltra";
$port ='3406';
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý form thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['add_loai'])) {
    $tensanpham = $_POST['tensanpham'];
    $tenloai = $_POST['tenloai'];
    $gia = $_POST['gia'];
    $trongluong = $_POST['trongluong'];
    $soluong = $_POST['soluong'];
    $hinhanh1 = $_FILES['hinhanh1']['name'];
    $hinhanh2 = $_FILES['hinhanh2']['name'];
    $hinhanh3 = $_FILES['hinhanh3']['name'];
    $mota1 = $_POST['mota1'];
    $mota2 = $_POST['mota2'];
    $mota3 = $_POST['mota3'];
    $mota4 = $_POST['mota4'];
    $motachitiet = $_POST['motachitiet'];

    // Upload hình ảnh
    move_uploaded_file($_FILES['hinhanh1']['tmp_name'], "uploads/" . $hinhanh1);
    move_uploaded_file($_FILES['hinhanh2']['tmp_name'], "uploads/" . $hinhanh2);
    move_uploaded_file($_FILES['hinhanh3']['tmp_name'], "uploads/" . $hinhanh3);

    // Thêm sản phẩm vào database
    $sql = "INSERT INTO sanpham (tensanpham, tenloai, gia, trongluong, soluong, hinhanh1, hinhanh2, hinhanh3, mota1, mota2, mota3, mota4, motachitiet) 
            VALUES ('$tensanpham', '$tenloai', '$gia', '$trongluong', '$soluong', '$hinhanh1', '$hinhanh2', '$hinhanh3', '$mota1', '$mota2', '$mota3', '$mota4', '$motachitiet')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm sản phẩm thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}

// Truy vấn bảng loaisanpham để lấy danh sách các loại sản phẩm
$sql_loai = "SELECT * FROM loaisanpham";
$result_loai = $conn->query($sql_loai);

// Xử lý form thêm loại sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_loai'])) {
    $tenloai = $_POST['tenloai'];
    $sql = "INSERT INTO loaisanpham (tenloai) VALUES ('$tenloai')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm loại sản phẩm thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}

// Xóa loại sản phẩm
if (isset($_GET['delete'])) {
    $maloai = $_GET['delete'];
    $sql = "DELETE FROM loaisanpham WHERE maloai = $maloai";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Xóa loại sản phẩm thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}
?>

    <style>

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
a {
    color: white;
    text-decoration: none; /* Bỏ gạch chân cho tất cả các thẻ <a> */
}
 a:hover{ color: yellow;}

        .container {
            display: flex;
            display: flex;
            justify-content: center; /* Dồn nội dung theo chiều ngang */
            align-items: center; /* Dồn nội dung theo chiều dọc */
            justify-content: space-between;
            padding: 20px;
            margin: 0 auto;
            width: 90%;
        }
 
        h3{ color: #009451;}
        form {
            width:100%; /* Cân đối không gian cho mỗi form */
            align-items: center; 
            display:flex;
            padding: 10px;
            background-color: white;
            border: 1px solid  #009451;
            border-radius: 10px;
            margin-bottom: 0px;
        }
        form button {width: 100%; justify-content: center; text-align:center; }

        form input[type="text"], form input[type="number"], form select, form textarea, form input[type="file"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 90%;
            background: white;
        }
        form input[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #009451;
            color: white;
            
        }
        th, td {
            text-align: left;
        }
        th {
            background-color: #009451;
            color: white;
        }





.right {text-align: center;

}
.right form {width:94%;}

.right table {
    width: 100%; /* Đặt chiều rộng của table */
    border-collapse: collapse; /* Gộp viền table */
}

.right th, td {
    padding: 10px; /* Khoảng cách bên trong ô */
    border: 1px solid #ddd; /* Viền cho ô */
    text-align: left; /* Canh trái cho nội dung */
}
.upload-container {
    display: flex;
    flex-direction: column; /* Hiển thị theo chiều dọc */
    gap: 10px; /* Khoảng cách giữa các box */
    margin-top: 20px;
}

.upload-box {
    width: 80px; /* Chiều rộng khung hình vuông */
    height: 80px; /* Chiều cao khung hình vuông */
    border: 1px dashed #009451; /* Đường viền khung */
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    background-image: url('uploads/addimg2.png');
    background-size: contain; /* Giữ nguyên tỷ lệ hình ảnh */
    background-position: center; /* Căn giữa hình ảnh */
    background-repeat: no-repeat; /* Không lặp lại hình ảnh */
}



        .upload-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-button {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            cursor: pointer;
            opacity: 0; /* ẩn input file */
        }

        .left,.form-left-left-left{
            display: flex;
            width:100%;
            gap:2px;
        }

        .center{
    flex-direction: column; /* Sắp xếp theo chiều dọc */
    text-align: center;
        }

      div {
           margin:10px;
        }
    </style>


<div class="container">
    <!-- Form thêm sản phẩm bên trái -->
    <div class="left">
        <form action="" method="POST" enctype="multipart/form-data">
         
            <div class="form-left">

 
            <div class="form-left-left-left">           
            <div class="form-left-left">
            <div>
                <input type="text" name="tensanpham" placeholder="Tên sản phẩm" required>
            </div>
            <div>
                <select name="tenloai" required>
                    <option value="">Chọn loại sản phẩm</option>
                    <?php
                    if ($result_loai->num_rows > 0) {
                        while ($row = $result_loai->fetch_assoc()) {
                            echo "<option value='" . $row['tenloai'] . "'>" . $row['tenloai'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Không có loại sản phẩm nào</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <input type="number" step="0.01" name="gia" placeholder="Giá" required>
            </div>
            <div>
                <input type="number" step="0.01" name="trongluong" placeholder="Trọng lượng" required>
            </div>
            <div>
                <input type="number" name="soluong" placeholder="Số lượng" required>
            </div>
            <di style="margin-left:10px;">
            <input type="submit" value="Thêm sản phẩm">
            </di>
            </div>




            <div class="form-left-right">
            <div>
                <textarea name="mota1" placeholder="Mô tả 1"></textarea>
            </div>
            <div>
                <textarea name="mota2" placeholder="Mô tả 2"></textarea>
            </div>
            <div>
                <textarea name="mota3" placeholder="Mô tả 3"></textarea>
            </div>
            <div>
                <textarea name="mota4" placeholder="Mô tả 4"></textarea>
            </div>
            <div>
                <textarea name="motachitiet" placeholder="Mô tả chi tiết"></textarea>
            </div>
       
            </div>



            </div>
            </div>

<div class="center">

    <div class="upload-container">
    <div class="upload-box">
        <img id="image1" src="" />
        <input type="file" class="upload-button" name="hinhanh1" accept="image/*" onchange="previewImage(event, 'image1')">
    </div>

    <div class="upload-box">
        <img id="image2" src="" />
        <input type="file" class="upload-button" name="hinhanh2" accept="image/*" onchange="previewImage(event, 'image2')">
    </div>

    <div class="upload-box">
        <img id="image3" src="" />
        <input type="file" class="upload-button" name="hinhanh3" accept="image/*" onchange="previewImage(event, 'image3')">
    </div>

</div>
            
            
            
            </div>
        </form>
    </div>

    <!-- Form thêm loại sản phẩm và bảng danh sách bên phải -->
    <div class="right">
        <form action="" method="POST">
            <div>
                <input type="text" name="tenloai" placeholder="Tên loại" required>
            </div>
            <div>
            <input type="submit" name="add_loai" value="Thêm loại">
            </div>
            
        </form>
         <h3>Danh sách loại sản phẩm</h3>
        <table>
            <tr>
                <th>Mã loại</th>
                <th>Tên loại</th>
                <th>Chức năng</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM loaisanpham");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['maloai']}</td>
                            <td>{$row['tenloai']}</td>
                            <td>
                                <a href='edit_loaisanpham.php?maloai={$row['maloai']}'>Sửa</a> |
                                <a href='?delete={$row['maloai']}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Không có loại sản phẩm nào</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
    function previewImage(event, imageId) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById(imageId).src = e.target.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>