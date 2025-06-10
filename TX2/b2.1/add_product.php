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

$message = ""; // Biến để lưu thông báo

// Xử lý khi form thêm sản phẩm được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    $maSP = $_POST["maSP"];
    $tenSP = $_POST["tenSP"];
    $soLuong = $_POST["soLuong"];
    $giaTien = $_POST["giaTien"];

    // Kiểm tra trùng lặp MaSP
    $check_sql = "SELECT MaSP FROM SanPham WHERE MaSP = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("i", $maSP);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $message = "Mã sản phẩm đã tồn tại. Vui lòng nhập mã khác.";
    } else {
        // Chuẩn bị và thực thi câu lệnh SQL để chèn dữ liệu
        $insert_sql = "INSERT INTO SanPham (MaSP, TenSP, SoLuong, GiaTien) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("isii", $maSP, $tenSP, $soLuong, $giaTien);

        if ($stmt->execute() === TRUE) {
            $message = "Sản phẩm mới đã được thêm thành công!";
            // Xóa dữ liệu form sau khi thêm thành công
            $_POST = array();
        } else {
            $message = "Lỗi: " . $conn->error;
        }
        $stmt->close();
    }
    $stmt_check->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Thêm Sản phẩm mới</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            color: #333;
        }

        .form-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .back-button {
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

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Thêm Sản phẩm mới</h1>

        <div class="form-section">
            <?php if (!empty($message)): ?>
                <div class="message <?php echo (strpos($message, 'Lỗi') !== false || strpos($message, 'tồn tại') !== false) ? 'error-message' : ''; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <input type="hidden" name="add_product" value="1">
                <div class="form-group">
                    <label for="maSP">Mã Sản phẩm:</label>
                    <input type="number" id="maSP" name="maSP" value="<?php echo isset($_POST['maSP']) ? htmlspecialchars($_POST['maSP']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="tenSP">Tên Sản phẩm:</label>
                    <input type="text" id="tenSP" name="tenSP" value="<?php echo isset($_POST['tenSP']) ? htmlspecialchars($_POST['tenSP']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="soLuong">Số Lượng:</label>
                    <input type="number" id="soLuong" name="soLuong" value="<?php echo isset($_POST['soLuong']) ? htmlspecialchars($_POST['soLuong']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="giaTien">Giá Tiền:</label>
                    <input type="number" id="giaTien" name="giaTien" value="<?php echo isset($_POST['giaTien']) ? htmlspecialchars($_POST['giaTien']) : ''; ?>" required>
                </div>
                <input type="submit" value="Thêm Sản phẩm">
            </form>

            <a href="b2_1tx.php" class="back-button">Quay lại danh sách</a>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>