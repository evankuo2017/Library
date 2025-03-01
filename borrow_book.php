<?php
session_start();
include('db.php');

if (!isset($_SESSION["username"])) {
    echo "錯誤：用戶未登入！";
    exit;
}

$isbn = $_GET["isbn"];
$bookName = $_GET["bookName"];
$bookAuthor = $_GET["author"];
$bookVersion = $_GET["version"];
$username = $_SESSION["username"];


$selectOverdueSql = "SELECT Overdue FROM reader WHERE Id = (SELECT Id FROM red_account WHERE Account = '$username')";
$row = $conn->query($selectOverdueSql)->fetch_assoc(); 

if($row['Overdue']){
    echo "ban";
}else{
    // 檢查是否有可借閱的書籍
    $sql = "SELECT * FROM book WHERE isbn = '$isbn' AND Name = '$bookName' AND Author = '$bookAuthor' AND Version = '$bookVersion' AND R_id IS NULL LIMIT 1";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $bookId = $row["Sys_no"];
        $username = $_SESSION["username"];

        // 檢查是否已借閱
        $checkSql = "SELECT * FROM book WHERE isbn = '$isbn' AND Name = '$bookName' AND Author = '$bookAuthor' AND Version = '$bookVersion'
        AND R_id = (SELECT Id FROM red_account WHERE Account = '$username') LIMIT 1";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            echo "error";
        }else{
            // 更新書籍的借閱資訊
            $sql = "UPDATE book SET R_id = (SELECT Id FROM red_account WHERE Account = '$username'), Borrow_date = CURRENT_TIMESTAMP WHERE Sys_no = '$bookId'";
            
            if ($conn->query($sql) === TRUE) {
                echo "success";
            } 
        }
    } 

}

// 關閉資料庫連接
$conn->close();
?>
