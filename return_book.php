<?php
session_start();

if (!isset($_SESSION["username"])) {
    echo "錯誤：用戶未登入！";
    exit;
}

$sysno = $_GET["sysno"];

// 資料庫連接設定
$servername = "localhost";
$dbusername = "root";
$dbpassword = "mrp2023Big?";
$dbname = "library";

// 建立資料庫連接
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("資料庫連接失敗: " . $conn->connect_error);
}

$paramsql = "SELECT ISBN, Name, Author, Version FROM book WHERE Sys_no = '$sysno'";
$result = $conn->query($paramsql);
if ($result->num_rows > 0) {
    // 提取資料並輸出
    $row = $result->fetch_assoc();
    $isbn = $row['ISBN'];
    $name = $row['Name'];
    $author = $row['Author'];
    $version = $row['Version'];
}else {
    echo "error";
    exit;
}

// 在reservation中查詢該書，以確認是否有人預借
$sql = "SELECT * FROM reservation WHERE UNIX_TIMESTAMP(date) IN ( SELECT MIN(UNIX_TIMESTAMP(date)) FROM reservation WHERE B_no IN
(SELECT Sys_no FROM book WHERE isbn = '$isbn' AND Name = '$name' AND Author = '$author' AND Version = '$version'))";
$result = $conn->query($sql);
if ($result->num_rows > 0){
    //取出第一個預借此書的reader的id
    $Ridsql = "SELECT R_id AS readerID FROM reservation WHERE UNIX_TIMESTAMP(date) IN ( SELECT MIN(UNIX_TIMESTAMP(date)) 
    FROM reservation WHERE B_no = '$sysno')";
    $RidsqlResult = $conn->query($Ridsql);
    $readerID = $RidsqlResult->fetch_assoc()["readerID"];
    //把書給這個人
    $sql = "UPDATE book SET R_id = '$readerID', Borrow_date = CURRENT_TIMESTAMP WHERE Sys_no = '$sysno'";
    //並刪除預借紀錄
    $paramsql = "DELETE FROM reservation WHERE R_id = '$readerID' AND B_no = '$sysno'";

    if ($conn->query($sql) === TRUE && $conn->query($paramsql) === TRUE) {
        echo "success";
    } 
}else{
    // 找出要還的書
    $sql = "SELECT * FROM book WHERE Sys_no = '$sysno'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $bookId = $row["Sys_no"];
        // 更新書籍資訊(還書)
        $sql = "UPDATE book SET R_id = NULL , Borrow_date = NULL WHERE Sys_no = '$bookId'";
        
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } 
    } 
}


// 關閉資料庫連接
$conn->close();
?>
