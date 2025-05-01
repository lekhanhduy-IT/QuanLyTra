<?php
session_start();
$host = 'localhost';
$db = 'qltra';
$user = 'root'; // Thay đổi theo tên người dùng DB của bạn
$pass = ''; // Thay đổi theo mật khẩu DB của bạn
$port ='3406';

$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_FILES['new_avatar']) && isset($_POST['makhachhang'])) {
    $makhachhang = $_POST['makhachhang'];
    $file = $_FILES['new_avatar'];

    // Kiểm tra xem file có được tải lên mà không có lỗi không
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Định nghĩa thư mục tải lên và đường dẫn file
        $uploadDir = 'uploads/';
        $fileName = basename($file['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName; // Tên file duy nhất để tránh ghi đè

        // Di chuyển file đã tải lên đến thư mục mong muốn
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Cập nhật cơ sở dữ liệu với đường dẫn avatar mới
            $query = "UPDATE khachhang SET avatar = ? WHERE makhachhang = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("si", $filePath, $makhachhang);

            if ($stmt->execute()) {
                $_SESSION['khachhang']['avatar'] = $filePath; // Cập nhật avatar trong phiên
                echo json_encode(['status' => 'success', 'avatar' => $filePath]); // Trả về trạng thái và đường dẫn avatar mới
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => "Error updating avatar: " . $stmt->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => "Error moving uploaded file."]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => "File upload error: " . $file['error']]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => "Invalid request."]);
}
?>

<a type="button" class="back" onclick="triggerNotification()">🔔 Thông Báo</a>
<script>
    function triggerNotification() {
        // Đặt một giá trị vào localStorage để thông báo cho tab khác
        localStorage.setItem('reloadDashboard', 'true');
        localStorage.setItem('newSalaryNotification', 'true');
        
        // Lưu thời gian hiện tại
        localStorage.setItem('notificationTime', new Date().toLocaleString());
    }

    // Giả sử đây là hàm AJAX để cập nhật avatar
    function updateAvatar() {
        const formData = new FormData();
        formData.append('makhachhang', 'your_makhachhang_value'); // Thay bằng giá trị thực
        formData.append('new_avatar', document.querySelector('input[type="file"]').files[0]);

        fetch('path_to_your_php_file.php', { // Thay bằng đường dẫn thực đến file PHP
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Gọi hàm triggerNotification() sau khi cập nhật thành công
                triggerNotification();
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>


<script>
    function updateAvatar() {
    const formData = new FormData();
    const avatarFile = document.querySelector('input[type="file"]').files[0];
    formData.append('new_avatar', avatarFile);
    formData.append('makhachhang', makhachhang); // Đảm bảo bạn đã có biến makhachhang

    fetch('header.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Lưu trạng thái vào local storage
            localStorage.setItem('avatarUpdated', 'true');
            // Cập nhật avatar trên giao diện nếu cần
            console.log('Avatar updated:', data.avatar);
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

</script>