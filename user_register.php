<?php
// 定義錯誤訊息變數
$error_message = "";

// 檢查是否有 POST 請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 取得使用者輸入的資訊
    $id = $_POST["id"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $sex = $_POST["sex"];
    $contacts = $_POST["contacts"];
    $birth_date = $_POST["birth_date"];

    // 檢查資訊是否為空
    if (!empty($id) && !empty($username) && !empty($password) && !empty($name) && !empty($sex) && !empty($contacts) && !empty($birth_date)) {
        // 資料庫連接設定
        include('db.php');

        // 檢查ID是否已存在於reader資料表中
        $check_id_sql = "SELECT * FROM reader WHERE Id = '$id'";
        $result = $conn->query($check_id_sql);
        if ($result->num_rows > 0) {
            $error_message = "該學生已註冊帳戶！";
        } else {
            // 將性別轉換為對應的值（true 或 false）
            $gender = ($sex == "男") ? 1 : 0;

            // 檢查出生日期格式是否正確
            if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $birth_date)) {
                // 插入資料到 reader 資料表
                $reader_sql = "INSERT INTO reader (Id, Name, Sex, Contacts, Birth_date) VALUES ('$id', '$name', $gender, '$contacts', '$birth_date')";
                if ($conn->query($reader_sql) === TRUE) {
                    // 插入資料到 red_account 資料表
                    $red_account_sql = "INSERT INTO red_account (Account, Password, Id) VALUES ('$username', '$password', '$id')";
                    if ($conn->query($red_account_sql) === TRUE) {
                        $error_message = "註冊成功！";
                    } else {
                        // 刪除剛插入的 reader 資料
                        $delete_reader_sql = "DELETE FROM reader WHERE Id = '$id'";
                        $conn->query($delete_reader_sql);
                        $error_message = "註冊失敗: " . $conn->error;
                    }
                } else {
                    $error_message = "註冊失敗: " . $conn->error;
                }
            } else {
                $error_message = "出生日期格式不正確！";
            }

            // 關閉資料庫連接
            $conn->close();
        }
    } else {
        $error_message = "請填寫所有必填欄位！";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>使用者註冊</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-bottom: 20px;
        }

        .form {
            display: inline-block;
            text-align: left;
        }

        .form input {
            margin-bottom: 10px;
            padding: 5px;
            width: 200px;
        }

        .btn {
            padding: 0px 10px;
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: block;
            margin: 0 auto; 
            opacity: 0.8;
            transition:0.3s;
            border: none;
        }
        .btn:hover{
            opacity:1;
            transform:scale(1.2);
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .gender-input {
            margin-bottom: 10px;
        }

        .gender-input label {
            margin-right: 10px;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
        }

        .button-group button,
        .button-group input[type="submit"] {
            height: 30px;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        input[type="text"] ,input[type="password"]{
            border: none; /* 移除边框 */
            border-radius: 4px; /* 添加圆角 */
            padding: 5px; /* 调整内边距 */
            height: 30px; /* 设置宽度 */
        }
        .gender-input label {
        display: inline-block;
        margin-right: 10px;
        }
    </style>
</head>
<body bgcolor="#b6fcf1" style="margin:0px;">
    <div class="container">
        <h1>使用者註冊</h1>
        <form class="form" action="user_register.php" method="POST">
            <input type="text" name="id" placeholder="學號" required><br>
            <input type="text" name="username" placeholder="帳號名稱" required><br>
            <input type="password" name="password" placeholder="密碼" required><br>
            <input type="text" name="name" placeholder="姓名" required><br>
            <div class="gender-input">
                <label for="male">
                    <input type="radio" id="male" name="sex" style=" width: 50px;" value="男" required> 男
                </label>
                <label for="female">
                    <input type="radio" id="female" name="sex" style=" width: 50px;" value="女" required> 女
                </label>
            </div>
            <input type="text" name="contacts" placeholder="聯絡資訊（電話）" required><br>
            <input type="text" name="birth_date"  placeholder="生日" required><br>
            <div class="button-group">
                <button onclick="location.href='user_login.php'" class="btn">返回</button>
                <input type="submit" value="註冊" style="width: 150px;margin-left: 5px" class="btn">
            </div>
            <div class="error-message"><?php echo $error_message; ?></div>
        </form>
    </div>
</body>
</html>

