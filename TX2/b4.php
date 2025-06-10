<!DOCTYPE html>
<html>

<head>
    <title>Bài 4 - Tính tổng số lẻ</title>
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
        <h2>Tính tổng các số lẻ trong mảng</h2>
        <form method="post">
            <div class="form-group">
                <label for="numbers">Nhập các số (phân cách bằng dấu phẩy):</label>
                <input type="text" id="numbers" name="numbers" required
                    placeholder="Ví dụ: 1,2,3,4,5"
                    value="<?php echo isset($_POST['numbers']) ? htmlspecialchars($_POST['numbers']) : ''; ?>">
            </div>
            <input type="submit" value="Tính tổng">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["numbers"])) {
                $input = $_POST["numbers"];
                // Chuyển chuỗi thành mảng số
                $numbers = array_map('intval', explode(',', $input));

                // Tính tổng các số lẻ
                $sum = 0;
                foreach ($numbers as $number) {
                    if ($number % 2 != 0) {
                        $sum += $number;
                    }
                }

                echo "<div class='result'>";
                echo "<h3>Kết quả:</h3>";
                echo "<p>Mảng số đã nhập: " . implode(', ', $numbers) . "</p>";
                echo "<p>Tổng các số lẻ trong mảng: " . $sum . "</p>";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>

</html>