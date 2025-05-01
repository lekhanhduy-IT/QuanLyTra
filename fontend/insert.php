<?php
session_start();
$makhachhang = isset($_SESSION['khachhang']) ? $_SESSION['khachhang']['makhachhang'] : null;

// Kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Địa chỉ máy chủ
$username = "root"; // Tên người dùng
$password = ""; // Mật khẩu
$dbname = "qltra"; // Tên cơ sở dữ liệu
$port ='3406';

// Tạo kết nối
$mysqli = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($mysqli->connect_error) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

if (isset($_POST['submitReview'])) {
    $madonhang = isset($_POST['madonhang']) ? intval($_POST['madonhang']) : 0; // Lấy mã đơn hàng từ POST
    $masanpham = isset($_POST['masanpham']) ? intval($_POST['masanpham']) : 0; // Lấy mã sản phẩm từ POST
    $sosao = isset($_POST['sosao']) ? intval($_POST['sosao']) : 0;
    $noidung = isset($_POST['noidung']) ? $mysqli->real_escape_string($_POST['noidung']) : '';
    $hinhanh = '';

    // Xử lý upload hình ảnh nếu có
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; // Thư mục lưu trữ ảnh
        $hinhanh = basename($_FILES['hinhanh']['name']);
        $target_file = $target_dir . $hinhanh; // Đường dẫn đầy đủ của tệp

        // Di chuyển tệp đã tải lên vào thư mục uploads/
        if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target_file)) {
            // Tệp đã được di chuyển thành công
        } else {
            echo "Có lỗi khi tải lên hình ảnh.";
        }
    }

    // Câu lệnh SQL để chèn dữ liệu vào bảng `danhgia`
    $sql_danhgia = "INSERT INTO danhgia (madonhang, makhachhang, masanpham, noidung, sosao, hinhanh, ngaygui)
                    VALUES ($madonhang, $makhachhang, $masanpham, '$noidung', $sosao, '$hinhanh', NOW())";

    if ($mysqli->query($sql_danhgia) === TRUE) {
        header("Location: chitietdonhang.php?madonhang=$madonhang");
        exit();
    } else {
        echo "Có lỗi xảy ra: " . $mysqli->error;
    }
}


$mysqli->close(); // Đóng kết nối
?>
