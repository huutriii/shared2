<!DOCTYPE html>
<html>

<head>
    <title>Bài 2 (1tx) - Tổng các số chia hết cho 3</title>
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
        <h2>Tổng các phần tử chia hết cho 3</h2>
        <form method="post">
            <div class="form-group">
                <label for="numbers">Nhập các số (phân cách bằng dấu phẩy):</label>
                <input type="text" id="numbers" name="numbers" required
                    placeholder="Ví dụ: 1,2,3,4,5,6"
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

                // Lọc bỏ các giá trị không phải số hoặc rỗng sau khi chuyển đổi
                $numbers = array_filter($numbers, function ($value) {
                    return is_numeric($value);
                });

                if (!empty($numbers)) {
                    $sum_divisible_by_3 = 0;
                    $elements_divisible_by_3 = [];

                    foreach ($numbers as $number) {
                        if ($number % 3 == 0) {
                            $sum_divisible_by_3 += $number;
                            $elements_divisible_by_3[] = $number;
                        }
                    }

                    echo "<div class='result'>";
                    echo "<h3>Mảng ban đầu:</h3>";
                    echo "<p>" . implode(', ', $numbers) . "</p>";

                    if (!empty($elements_divisible_by_3)) {
                        echo "<h3>Các phần tử chia hết cho 3:</h3>";
                        echo "<p>" . implode(', ', $elements_divisible_by_3) . "</p>";
                        echo "<h3>Tổng các phần tử chia hết cho 3:</h3>";
                        echo "<p><strong>" . $sum_divisible_by_3 . "</strong></p>";
                    } else {
                        echo "<h3>Không có phần tử nào chia hết cho 3 trong mảng.</h3>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='result'>";
                    echo "<p>Vui lòng nhập các số nguyên hợp lệ.</p>";
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
</body>

</html>