<?php
session_start();

// 清除管理員使用者名稱的 Session 資料
unset($_SESSION["username"]);
session_destroy();

// 返回成功的回應
http_response_code(200);
?>
