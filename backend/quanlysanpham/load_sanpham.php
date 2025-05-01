<?php
// Kết nối cơ sở dữ liệu và lấy dữ liệu sản phẩm
include('../config.php');  // Kết nối CSDL

// Xác định trang hiện tại và các tham số phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;  // Số sản phẩm mỗi trang
$offset = ($page - 1) * $limit;

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = $search ? " WHERE tensanpham LIKE '%" . $conn->real_escape_string($search) . "%' 
                                OR tenloai LIKE '%" . $conn->real_escape_string($search) . "%' 
                                OR gia LIKE '%" . $conn->real_escape_string($search) . "%' 
                                OR trongluong LIKE '%" . $conn->real_escape_string($search) . "%' 
                                OR soluong LIKE '%" . $conn->real_escape_string($search) . "%' 
                                OR mota1 LIKE '%" . $conn->real_escape_string($search) . "%'" : '';

// Lấy tổng số sản phẩm để tính toán số trang
$total_query = "SELECT COUNT(*) FROM sanpham" . $search_condition;
$total_result = $conn->query($total_query);
$total_sanpham = $total_result->fetch_row()[0];
$total_pages = ceil($total_sanpham / $limit);

// Lấy dữ liệu sản phẩm dựa trên trang hiện tại và điều kiện tìm kiếm
$query = "SELECT * FROM sanpham" . $search_condition . " LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<table>
    <tr>
        <th>STT</th>
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
        <td><?= htmlspecialchars($row['tensanpham']); ?></td>
        <td><?= htmlspecialchars($row['tenloai']); ?></td>
        <td><?= htmlspecialchars($row['gia']); ?></td>
        <td><?= htmlspecialchars($row['trongluong']); ?></td>
        <td><?= htmlspecialchars($row['soluong']); ?></td>
        <td><img src="../../fontend/uploads/<?= htmlspecialchars($row['hinhanh1']); ?>" width="50"></td>
        <td><?= htmlspecialchars(strlen($row['mota1']) > 10 ? substr($row['mota1'], 0, 10) . '...' : $row['mota1']); ?></td>
        <td><?= $row['trangthai'] ? 'Hiện' : 'Ẩn'; ?></td>
        <td><?= $row['ghim'] ? 'Có' : 'Không'; ?></td>
        <td>
            <a href="edit.php?id=<?= $row['masanpham']; ?>">Sửa</a> 
            <a href="delete.php?id=<?= $row['masanpham']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a id="page<?= $i; ?>" class="page <?= ($i == $page) ? 'active' : ''; ?>" href="javascript:void(0);" data-page="<?= $i; ?>" onclick="loadContent('load_sanpham.php?page=<?= $i; ?>&search=<?= urlencode($search); ?>', <?= $i; ?>);"><?= $i; ?></a>
    <?php endfor; ?>
</div>

<style>
    .pagination {
        display: flex;
        text-align: center;
        margin: 10px auto;

    }
    .pagination a{
        text-align: center;
        margin: 0 auto;
        
    }
</style>