<?php
    // Kết nối CSDL
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

    // Hàm lấy dữ liệu từ bảng tbl_khachhang
    function getCustomerData($conn) {
        $sql_lietke_dh = "SELECT * FROM khachhang ORDER BY makhachhang ASC";
        $query_lietke_dh = mysqli_query($conn, $sql_lietke_dh);  // Sửa lại ở đây
        return $query_lietke_dh;
    }
?>


<div clasc="contai-ner">

<div class="search-container">
            <input type="text" id="searchInput" onkeyup="searchFunction()" placeholder="Tìm kiếm theo tên khách hàng...">
            <div class="search-icon"><i class="fas fa-search"></i></div>
        </div>
        <section style="background-color:#ADD8E6;">
            <table id="messageContainer">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên khách hàng</th>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th>Điện thoại</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query_lietke_dh = getCustomerData($conn);
                        while ($row = mysqli_fetch_array($query_lietke_dh)) {
                    ?>
                   <tr class="message-item" onclick='redirectToAnotherPage(<?php echo $row["makhachhang"]; ?>)'>
                        <td><img src="../../fontend/<?php echo $row['avatar']; ?>" alt="Avatar" style="width:50px;height:50px; border-radius:50px;object-fit: cover;"></td>
                        <td class="chat-sender"><?php echo $row['tenkhachhang']; ?></td>
                        <td><?php echo $row['makhachhang']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['diachi']; ?></td>
                        <td><?php echo $row['dienthoai']; ?></td>

                    </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>

    <script>
        // JavaScript để chuyển hướng khi nhấp vào nút liên hệ
        function redirectToAnotherPage(makhachhang) {
            window.location.href = '../quanlydonhang/donhang.php?makhachhang=' + makhachhang;
        }
</script>
<script>
        // JavaScript để tìm kiếm tên khách hàng
        function searchFunction() {
    var input, filter, messageItems, messageName, i, txtValue;
    input = document.getElementById('searchInput');
    filter = input.value.toUpperCase();
    messageItems = document.getElementsByClassName('message-item');

    // Thêm thông báo gỡ lỗi
    console.log("Giá trị tìm kiếm:", filter);
    
    var visibleCount = 0; // Đếm số hàng hiển thị

    for (i = 0; i < messageItems.length; i++) {
        messageName = messageItems[i].getElementsByClassName("chat-sender")[0];
        txtValue = messageName.textContent || messageName.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            messageItems[i].style.display = "";
            visibleCount++; // Tăng đếm nếu hàng hiển thị
        } else {
            messageItems[i].style.display = "none";
        }
    }

    console.log("Số hàng hiển thị:", visibleCount); // In số hàng hiển thị
}

    </script>
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
        /* CSS để hiển thị bảng danh sách khách hàng */
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
         
        }
        thead
        {
            background-color:#009451;
            color: #fff;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .chat {
            cursor: pointer;
        }

    
/* Search input container */
.search-container {
    margin: 0 auto;
    padding-bottom:5px;
    text-align:center;
    background: white;

    justify-content: center;
}

/* Search input styles */
.search-container input[type="text"] {
    width: 50%;
    margin: 0 auto;
    padding: 5px 20px;
    border-radius: 25px;
    border: 1px solid green;

    outline: none;
    font-size: 16px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

/* Search input focus styles */
.search-container input[type="text"]:focus {
    border: 1px solid green;
    box-shadow: 0 0 0 1px #4d90fe;
}

/* Search input placeholder styles */
.search-container input[type="text"]::placeholder {
    color: :#009451;
}

/* Search input icon styles */
.search-container .search-icon {
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #70757a;
    cursor: pointer;
}

/* Remove default browser styles */
.search-container input[type="text"]::-webkit-search-cancel-button {
    -webkit-appearance: none;
}

.search-container input[type="search"]::-webkit-search-decoration,
.search-container input[type="search"]::-webkit-search-results-button,
.search-container input[type="search"]::-webkit-search-results-decoration {
    display: none;
}
    </style>