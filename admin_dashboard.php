<!DOCTYPE html>
<html>
<head>
    <title>管理員控制頁面</title>
    <style>
        .container {
            width: 600px;
            margin: 0 auto;
        }

        .search-bar {
            margin-bottom: 10px;
        }

        .data-window {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: white;
            border-radius: 10px;
        }

        .data-item {
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
        }

        

        .logout-button {
            
        }

        button ,.btn {
            padding: 0px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            
            opacity: 0.8;
            transition:0.3s;
            border: none;
        }
        button:hover{
            opacity:1;
            transform:scale(1.2);
        }

        input[type="text"] ,input[type="password"]{
            border: none; /* 移除边框 */
            border-radius: 4px; /* 添加圆角 */
            padding: 5px; /* 调整内边距 */
            height: 20; /* 设置宽度 */
        }
    </style>
</head>
<body bgcolor="#b6fcf1" style="margin:0px;">
    <div class="container">
    <?php
        session_start();
        if (!isset($_SESSION["admin_username"])) {
            echo "<h1>發生錯誤(您未登入!)</h1>";
            exit;
        }
        else {//isset($_SESSION["admin_username"])為true
            $admin_username = $_SESSION["admin_username"];
            echo "<h1>歡迎，管理員：$admin_username</h1>";
            echo "<button class='logout-button' onclick='logout()'>登出</button>";
        } 
        ?>
        <h2>使用者帳戶管理</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="輸入 Reader ID">
            <button onclick="<?php echo 'search();' ?>">搜尋</button>
            <button onclick="<?php echo 'showAll();' ?>">顯示全部</button>
        </div>
        <div class="data-window">
            <?php
            // 資料庫連接設定
            $servername = "localhost";
            $dbusername = "root";
            $dbpassword = "12345678";
            $dbname = "library";

            // 建立資料庫連接
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
            if ($conn->connect_error) {
                die("資料庫連接失敗: " . $conn->connect_error);
            }

            // 搜尋特定 Reader 資料
            if (isset($_GET["reader_id"]) && $_GET["reader_id"]!=="") {
                $reader_id = $_GET["reader_id"];
                $reader_id = "'$reader_id'";
                $sql = "SELECT * FROM reader WHERE Id = $reader_id";
            } else {
                $sql = "SELECT * FROM reader";
            }

            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // 撈取使用者借閱的所有書籍
                    $sql = "SELECT * FROM book WHERE R_id = '$row[Id]'";
                    $bookresult = $conn->query($sql);
                    // 是否有逾期書籍的標記
                    $hasOverdue = false;
                    if ($bookresult->num_rows > 0) {
                        while ($bookrow = $bookresult->fetch_assoc()) {
                            $borrow_date = $bookrow["Borrow_date"];
                            
                            // 確認書籍是否逾期
                            if (checkOverdue($borrow_date)) {
                                $hasOverdue = true;
                                break; // 若有逾期書籍，則跳出迴圈
                            }
                        }
                    } 
                    $id = $row["Id"];
                    $name = $row["Name"];
                    $sex = $row["Sex"];
                    $contacts = $row["Contacts"];
                    $birth_date = $row["Birth_date"];
                    $sex = ($sex) ? "男" : "女";
                    $overdue = ($hasOverdue) ? "是" : "否";
                    echo "<div class='data-item'>";
                    echo "Reader ID: $id<br>";
                    echo "姓名: $name<br>";
                    echo "性別: $sex<br>";
                    echo "聯絡資訊: $contacts<br>";
                    echo "生日: $birth_date<br>";
                    echo "是否逾期: $overdue<br>";
                    echo "<button class='delete-button' onclick='deleteReader(\"$id\")'>註銷</button>";
                    echo "</div>";
                }
            } else {
                echo "查無該 Reader 資料";
            }

            // 關閉資料庫連接
            $conn->close();
            ?>
        </div>
    </div>

    <div class="container">
        <h2>書籍管理</h2>
        <div class="search-bar">
            <input type="text" id="searchInput2" placeholder="輸入Book之System ID">
            <button onclick="<?php echo 'searchBookId();' ?>">搜尋</button>
            <input type="text" id="searchInput3" placeholder="輸入Book之名稱">
            <button onclick="<?php echo 'searchBookName();' ?>">搜尋</button>
            <button onclick="<?php echo 'showAll();' ?>">顯示全部</button>
        </div>
        <div class="data-window">
            <?php
            include('db.php');

            // 搜尋特定 Books 資料
            if (isset($_GET["bookNo"]) && $_GET["bookNo"]!=="") {
                $bookNo = $_GET["bookNo"];
                $bookNo = "'$bookNo'";
                $sql = "SELECT * FROM book WHERE Sys_no = $bookNo";
            } else if (isset($_GET["bookName"]) && $_GET["bookName"]!==""){
                $bookName = $_GET["bookName"];
                $bookName = "'$bookName'";
                $sql = "SELECT * FROM book WHERE Name LIKE '%' $bookName  '%'";
            }
            else {
                $sql = "SELECT * FROM book";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $ISBN = $row["ISBN"];
                    $book_sys_no = $row["Sys_no"];
                    $book_name = $row["Name"];
                    $author = $row["Author"];
                    $version = $row["Version"];
                    $borrow_id = $row["R_id"];
                    $borrow_date = $row["Borrow_date"];
                    echo "<div class='data-item'>";
                    echo "圖書系統號: $book_sys_no<br>";
                    echo "ISBN: $ISBN<br>";
                    echo "書名: $book_name<br>";
                    echo "作者: $author<br>";
                    echo "版本: $version<br>";
                    echo "已借出讀者ID: $borrow_id<br>";
                    echo "借出日期: $borrow_date<br>";
                    echo "<button class='delete-button' onclick='deleteBook(\"$book_sys_no\")'>刪除</button>";
                    echo "</div>";
                }
            } else {
                echo "查無任何書籍資料";
            }

            // 關閉資料庫連接
            $conn->close();
            ?>
        </div>
    </div>

    <div class="container">
        <?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $NewSysno = $_POST["NewSysno"];
                $NewISBN = $_POST["NewISBN"];
                $NewName = $_POST["NewName"];
                $NewAuthor = $_POST["NewAuthor"];
                $NewVersion = $_POST["NewVersion"];

                if ($NewSysno !== "" && $NewISBN !== "" && $NewName !== "" && $NewAuthor !== "" && $NewVersion !== "") {
                    // 資料庫連接設定
                    $servername = "localhost";
                    $dbusername = "root";
                    $dbpassword = "mrp2023Big?";
                    $dbname = "library";
            
                    // 建立資料庫連接
                    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
                    // 檢查連接是否成功
                    if ($conn->connect_error) {
                        die("資料庫連接失敗: " . $conn->connect_error);
                    }
            
                    // 檢查ID是否已存在於reader資料表中
                    $check_id_sql = "SELECT * FROM book WHERE Sys_no = '$NewSysno'";
                    $result = $conn->query($check_id_sql);
                    if ($result->num_rows > 0) {
                        echo "<script>alert('該書籍之圖書系統號已經存在！');</script>";
                    } else {
                        $book_sql = "INSERT INTO book (ISBN, Sys_no, Name, Author, Version, R_id, Borrow_date) VALUES ('$NewISBN', '$NewSysno', '$NewName', '$NewAuthor', '$NewVersion',NULL,NULL)";
                        if($conn->query($book_sql) === TRUE){
                            echo "<script>alert('新增成功！');</script>";
                            echo "<script>window.location.href = window.location.href;</script>";
                        }
                        else {
                            echo "<script>alert('新增失敗！');</script>";
                        }
            
                        // 關閉資料庫連接
                        $conn->close();
                    }
                } else {
                    echo "<script>alert('請填寫所有欄位');</script>";
                }
            }
        ?>

        <h2>新增書籍</h2>
        <form class="form" action="admin_dashboard.php" method="POST" name="createBook">
        <input type="text" name="NewSysno" placeholder="圖書系統號" required style="margin-bottom: 10px;"><br>
        <input type="text" name="NewISBN" placeholder="ISBN" required style="margin-bottom: 10px;"><br>
        <input type="text" name="NewName" placeholder="書籍名稱" required style="margin-bottom: 10px;"><br>
        <input type="text" name="NewAuthor" placeholder="作者" required style="margin-bottom: 10px;"><br>
        <input type="text" name="NewVersion" placeholder="版本" required style="margin-bottom: 10px;"><br>


            <div class="button-group">
                <input type="submit" value="註冊" style="width: 150px;margin-left: 5px" class="btn">
            </div>
        </form>
    </div>

    <div class="container">
        <h2>預約資訊</h2>
        <div class="data-window" style="margin-top:10px;margin-bottom:20px">
            <?php
            // 建立資料庫連接
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
            if ($conn->connect_error) {
                die("資料庫連接失敗: " . $conn->connect_error);
            }

            $reservation_sql = "SELECT * FROM reservation";
            $reservation_result = $conn->query($reservation_sql);

            if ($reservation_result->num_rows > 0) {
                while ($reservation_row = $reservation_result->fetch_assoc()) {
                    $reader_id = $reservation_row["R_id"];
                    $book_sys_no = $reservation_row["B_no"];
                    $reservation_date = $reservation_row["date"];

                    // 查詢使用者姓名
                    $user_sql = "SELECT * FROM reader WHERE Id = '$reader_id'";
                    $user_result = $conn->query($user_sql);
                    $user_row = $user_result->fetch_assoc();
                    $user_name = $user_row["Name"];

                    // 查詢書籍資訊
                    $book_sql = "SELECT * FROM book WHERE Sys_no = '$book_sys_no'";
                    $book_result = $conn->query($book_sql);
                    $book_row = $book_result->fetch_assoc();
                    $book_isbn = $book_row["ISBN"];
                    $book_name = $book_row["Name"];

                    echo "<div class='data-item'>";
                    echo "使用者 ID: $reader_id<br>";
                    echo "使用者姓名: $user_name<br>";
                    echo "書籍 ISBN: $book_isbn<br>";
                    echo "書籍名稱: $book_name<br>";
                    echo "預約時間: $reservation_date<br>";
                    echo "</div>";
                }
            } else {
                echo "目前沒有任何預約資訊";
            }

            // 關閉資料庫連接
            $conn->close();
            ?>
        </div>
    </div>


    <script>
        function search() {
            var readerId = document.getElementById("searchInput").value;
            if (readerId.trim() !== "") {
                window.location.href = "?reader_id=" + readerId;
            }
        }

        function showAll() {
            window.location.href = window.location.pathname;
        }

        function deleteReader(id) {
            if (confirm("確定要刪除該 Reader 嗎？")) {
                // 使用 Ajax 向後端發送刪除請求
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        // 刷新頁面以更新資料
                        location.reload();
                    }
                };
                xhttp.open("GET", "delete_reader.php?id=" + encodeURIComponent(id), true);
                xhttp.send();
            }
        }

        function searchBookId() {
            var bookId = document.getElementById("searchInput2").value;
            if (bookId.trim() !== "") {
                window.location.href = "?bookNo=" + bookId;
            }
        }


        function searchBookName() {
            var bookName = document.getElementById("searchInput3").value;
            if (bookName.trim() !== "") {
                window.location.href = "?bookName=" + encodeURIComponent(bookName);
            }
        }


        function deleteBook(book_sys_no) {
            if (confirm("確定要刪除書籍嗎？")) {
                // 使用 Ajax 向後端發送刪除請求
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        // 刷新頁面以更新資料
                        location.reload();
                    }
                };
                xhttp.open("GET", "delete_book.php?book_sys_no=" + encodeURIComponent(book_sys_no), true);
                xhttp.send();
            }
        }

        function logout() {
            if (confirm("確定登出嗎？")) {
                // 使用 AJAX 向後端發送登出請求
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        // 登出成功後導向到登入頁面
                        window.location.href = "admin_login.php";
                    }
                };
                xhttp.open("GET", "admin_logout.php", true); 
                xhttp.send();
            }
        }
    </script>
    <?php
    // 確認讀者是否逾期借閱書籍
    function checkOverdue($borrow_date) {
        $borrow_timestamp = strtotime($borrow_date);
        $current_timestamp = time();
        $minutes_diff = round(($current_timestamp - $borrow_timestamp) / 60);
        if ($minutes_diff > 1) {
            return 1;
        } else {
            return 0;
        }   
    }
    ?>
</body>
</html>
