<?php   //此php檔為了顯示使用者已預約的書籍
session_start();
include('db.php');

if (!isset($_SESSION["username"])) {
    echo "錯誤：用戶未登入！";
    exit;
}

// 取得用戶ID
$username = $_SESSION["username"];

// 搜尋預約書籍清單
$sql = "SELECT * FROM reservation WHERE R_id = (SELECT Id FROM red_account WHERE Account = '$username')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 輸出預約書籍清單
    while ($row = $result->fetch_assoc()) {
        $B_no = $row["B_no"];
        // 查詢書籍詳細資訊
        $bookInfoSql = "SELECT * FROM book WHERE Sys_no = '$B_no' LIMIT 1";
        $bookInfoResult = $conn->query($bookInfoSql);
        if ($bookInfoResult->num_rows > 0) {
            $bookInfoRow = $bookInfoResult->fetch_assoc();
            $isbn = $bookInfoRow["ISBN"];
            $bookName = $bookInfoRow["Name"];
            $author = $bookInfoRow["Author"];
            $version = $bookInfoRow["Version"];

            // 查詢當前預借人數
            $reservationCountSql = "SELECT COUNT(*) AS count FROM reservation WHERE B_no IN (SELECT Sys_no FROM book WHERE 
            isbn = '$bookInfoRow[ISBN]' AND Name = '$bookInfoRow[Name]' AND Author = '$bookInfoRow[Author]' AND Version = '$bookInfoRow[Version]')";
            $reservationCountResult = $conn->query($reservationCountSql);
            $reservationCount = $reservationCountResult->fetch_assoc()["count"];
            //查詢使用者的預借排名
            $userRankSql = "SELECT COUNT(*) AS userRank FROM reservation WHERE B_no IN (SELECT Sys_no FROM book WHERE 
            isbn = '$bookInfoRow[ISBN]' AND Name = '$bookInfoRow[Name]' AND Author = '$bookInfoRow[Author]' AND Version = '$bookInfoRow[Version]')
             AND UNIX_TIMESTAMP(date) < (SELECT UNIX_TIMESTAMP(date) FROM reservation WHERE B_no IN 
             (SELECT Sys_no FROM book WHERE 
             isbn = '$bookInfoRow[ISBN]' AND Name = '$bookInfoRow[Name]' AND Author = '$bookInfoRow[Author]' AND Version = '$bookInfoRow[Version]') 
             AND R_id IN (SELECT Id FROM red_account WHERE Account = '$username'))";
            $userRankResult = $conn->query($userRankSql);
            $userRank = $userRankResult->fetch_assoc()["userRank"] + 1; // 加1是因為索引從0開始

            echo "<div class='data-item'>";
            echo "ISBN: " . $isbn . "<br>";
            echo "書名: " . $bookName . "<br>";
            echo "作者: " . $author . "<br>";
            echo "版本: " . $version . "<br>";
            echo "當前預借人數: " . $reservationCount . " (您排在第 " . $userRank . " 位)<br>";
            echo "</div>";
        }
    }
} else {
    echo "尚無預約書籍";
}


// 關閉資料庫連接
$conn->close();
?>
