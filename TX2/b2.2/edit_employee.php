<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "NhanVienDB";

// Tạo kết nối đến MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message = ""; // Biến để lưu thông báo
$employee = null;

// Lấy thông tin nhân viên cần sửa
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT MaNV, HoTen, Luong, Thuong FROM NhanVien WHERE MaNV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        header("Location: index.php"); // Chuyển hướng nếu không tìm thấy nhân viên
        exit();
    }
    $stmt->close();
} else {
    header("Location: index.php"); // Chuyển hướng nếu không có ID
    exit();
}

// Xử lý khi form sửa nhân viên được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_employee"])) {
    $maNV = $_POST["maNV"];
    $hoTen = $_POST["hoTen"];
    $luong = $_POST["luong"];
    $thuong = $_POST["thuong"];

    // Kiểm tra trùng lặp MaNV (trừ nhân viên hiện tại)
    $check_sql = "SELECT MaNV FROM NhanVien WHERE MaNV = ? AND MaNV != ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ii", $maNV, $id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $message = "Mã nhân viên đã tồn tại. Vui lòng nhập mã khác.";
    } else {
        // Cập nhật thông tin nhân viên
        $update_sql = "UPDATE NhanVien SET MaNV = ?, HoTen = ?, Luong = ?, Thuong = ? WHERE MaNV = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("isiii", $maNV, $hoTen, $luong, $thuong, $id);

        if ($stmt_update->execute() === TRUE) {
            $message = "Cập nhật nhân viên thành công!";
            // Cập nhật lại biến $employee để hiển thị thông tin mới
            $employee['MaNV'] = $maNV;
            $employee['HoTen'] = $hoTen;
            $employee['Luong'] = $luong;
            $employee['Thuong'] = $thuong;
            // Cập nhật ID hiện tại nếu MaNV đã thay đổi
            $id = $maNV;
        } else {
            $message = "Lỗi: " . $conn->error;
        }
        $stmt_update->close();
    }
    $stmt_check->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sửa Nhân viên</title>
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
        <h1>Sửa Thông tin Nhân viên</h1>

        <div class="form-section">
            <?php if (!empty($message)): ?>
                <div class="message <?php echo (strpos($message, 'Lỗi') !== false || strpos($message, 'tồn tại') !== false) ? 'error-message' : ''; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($employee): ?>
                <form method="post" action="">
                    <input type="hidden" name="edit_employee" value="1">
                    <div class="form-group">
                        <label for="maNV">Mã Nhân viên:</label>
                        <input type="number" id="maNV" name="maNV" value="<?php echo htmlspecialchars($employee['MaNV']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hoTen">Họ Tên:</label>
                        <input type="text" id="hoTen" name="hoTen" value="<?php echo htmlspecialchars($employee['HoTen']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="luong">Lương:</label>
                        <input type="number" id="luong" name="luong" value="<?php echo htmlspecialchars($employee['Luong']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="thuong">Thưởng:</label>
                        <input type="number" id="thuong" name="thuong" value="<?php echo htmlspecialchars($employee['Thuong']); ?>" required>
                    </div>
                    <input type="submit" value="Cập nhật Nhân viên">
                </form>
            <?php else: ?>
                <p>Không tìm thấy nhân viên.</p>
            <?php endif; ?>

            <a href="index.php" class="back-button">Quay lại danh sách</a>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>