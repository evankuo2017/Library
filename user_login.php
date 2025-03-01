<?php
include('db.php');

// 定義錯誤訊息變數
$errorMsg = "";

// 從 POST 請求中獲取使用者名稱和密碼
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 建立查詢使用者的 SQL 語句
    $sql = "SELECT * FROM red_account WHERE Account COLLATE utf8mb4_bin = '$username' AND Password COLLATE utf8mb4_bin = '$password'";

    // 執行查詢
    $result = $conn->query($sql);

    // 檢查結果是否有資料
    if ($result->num_rows > 0) {
        // 登入成功，重新導向
        session_start();
        $_SESSION["username"] = $username;
        header("Location: user_dashboard.php");
        exit(); // 確保在重新導向後停止執行後續程式碼
    } else {
        // 登入失敗，顯示錯誤訊息
        $errorMsg = "帳號或密碼錯誤！";
    }
}

// 關閉資料庫連接
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>使用者登入</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
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
        .button-group {
            display: flex;
            justify-content: flex-end;
        }

        .button-group button,
        .button-group input[type="submit"] {
            height: 30px;
        }

        .error-msg {
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
    </style>
</head>
<body bgcolor="#b6fcf1" style="margin:0px;">
    
    <div class="container">
        <img style="display:block; margin:auto;" width=20% src="image\reader.png" alt="mine">
        <h1>使用者登入</h1>
        <form class="form" action="user_login.php" method="POST">
            <input type="text" name="username" placeholder="使用者名稱" required><br>
            <input type="password" name="password" placeholder="密碼" required><br>
            <div class="button-group">
                <button onclick="location.href='index.php'" class="btn">返回</button>
                <input type="submit" value="登入" style="width:150px;margin-left:1px" class="btn">
            </div>
        </form>
        <p>還沒有帳號？<a href="user_register.php">註冊</a></p>
        <div class="error-msg"><?php echo $errorMsg; ?></div>
    </div>
</body>
</html>
