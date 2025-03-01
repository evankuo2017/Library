<!DOCTYPE html>
<html>
<head>
    <title>用戶頁面</title>
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

        .delete-button {
            float: right;
        }

        .logout-button {
            
        }

        .account-info {
            display: none;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }
        
        button {
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
        include('db.php');
        if (!isset($_SESSION["username"])) {
            echo "<h1>發生錯誤(您未登入!)</h1>";
            exit;
        }
        else {
            $username = $_SESSION["username"];
            echo "<h1>歡迎，用戶：$username</h1>";
            echo "<button class='logout-button' onclick='logout()'>登出</button>";
            echo "<button onclick='showAccountInfo()' style='margin-left:5px'>帳號資訊</button><br>";
        } 
        ?>
        <div class="account-info" id="accountInfo"style="display: none">
            <?php
            // include('db.php');
            // 取得帳號資訊
            $sql = "SELECT * FROM red_account WHERE Account = '$username'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $Id = $row["Id"];
                $sql = "SELECT * FROM reader WHERE Id = '$Id'";
                $result = $conn->query($sql);
                
                $row = $result->fetch_assoc();
                $id = $row["Id"];
                $name = $row["Name"];
                $sex = $row["Sex"];
                $contacts = $row["Contacts"];
                $birth_date = $row["Birth_date"];
                $overdue = $row["Overdue"] ? "是" : "否";
                $sex = ($sex) ? "男" : "女";
                echo "Reader ID: $id<br>";
                echo "姓名: $name<br>";
                echo "性別: $sex<br>";
                echo "聯絡資訊: $contacts<br>";
                echo "生日: $birth_date<br>";
                echo "是否逾期: $overdue<br>";
            } else {
                echo "查無該 Reader 資料";
            }

            // 關閉資料庫連接
            // $conn->close();
            ?> 
        </div>
        <h3>書目清單:</h3>
        <div class="search-bar">
            <input type="text" id="searchInput3" placeholder="輸入Book之名稱">
            <button onclick="<?php echo 'searchBookName();' ?>">搜尋</button>
            <button onclick="<?php echo 'showAll();' ?>">顯示全部</button>
        </div>

        <div class="data-window" id="bookDataWindow">
            <?php
            // include('db.php');

            //初始逾期狀態
            $hasOverdue = false;

            // 搜尋特定 Books 資料
            if (isset($_GET["bookName"]) && $_GET["bookName"]!==""){
                $bookName = $_GET["bookName"];
                $bookName = "'$bookName'";
                $sql = "SELECT * FROM book WHERE Name LIKE '%' $bookName  '%'";
            }
            else {
                $sql = "SELECT * FROM book";
            }

            $result = $conn->query($sql);
            

            if ($result->num_rows > 0) {
                // 建立一個關聯陣列來儲存書籍資料
                $books = array();
            
                while ($row = $result->fetch_assoc()) {
                    $isbn = $row["ISBN"];
                    $bookName = $row["Name"];
                    $author = $row["Author"];
                    $version = $row["Version"];
                    $borrowId = $row["R_id"];
                    $borrowDate = $row["Borrow_date"];
            
                    // 檢查是否已經存在相同的書籍
                    $bookKey = $isbn . $bookName . $author . $version;
                    if (array_key_exists($bookKey, $books)) {
                        // 如果存在，增加數量和可借閱數量
                        $books[$bookKey]['count']++;
                        if (is_null($borrowId)) {
                            $books[$bookKey]['availableCount']++;
                        }
                    } else {
                        // 如果不存在，新增一個書籍資料項目
                        $books[$bookKey] = array(
                            'isbn' => $isbn,
                            'bookName' => $bookName,
                            'author' => $author,
                            'version' => $version,
                            'count' => 1,
                            'availableCount' => is_null($borrowId) ? 1 : 0
                        );
                    }
                }
            
                // 輸出書籍資料
                foreach ($books as $book) {
                    $reservationCountSql = "SELECT COUNT(*) AS count FROM reservation WHERE B_no IN 
                    (SELECT Sys_no FROM book WHERE isbn = '$book[isbn]' AND Name = '$book[bookName]' AND Author = '$book[author]' AND Version = '$book[version]')";
                    $reservationCountResult = $conn->query($reservationCountSql);
                    $reservationCount = ($book['availableCount'] > 0) ? "" : " (預約人數：" . $reservationCountResult->fetch_assoc()["count"].")";
                    echo "<div class='data-item'>";
                    echo "ISBN: " . $book['isbn'] . "<br>";
                    echo "書名: " . $book['bookName'] . "<br>";
                    echo "作者: " . $book['author'] . "<br>";
                    echo "版本: " . $book['version'] . "<br>";
                    echo "數量: " . $book['count'] . "<br>";
                    echo "可借閱數量: " . $book['availableCount'] . $reservationCount . "<br>";

                    // 添加借書按钮
                    echo "<button onclick='borrowBook(\"" . $book['isbn'] . "\", \"" . $book['bookName'] . "\", \"" . $book['author'] . "\"
                    , \"" . $book['version'] . "\", " . $book['availableCount'] .")'>借書</button>";
                    echo "</div>";
                }
            } else {
                echo "查無任何書籍資料";
            }

            // 關閉資料庫連接
            // $conn->close();
            ?>
        </div>

        <div class="container">
                <!--顯示借書區-->
                <h3>您的借閱清單:</h3>
                <div class="data-window" id="bookBorrowedWindow">
                <?php
                    // include('db.php');

                    //取得$rowbefore["Overdue"]
                    $selectOverdueSql = "SELECT Overdue FROM reader WHERE Id = (SELECT Id FROM red_account WHERE Account = '$username')";
                    $rowbefore = $conn->query($selectOverdueSql)->fetch_assoc();

                    
                    $sql = "SELECT * FROM book WHERE R_id = '$Id'";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        //該使用者是否逾期
                        $userOverdue = false;
                        while ($row = $result->fetch_assoc()) {//在輸出借閱書籍資訊的同時檢查有無逾期
                            $isbn = $row["ISBN"];
                            $sysno = $row["Sys_no"];
                            $bookName = $row["Name"];
                            $author = $row["Author"];
                            $version = $row["Version"];
                            $borrowDate = $row["Borrow_date"];
                            if(checkOverdue($borrowDate)){
                                $bookOverdue = " 已逾期";
                                $userOverdue = true;
                            } else {
                                $bookOverdue = "" ;
                            }
                            echo "<div class='data-item'>";
                            echo "ISBN: " . $isbn . "<br>";
                            echo "書名: " . $bookName . "<br>";
                            echo "作者: " . $author . "<br>";
                            echo "版本: " . $version . "<br>";
                            echo "借出時間: " . $borrowDate . $bookOverdue ."<br>";
                            echo "<button class='return-button' onclick='returnBook(\"$sysno\")'>還書</button>";
                            echo "</div>";
                        }

                        if($userOverdue){// 更新使用者的Overdue狀態為1
                            $updateSql = "UPDATE reader SET Overdue = 1 WHERE Id = (SELECT Id FROM red_account WHERE Account = '$username')";
                            $conn->query($updateSql);

                        }else{// 更新使用者的Overdue狀態為0
                            $updateSql = "UPDATE reader SET Overdue = 0 WHERE Id = (SELECT Id FROM red_account WHERE Account = '$username')";
                            $conn->query($updateSql);
                        }

                    } else {
                        echo "目前尚未借閱書籍!";
                        // 更新使用者的Overdue狀態為0
                        $updateSql = "UPDATE reader SET Overdue = 0 WHERE Id = (SELECT Id FROM red_account WHERE Account = '$username')";
                        $conn->query($updateSql);
                    }

                    //因為Overdue改變後需要頁面刷新才會更新資訊故利用$rowbefore["Overdue"]、$rowafter["Overdue"]比較
                    $rowafter = $conn->query($selectOverdueSql)->fetch_assoc();
                    if($rowbefore["Overdue"] !== $rowafter["Overdue"])echo "<script>window.location.reload();</script>";
                    // 關閉資料庫連接
                    $conn->close();
                ?>
                </div>
        </div>
        <button onclick="showReservedBooks()" style="margin-top:10px;margin-bottom:10px">顯示預約書籍清單</button>
        <div class="data-window" id="reservedBooksWindow" style="display: none;"></div>
    </div>

    <script>
        function logout() {
            if (confirm("確定登出嗎？")) {
                // 使用 AJAX 向後端發送登出請求
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        // 登出成功後導向到登入頁面
                        window.location.href = "user_login.php";
                    }
                };
                xhttp.open("GET", "user_logout.php", true);
                xhttp.send();
            }
        }

        function showAccountInfo() {
            var accountInfo = document.getElementById("accountInfo");
            if (accountInfo.style.display === "none") {
                accountInfo.style.display = "block";
            } else {
                accountInfo.style.display = "none";
            }
        }

        function searchBookName() {
            var bookName = document.getElementById("searchInput3").value;
            if (bookName.trim() !== "") {
                window.location.href = "?bookName=" + encodeURIComponent(bookName);
            }
        }

        function showAll() {
            window.location.href = window.location.pathname;
        }

        function borrowBook(isbn, bookName, author, version, availableCount) {
            if (availableCount > 0) {
                if (confirm("確定借書嗎？")) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState === 4 && this.status === 200) {
                            var response = this.responseText;
                            if (response === "success") {
                                alert("借書成功！");
                                location.reload();
                            }else if (response === "error") {
                                alert("已借閱過該書籍！");
                            }else if (response === "ban") {
                                alert("您已失去借書權限，請檢查是否有逾期未歸還之書籍！");
                            }else {
                                alert(response);
                            }
                        }
                    };
                    var url = "borrow_book.php?bookName=" + encodeURIComponent(bookName);
                    url += "&isbn=" + encodeURIComponent(isbn);
                    url += "&author=" + encodeURIComponent(author);
                    url += "&version=" + encodeURIComponent(version);
                    
                    xhttp.open("GET", url, true);
                    xhttp.send();
                }
            } else {
                if (confirm("此書現已無館藏，要預約嗎？")) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState === 4 && this.status === 200) {
                            var response = this.responseText;
                            if (response === "success") {
                                alert("預約成功！");
                                location.reload();
                            }else if (response === "error1") {
                                alert("已預約過該書籍！");
                            }else if (response === "error2") {
                                alert("已借閱過該書籍！");
                            }else if (response === "ban") {
                                alert("您已失去預約權限，請檢查是否有逾期未歸還之書籍！");
                            }else {
                                alert(response);
                            }
                        }
                    };
                    var url = "reserve_book.php?bookName=" + encodeURIComponent(bookName);
                    url += "&isbn=" + encodeURIComponent(isbn);
                    url += "&author=" + encodeURIComponent(author);
                    url += "&version=" + encodeURIComponent(version);
                    
                    xhttp.open("GET", url, true);
                    xhttp.send();
                }
            }
        }

        function returnBook(sysno){
            if (confirm("確定要還書嗎？")) {
                var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState === 4 && this.status === 200) {
                            var response = this.responseText;
                            if (response === "success") {
                                alert("還書成功！");
                                location.reload();
                            } else {
                                alert(response);
                            }
                        }
                    };
                    xhttp.open("GET", "return_book.php?sysno=" + encodeURIComponent(sysno), true);
                    xhttp.send();
            }
        }

        function showReservedBooks() {
        var reservedBooksWindow = document.getElementById("reservedBooksWindow");
        if (reservedBooksWindow.style.display === "none") {
            // 使用 AJAX 向後端請求預約書籍清單
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    var response = this.responseText;
                    reservedBooksWindow.innerHTML = response;
                    reservedBooksWindow.style.display = "block";
                }
            };
            xhttp.open("GET", "reserved_book.php", true);
            xhttp.send();
        } else {
            reservedBooksWindow.style.display = "none";
        }
    }
    </script>
    <?php
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









