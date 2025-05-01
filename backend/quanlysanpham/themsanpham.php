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
        echo "Thêm sản phẩm thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
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
        echo "Thêm loại sản phẩm thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Xóa loại sản phẩm
if (isset($_GET['delete'])) {
    $maloai = $_GET['delete'];
    $sql = "DELETE FROM loaisanpham WHERE maloai = $maloai";
    if ($conn->query($sql) === TRUE) {
        echo "Xóa loại sản phẩm thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

    <style>
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            gap: 20px;
        }
        form.left, form.right {
        }
        form {
            width:100%; /* Cân đối không gian cho mỗi form */

            display:flex;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        form button {width: 100%;}
        form div {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
            width:100%;
        }
        form input[type="text"], form input[type="number"], form select, form textarea, form input[type="file"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 90%;
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
        }
        th, td {
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }

        form div {
    width: 100%; /* Mỗi phần chiếm khoảng 50% */
}

form div.left,
form div.right {
    display: flex;
    flex-direction: column;
}

    </style>


<div class="container">
    <!-- Form thêm sản phẩm bên trái -->
    <div class="left">
        <form action="" method="POST" enctype="multipart/form-data">
         
            <div class="left">
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
            <div>
                <textarea name="mota1" placeholder="Mô tả 1"></textarea>
            </div>
            <div>
                <textarea name="mota2" placeholder="Mô tả 2"></textarea>
            </div>

            </div>

            <div class="right">
            <div>
                <input type="file" name="hinhanh1">
            </div>
            <div>
                <input type="file" name="hinhanh2">
            </div>
            <div>
                <input type="file" name="hinhanh3">
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
            <di>
            <input type="submit" value="Thêm sản phẩm">
            </di>
            

            </div>
        </form>
    </div>

    <!-- Form thêm loại sản phẩm và bảng danh sách bên phải -->
    <div class="right">
        <form action="" method="POST">
            <div>
                <input type="text" name="tenloai" placeholder="Tên loại" required>
            </div>
            <input type="submit" name="add_loai" value="Thêm loại">
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

