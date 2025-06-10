<!DOCTYPE html>
<html>

<head>
    <title>Bài 2 - Sắp xếp mảng giảm dần</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Sắp xếp mảng giảm dần</h2>
        <form method="post">
            <div class="form-group">
                <label for="characters">Nhập các ký tự (phân cách bằng dấu phẩy):</label>
                <input type="text" id="characters" name="characters" required
                    placeholder="Ví dụ: apple, banana, orange"
                    value="<?php echo isset($_POST['characters']) ? htmlspecialchars($_POST['characters']) : ''; ?>">
            </div>
            <input type="submit" value="Xử lý và Hiển thị">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["characters"])) {
                $input = $_POST["characters"];
                $characters_array = array_map('trim', explode(',', $input));
                $characters_array = array_filter($characters_array, function ($value) {
                    return $value !== '';
                });

                if (!empty($characters_array)) {
                    echo "<div class='result'>";
                    echo "<h3>Mảng ban đầu:</h3>";
                    echo "<p>" . implode(', ', $characters_array) . "</p>";

                    rsort($characters_array);
                    echo "<h3>Mảng đã được sắp xếp (giảm dần):</h3>";
                    echo "<p>" . implode(', ', $characters_array) . "</p>";

                    echo "<h3>Tổng số phần tử trong mảng:</h3>";
                    echo "<p>" . count($characters_array) . "</p>";
                    echo "</div>";
                } else {
                    echo "<div class='result'>";
                    echo "<p>Vui lòng nhập một vài ký tự.</p>";
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
</body>

</html>