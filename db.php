<?php
// 建立資料庫連接
$servername = "localhost"; // 資料庫伺服器名稱
$username = "root";
$password = "12345678";
$dbname = "library"; // 資料庫名稱

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接資料庫失敗：" . $conn->connect_error);
}

?>