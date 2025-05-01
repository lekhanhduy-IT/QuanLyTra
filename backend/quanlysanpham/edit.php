<?php
include('../config.php');
if (isset($_POST['update'])) {
    $masanpham = $_POST['masanpham'];
    $tensanpham = $_POST['tensanpham'];
    $tenloai = $_POST['tenloai'];
    $gia = $_POST['gia'];
    $trongluong = $_POST['trongluong'];
    $soluong = $_POST['soluong'];
    $trangthai = isset($_POST['trangthai']) ? 1 : 0; // Lưu trạng thái checkbox
    $ghim = isset($_POST['ghim']) ? 1 : 0; // Lưu trạng thái checkbox

    // Mảng để lưu tên file hình ảnh
    $hinhanhFiles = ['hinhanh1', 'hinhanh2', 'hinhanh3'];
    $hinhanhNames = [];

    // Xử lý hình ảnh và sao chép vào thư mục uploads
    foreach ($hinhanhFiles as $file) {
        if (isset($_FILES[$file]) && $_FILES[$file]['error'] == 0) {
            $hinhanhName = $_FILES[$file]['name'];
            $hinhanhTemp = $_FILES[$file]['tmp_name'];
            $hinhanhPath = '../../fontend/uploads/' . $hinhanhName;

            // Sao chép file hình ảnh vào thư mục uploads
            move_uploaded_file($hinhanhTemp, $hinhanhPath);
            $hinhanhNames[] = $hinhanhName;
        } else {
            // Nếu không có hình ảnh mới, giữ lại tên hình ảnh cũ từ cơ sở dữ liệu
            $query = "SELECT hinhanh1, hinhanh2, hinhanh3 FROM sanpham WHERE masanpham='$masanpham'";
            $result = $conn->query($query);
            $product = $result->fetch_assoc();
            $hinhanhNames[] = $product[$file];
        }
    }

    // Lưu thông tin vào cơ sở dữ liệu
    $update_query = "UPDATE sanpham SET 
                        tensanpham='$tensanpham',
                        tenloai='$tenloai',
                        gia='$gia',
                        trongluong='$trongluong',
                        soluong='$soluong',
                        trangthai='$trangthai',
                        ghim='$ghim',
                        hinhanh1='{$hinhanhNames[0]}',
                        hinhanh2='{$hinhanhNames[1]}',
                        hinhanh3='{$hinhanhNames[2]}'
                    WHERE masanpham='$masanpham'";
    $conn->query($update_query);

    header('Location: sanpham.php');
}

$id = $_GET['id'];
$query = "SELECT masanpham, tensanpham, tenloai, gia, trongluong, soluong, trangthai, ghim, 
                 hinhanh1, hinhanh2, hinhanh3, mota1, mota2, mota3, mota4, motachitiet 
          FROM sanpham 
          WHERE masanpham='$id'";
$result = $conn->query($query);
$product = $result->fetch_assoc();
// Truy vấn lấy danh sách loại sản phẩm
$loaisanpham_query = "SELECT tenloai FROM loaisanpham";
$loaisanpham_result = $conn->query($loaisanpham_query);
?>
<div class="contai-ner">
        <h2>Chỉnh sửa sản phẩm</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="left-column">
                <input type="hidden" name="masanpham" value="<?= $product['masanpham']; ?>">
                
                <div class="form-group">
                    <label for="hinhanh1">Hình ảnh 1</label>
                    <img src="../../fontend/uploads/<?= $product['hinhanh1']; ?>" alt="Hình ảnh 1">
                    <input type="file" id="hinhanh1" name="hinhanh1">
                </div>

                <div class="form-group">
                    <label for="hinhanh2">Hình ảnh 2</label>
                    <img src="../../fontend/uploads/<?= $product['hinhanh2']; ?>" alt="Hình ảnh 2">
                    <input type="file" id="hinhanh2" name="hinhanh2">
                </div>

                <div class="form-group">
                    <label for="hinhanh3">Hình ảnh 3</label>
                    <img src="../../fontend/uploads/<?= $product['hinhanh3']; ?>" alt="Hình ảnh 3">
                    <input type="file" id="hinhanh3" name="hinhanh3">
                </div>
               
            </div>
            <div class="center-column">
                <div class="form-group">
                    <label for="tensanpham">Tên sản phẩm:</label>
                    <input type="text" id="tensanpham" name="tensanpham" value="<?= $product['tensanpham']; ?>">
                </div>
<div class="form-group">
    <label for="tenloai">Loại sản phẩm:</label>
    <select id="tenloai" name="tenloai">
        <?php
        // Duyệt qua các loại sản phẩm và tạo các tùy chọn trong select
        if ($loaisanpham_result->num_rows > 0) {
            while($row = $loaisanpham_result->fetch_assoc()) {
                $selected = ($product['tenloai'] == $row['tenloai']) ? 'selected' : '';
                echo "<option value='{$row['tenloai']}' $selected>{$row['tenloai']}</option>";
            }
        }
        ?>
    </select>
</div>

                <div class="form-group">
                    <label for="gia">Giá:</label>
                    <input type="text" id="gia" name="gia" value="<?= $product['gia']; ?>">
                </div>
                <div class="form-group">
                    <label for="trongluong">Trọng lượng:</label>
                    <input type="text" id="trongluong" name="trongluong" value="<?= $product['trongluong']; ?>">
                </div>
                <div class="form-group">
                    <label for="soluong">Số lượng:</label>
                    <input type="text" id="soluong" name="soluong" value="<?= $product['soluong']; ?>">
                </div>
                <div class="form-group-row" style="width: 60%;">
                    <div>
                        <label style="color:#fff;" for="trangthai">Trạng thái</label>
                        <input type="checkbox" id="trangthai" name="trangthai" <?= $product['trangthai'] ? 'checked' : ''; ?>>
                    </div>
                    <div>
                        <label style="color:#fff;" for="ghim">Ghim</label>
                        <input type="checkbox" id="ghim" name="ghim" <?= $product['ghim'] ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" name="update" class="submit-btn">Cập nhật</button>
                   </div>
            </div>

            <div class="right-column">
                <div class="form-group">
                    <label for="mota1">Mô tả 1:</label>
                    <input type="text" id="mota1" name="mota1" value="<?= $product['mota1']; ?>">
                </div>

                <div class="form-group">
                    <label for="mota2">Mô tả 2:</label>
                    <input type="text" id="mota2" name="mota2" value="<?= $product['mota2']; ?>">
                </div>

                <div class="form-group">
                    <label for="mota3">Mô tả 3:</label>
                    <input type="text" id="mota3" name="mota3" value="<?= $product['mota3']; ?>">
                </div>

                <div class="form-group">
                    <label for="mota4">Mô tả 4:</label>
                    <input type="text" id="mota4" name="mota4" value="<?= $product['mota4']; ?>">
                </div>

                <div class="form-group">
                    <label for="motachitiet">Mô tả chi tiết:</label>
                    <textarea id="motachitiet" name="motachitiet"><?= $product['motachitiet']; ?></textarea>
                </div>
            </div>
        </form>
    </div>
    <link href="../css/style.css" rel="stylesheet">

<style>

        .contai-ner {
            margin: 0 auto;
            margin-top: 0px;
            margin-left: 0px;
            margin-right: 0px;
            background:#fff;
            padding-left: 30px;
            padding-right: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color:#009451;
        }

        form {
            display: flex;
            gap: 20px;
        }

        .left-column, .right-column, .center-column {
            flex: 1;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #009451;
        
        }

        .form-group input,
        .form-group textarea, .form-group select{
            width: 60%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }


        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .form-group input[type="checkbox"] {
            width: 60%;
            color: #fff;        }

        .form-group input[type="file"] {
            color:#fff;
        }

        .form-group-row {
            display: flex;
            gap: 15px;
            padding:20px;
            padding-left:0px;
            justify-content:left;
        }

        .form-group-row input {
            width: calc(50% - 10px);
        }

        .submit-btn {
            display: block;
            padding: 15px;
            background-color: #28a745;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        .form-group img {
            width: 100px;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }

    </style>

