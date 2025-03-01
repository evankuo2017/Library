<?php
include('db.php');

// 確認是否收到要刪除的 Reader ID
if (isset($_GET["id"])) {
    
    $id = $_GET["id"];
    error_log("Received id: " .$id);

    // 刪除 Reader 資料
    $sql = "DELETE FROM reader WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();

    // 重定向回管理員控制頁面
    header("Location: admin_dashboard.php");
    exit();
} else {
    error_log("No id received");
}

// 關閉資料庫連接
$conn->close();
?>
