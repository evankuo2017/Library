# Library

1. **Clone 專案並解壓縮 phpMyAdmin**
2. **安裝 WampServer**
   - 下載 **WampServer**（版本 3.3.0 x64 以上皆可）
   - 若缺少 **Visual C++** 套件，請至官網搜尋錯誤訊息上的四個執行檔並安裝
3. **將專案放入 WampServer**
   - 下載完成後，將整個專案壓縮成 `SEP` 資料夾，放置於 `wamp/www/`
4. **啟動 WampServer**
   - 透過瀏覽器訪問：
     - `localhost/SEP` 查看網頁首頁
     - `localhost/SEP/phpmyadmin` 查看資料庫管理頁面
5. **更改 MySQL root 密碼**
   - 參考 `密碼問題.txt`
6. **建立 Library 資料庫**
   - 成功登入後，新增 **library** 資料庫
   - 從 `SEP` 中匯入 `library.sql`

