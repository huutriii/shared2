<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "NhanVienDB";

// Tạo kết nối đến MySQL server
$conn = new mysqli($servername, $username, $password);

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

// Tạo bảng NhanVien nếu chưa tồn tại
$sql_create_table = "CREATE TABLE IF NOT EXISTS NhanVien (
    MaNV INT PRIMARY KEY,
    HoTen VARCHAR(255) NOT NULL,
    Luong INT NOT NULL,
    Thuong INT NOT NULL
)";

if ($conn->query($sql_create_table) === TRUE) {
    // echo "Bảng \"NhanVien\" đã được tạo hoặc đã tồn tại.<br>";
} else {
    // echo "Lỗi khi tạo bảng: " . $conn->error . "<br>";
}

// Chèn 3 bản ghi nhân viên mẫu (chỉ chèn nếu bảng rỗng để tránh trùng lặp)
$check_empty = "SELECT COUNT(*) FROM NhanVien";
$result = $conn->query($check_empty);
$row = $result->fetch_row();

if ($row[0] == 0) {
    $sql_insert_data = "INSERT INTO NhanVien (MaNV, HoTen, Luong, Thuong) VALUES
    (1, 'Nguyễn Văn A', 1500, 200),
    (2, 'Trần Thị B', 900, 100),
    (3, 'Lê Văn C', 2000, 300)";

    if ($conn->query($sql_insert_data) === TRUE) {
        // echo "Đã chèn 3 bản ghi nhân viên mẫu thành công.<br>";
    } else {
        // echo "Lỗi khi chèn dữ liệu mẫu: " . $conn->error . "<br>";
    }
} else {
    // echo "Bảng NhanVien đã có dữ liệu, bỏ qua việc chèn dữ liệu mẫu.<br>";
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where_clause = '';
if (!empty($search)) {
    $search_safe = $conn->real_escape_string($search);
    $where_clause = "WHERE HoTen LIKE '%$search_safe%' OR MaNV LIKE '%$search_safe%'";
}

// Xử lý xóa nhân viên
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $sql_delete = "DELETE FROM NhanVien WHERE MaNV = $delete_id";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Danh sách Nhân viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 1000px;
            /* Tăng chiều rộng để phù hợp với các nút */
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
            display: flex;
            gap: 10px;
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
            gap: 5px;
            /* Giảm khoảng cách giữa các nút */
        }

        .edit-button,
        .delete-button {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            /* Để áp dụng gap */
        }

        .edit-button {
            background-color: #ffc107;
            color: #333;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .edit-button:hover {
            background-color: #e0a800;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .clear-search-button {
            background-color: #6c757d;
            /* Màu xám */
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .clear-search-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Quản lý Nhân viên</h1>

        <!-- Form tìm kiếm -->
        <div class="search-form">
            <form method="get" action="">
                <input type="text" name="search" class="search-input" placeholder="Tìm kiếm theo tên hoặc mã nhân viên..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-button">Tìm kiếm</button>
                <?php if (!empty($search)): ?>
                    <a href="index.php" class="clear-search-button">Xóa tìm kiếm</a>
                <?php endif; ?>
            </form>
        </div>

        <h2>Danh sách Nhân viên</h2>
        <table>
            <tr>
                <th>Mã NV</th>
                <th>Họ Tên</th>
                <th>Lương</th>
                <th>Thưởng</th>
                <th>Thuế</th>
                <th>Thao tác</th>
            </tr>
            <?php
            $sql_select = "SELECT MaNV, HoTen, Luong, Thuong FROM NhanVien $where_clause";
            $result_select = $conn->query($sql_select);

            if ($result_select->num_rows > 0) {
                while ($row = $result_select->fetch_assoc()) {
                    $thue = 0;
                    if ($row["Luong"] > 1000) {
                        $thue = $row["Luong"] * 0.10;
                    }
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["MaNV"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["HoTen"]) . "</td>";
                    echo "<td>" . number_format($row["Luong"]) . " VNĐ</td>";
                    echo "<td>" . number_format($row["Thuong"]) . " VNĐ</td>";
                    echo "<td>" . number_format($thue) . " VNĐ</td>";
                    echo "<td class='action-buttons'>";
                    echo "<a href='edit_employee.php?id=" . $row["MaNV"] . "' class='edit-button'>Sửa</a>";
                    echo "<a href='index.php?delete=" . $row["MaNV"] . "' class='delete-button' onclick='return confirm(\"Bạn có chắc chắn muốn xóa nhân viên này?\")'>Xóa</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Không có nhân viên nào.</td></tr>";
            }
            ?>
        </table>

        <!-- Nút thêm mới -->
        <a href="add_employee.php" class="add-button">Thêm nhân viên mới</a>
    </div>
</body>

</html>

<?php
$conn->close();
?>