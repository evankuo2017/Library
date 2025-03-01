<?php
include('db.php');

// 確認是否收到要刪除的 Reader ID
if (isset($_GET["book_sys_no"])) {
    
    $book_sys_no = $_GET["book_sys_no"];
    error_log("Received book_sys_no: " .$book_sys_no);

    // 刪除 Reader 資料
    $sql = "DELETE FROM book WHERE Sys_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $book_sys_no);
    $stmt->execute();
    $stmt->close();

    // 重定向回管理員控制頁面
    header("Location: admin_dashboard.php");
    exit();
} else {
    error_log("No book_sys_no received");
}

// 關閉資料庫連接
$conn->close();
?>
