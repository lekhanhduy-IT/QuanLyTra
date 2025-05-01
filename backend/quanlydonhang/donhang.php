
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng</title>
    <link href="css/style.css" rel="stylesheet">
    <style>
        /* Thêm CSS cho giao diện */
        .contai-ner {
            display: flex;
            justify-content: space-between;
            padding: 0px;
            background: white;

        }
        .revenue-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 0 auto;
            padding-top:-20px;
            width: 30%;
        }

        .revenue-box h3{ margin-top: -5px}
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table th, .orders-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        .action-buttons {
            display: flex;
            text-align:center;
            gap: 5px;
        }
        .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            margin: 0 auto;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        #revenueChart {
            width: 100%;
            height: 400px;
        }
        .danhsach {
            background-color: #009451; /* Màu nền xanh */
            padding: 5px;
            width:100%;
            text-align: center;
            color:white;
            font-size: 18px;

        }
        .danhsach td{
            text-align: center;

        }
        .danhsach td img {object-fit: cover;}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="../css/style.css" rel="stylesheet">

</head>
    <div class="contai-ner">
    <?php
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Tên người dùng của bạn
$password = ""; // Mật khẩu của bạn
$dbname = "qltra"; // Tên cơ sở dữ liệu của bạn
$port ='3406';
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý xóa đơn hàng, chi tiết đơn hàng và tình trạng đơn hàng
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
    $conn->begin_transaction();
    
    try {
        // Xóa dữ liệu trong bảng chitietdonhang trước
        $stmt = $conn->prepare("DELETE FROM chitietdonhang WHERE madonhang = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Xóa dữ liệu trong bảng tinhtrang trước
        $stmt = $conn->prepare("DELETE FROM tinhtrang WHERE madonhang = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Sau đó xóa đơn hàng trong bảng donhang
        $stmt = $conn->prepare("DELETE FROM donhang WHERE madonhang = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Commit nếu tất cả đều thành công
        $conn->commit();
        
        echo "<script>alert('Đơn hàng, chi tiết đơn hàng và tình trạng đơn hàng đã được xóa thành công.');</script>";
        header("Location: " . $_SERVER['PHP_SELF']); // Tải lại trang sau khi xóa
        exit;
    } catch (Exception $e) {
        // Rollback nếu có lỗi xảy ra
        $conn->rollback();
        echo "<script>alert('Có lỗi xảy ra khi xóa đơn hàng.');</script>";
    }
}


// Lấy makhachhang từ GET
$makhachhang = isset($_GET['makhachhang']) ? intval($_GET['makhachhang']) : 0;

// Truy vấn tên khách hàng nếu có makhachhang
$tenkhachhang = "";
if ($makhachhang > 0) {
    // Nếu có makhachhang, lọc theo makhachhang
    $customerQuery = "SELECT * FROM khachhang WHERE makhachhang = ?";
    $stmt = $conn->prepare($customerQuery);
    $stmt->bind_param("i", $makhachhang);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $tenkhachhang = $row['tenkhachhang'];
    }
    $stmt->close();
}

// Truy vấn đơn hàng
// Truy vấn đơn hàng kết hợp với bảng khachhang
if ($makhachhang > 0) {
    $ordersQuery = "SELECT d.*, k.tenkhachhang, k.avatar 
                    FROM donhang d 
                    JOIN khachhang k ON d.makhachhang = k.makhachhang 
                    WHERE d.makhachhang = ? 
                    ORDER BY d.ngaytao DESC";
    $stmt = $conn->prepare($ordersQuery);
    $stmt->bind_param("i", $makhachhang);
} else {
    $ordersQuery = "SELECT d.*, k.tenkhachhang, k.avatar 
                    FROM donhang d 
                    JOIN khachhang k ON d.makhachhang = k.makhachhang 
                    ORDER BY d.ngaytao DESC";
    $stmt = $conn->prepare($ordersQuery);
}

$stmt->execute();
$ordersResult = $stmt->get_result();
?>


<?php
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Tên người dùng của bạn
$password = ""; // Mật khẩu của bạn
$dbname = "qltra"; // Tên cơ sở dữ liệu của bạn
$port ='3406';
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentYear = date("Y");
$currentMonth = date("m");
$lastMonth = date("m", strtotime("last month"));

// Doanh thu tháng hiện tại
$currentMonthRevenueQuery = "SELECT SUM(tongtien) AS total_current_month FROM donhang 
                               WHERE YEAR(ngaytao) = $currentYear AND MONTH(ngaytao) = $currentMonth";
$currentMonthRevenueResult = $conn->query($currentMonthRevenueQuery);
$currentMonthRevenue = $currentMonthRevenueResult->fetch_assoc()['total_current_month'];

// Doanh thu tháng trước
$lastMonthRevenueQuery = "SELECT SUM(tongtien) AS total_last_month FROM donhang 
                           WHERE YEAR(ngaytao) = $currentYear AND MONTH(ngaytao) = $lastMonth";
$lastMonthRevenueResult = $conn->query($lastMonthRevenueQuery);
$lastMonthRevenue = $lastMonthRevenueResult->fetch_assoc()['total_last_month'] ?? 0; // Gán 0 nếu không có dữ liệu

// Tính độ chênh lệch
$difference = $currentMonthRevenue - $lastMonthRevenue;
?>


        <div class="revenue-box">
            <h3>Doanh Thu Tháng Hiện Tại</h3>
            <p><?php echo number_format($currentMonthRevenue, 2); ?> VND</p>
            <h3>Doanh Thu Tháng Trước</h3>
            <p><?php echo number_format($lastMonthRevenue, 2); ?> VND</p>
            <h3>Độ Chênh Lệch</h3>
            <p><?php echo number_format($difference, 2); ?> VND</p>
        </div>

        
        <div>
            <table class="orders-table">
   
            <thead>
    <tr class="danhsach">
        <td colspan="8">
        <?php if ($tenkhachhang): ?>
            Danh sách đơn hàng của <?php echo $tenkhachhang; ?>
        <?php else: ?>
            Danh sách đơn hàng
        <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Mã ĐH</th>
        <th>Mã Khách Hàng</th>
        <th>Tên Khách Hàng</th> <!-- Cột mới cho tên khách hàng -->
        <th>Avatar</th> <!-- Cột mới cho avatar -->
        <th>Tổng Tiền</th>
        <th>Ngày Tạo</th>
        <th>Tình Trạng</th>
        <th>Hành Động</th>
    </tr>
</thead>
<tbody>
    <?php
    while ($order = $ordersResult->fetch_assoc()) {
        echo "<tr>
                <td>{$order['madonhang']}</td>
                <td>{$order['makhachhang']}</td>
                <td>{$order['tenkhachhang']}</td> <!-- Hiển thị tên khách hàng -->
                <td><img src='../../fontend/{$order['avatar']}' alt='Avatar' style='width:50px; height:50px;object-fit: cover;'/></td> <!-- Hiển thị avatar -->
                <td>" . number_format($order['tongtien'], 0) . " VND</td>
                <td>" . date("d/m/Y H:i:s", strtotime($order['ngaytao'])) . "</td>
                <td>{$order['tinhtrang']}</td>
                <td class='action-buttons'>
                    <a href='sua_donhang.php?id={$order['madonhang']}' class='btn'>Sửa</a>
                </td>
              </tr>";
    }
    ?>
</tbody>

            </table>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <canvas id="revenueChart"></canvas>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Doanh Thu (VND)',
                    data: <?php echo json_encode($revenues); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</html>


<?php
$conn->close();
?>
