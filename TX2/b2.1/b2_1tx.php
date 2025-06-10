<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CongTyDB";

// Tạo kết nối đến MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tạo cơ sở dữ liệu nếu chưa tồn tại
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname COLLATE utf8mb4_general_ci";
if ($conn->query($sql_create_db) === TRUE) {
    // echo "Cơ sở dữ liệu \"$dbname\" đã được tạo hoặc đã tồn tại.<br>";
} else {
    // echo "Lỗi khi tạo cơ sở dữ liệu: " . $conn->error . "<br>";
}

// Chọn cơ sở dữ liệu để làm việc
$conn->select_db($dbname);

// Tạo bảng SanPham nếu chưa tồn tại
$sql_create_table = "CREATE TABLE IF NOT EXISTS SanPham (
    MaSP INT PRIMARY KEY,
    TenSP VARCHAR(25) NOT NULL,
    SoLuong INT NOT NULL,
    GiaTien INT NOT NULL
)";

if ($conn->query($sql_create_table) === TRUE) {
    // echo "Bảng \"SanPham\" đã được tạo hoặc đã tồn tại.<br>";
} else {
    // echo "Lỗi khi tạo bảng: " . $conn->error . "<br>";
}

// Chèn 3 bản ghi sản phẩm mẫu (chỉ chèn nếu bảng rỗng để tránh trùng lặp)
$check_empty = "SELECT COUNT(*) FROM SanPham";
$result = $conn->query($check_empty);
$row = $result->fetch_row();

if ($row[0] == 0) {
    $sql_insert_data = "INSERT INTO SanPham (MaSP, TenSP, SoLuong, GiaTien) VALUES
    (1, 'Laptop Dell', 15, 12000000),
    (2, 'Chuột Logitech', 8, 350000),
    (3, 'Bàn phím cơ', 12, 1500000)";

    if ($conn->query($sql_insert_data) === TRUE) {
        // echo "Đã chèn 3 bản ghi sản phẩm mẫu thành công.<br>";
    } else {
        // echo "Lỗi khi chèn dữ liệu mẫu: " . $conn->error . "<br>";
    }
} else {
    // echo "Bảng SanPham đã có dữ liệu, bỏ qua việc chèn dữ liệu mẫu.<br>";
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where_clause = '';
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $where_clause = "WHERE TenSP LIKE '%$search%' OR MaSP LIKE '%$search%'";
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $sql_delete = "DELETE FROM SanPham WHERE MaSP = $delete_id";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: b2_1tx.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Quản lý Sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .add-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .add-button:hover {
            background-color: #0056b3;
        }

        .search-form {
            margin: 20px 0;
        }

        .search-input {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .edit-button {
            padding: 5px 10px;
            background-color: #ffc107;
            color: #000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .delete-button {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .edit-button:hover {
            background-color: #e0a800;
        }

        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Quản lý Sản phẩm</h1>

        <!-- Form tìm kiếm -->
        <div class="search-form">
            <form method="get" action="">
                <input type="text" name="search" class="search-input" placeholder="Tìm kiếm theo tên hoặc mã sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-button">Tìm kiếm</button>
                <?php if (!empty($search)): ?>
                    <a href="b2_1tx.php" class="add-button" style="margin-left: 10px;">Xóa tìm kiếm</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Phần hiển thị danh sách sản phẩm -->
        <h2>Danh sách Sản phẩm</h2>
        <table>
            <tr>
                <th>Mã SP</th>
                <th>Tên SP</th>
                <th>Số Lượng</th>
                <th>Giá Tiền</th>
                <th>Giảm Giá</th>
                <th>Thao tác</th>
            </tr>
            <?php
            $sql_select = "SELECT MaSP, TenSP, SoLuong, GiaTien FROM SanPham $where_clause";
            $result_select = $conn->query($sql_select);

            if ($result_select->num_rows > 0) {
                while ($row = $result_select->fetch_assoc()) {
                    $giam_gia = 0;
                    if ($row["SoLuong"] >= 10) {
                        $giam_gia = $row["GiaTien"] * 0.10;
                    }
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["MaSP"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["TenSP"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["SoLuong"]) . "</td>";
                    echo "<td>" . number_format($row["GiaTien"]) . " VNĐ</td>";
                    echo "<td>" . number_format($giam_gia) . " VNĐ</td>";
                    echo "<td class='action-buttons'>";
                    echo "<a href='edit_product.php?id=" . $row["MaSP"] . "' class='edit-button'>Sửa</a>";
                    echo "<a href='?delete=" . $row["MaSP"] . "' class='delete-button' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này?\")'>Xóa</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Không có sản phẩm nào.</td></tr>";
            }
            ?>
        </table>

        <!-- Nút thêm mới -->
        <a href="add_product.php" class="add-button">Thêm sản phẩm mới</a>
    </div>
</body>

</html>

<?php
$conn->close();
?>