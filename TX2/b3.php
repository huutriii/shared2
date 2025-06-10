<!DOCTYPE html>
<html>

<head>
    <title>Bài 3 - Tìm từ dài nhất</title>
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
        <h2>Tìm từ dài nhất</h2>
        <form method="post">
            <div class="form-group">
                <label for="characters">Nhập các từ (phân cách bằng dấu phẩy):</label>
                <input type="text" id="characters" name="characters" required
                    placeholder="Ví dụ: apple, banana, orange, strawberry"
                    value="<?php echo isset($_POST['characters']) ? htmlspecialchars($_POST['characters']) : ''; ?>">
            </div>
            <input type="submit" value="Tìm từ dài nhất">
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

                    $longest_word = "";
                    $max_length = 0;

                    foreach ($characters_array as $word) {
                        if (mb_strlen($word, 'UTF-8') > $max_length) {
                            $max_length = mb_strlen($word, 'UTF-8');
                            $longest_word = $word;
                        }
                    }

                    echo "<h3>Kết quả:</h3>";
                    echo "<p>Từ dài nhất: <strong>" . htmlspecialchars($longest_word) . "</strong></p>";
                    echo "<p>Số lượng ký tự của từ đó: <strong>" . $max_length . "</strong></p>";
                    echo "</div>";
                } else {
                    echo "<div class='result'>";
                    echo "<p>Vui lòng nhập một vài từ.</p>";
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
</body>

</html>