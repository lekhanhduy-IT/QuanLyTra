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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn để kiểm tra tài khoản admin
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // So sánh mật khẩu đã mã hóa bằng MD5
        if (md5($password) === $row['mat_khau']) {
            // Kiểm tra trạng thái
            if ($row['trang_thai'] == 0) {
                // Cập nhật trạng thái thành 1
                $stmt = $conn->prepare("UPDATE admin SET trang_thai = 1 WHERE id = ?");
                $stmt->bind_param("i", $row['id']);
                $stmt->execute();
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                header("Location:index.php?message=Đăng nhập thành công");
                exit();
            } else {
                header("Location:index.php?message=Đăng nhập thành công");
                exit();
            }
        } else {
            echo "Mật khẩu không chính xác.";
        }
    } else {
        echo "Tài khoản không tồn tại.";
    }
}
?>
