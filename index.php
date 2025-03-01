<!DOCTYPE html>
<HTML>
<HEAD>
<TITLE>圖書線上系統</TITLE>
<style>
    <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .container {
            text-align: center;
    }

    h1 {
            margin-bottom: 20px;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-right: 10px;
        opacity: 0.8;
        transition:0.3s;
    }
    #middle{
	position: absolute;
    top:50%;
    left:50%;
    transform:translateX(-50%) translateY(-50%);
    }
    .box{
    width: 200px;
    height: 200px;
    background: #ecd6c7;
    margin: 0 auto;/*區塊置中*/
    }
    #bottom{
    position: absolute;
	width: 100%;
	height: 50px;
	bottom: 0px;
    text-align: center;
    margin: 0px;
    background-color:#1c2222;
    }

    .btn:hover{
        opacity:1;
        transform:scale(1.2);
    }
</style>
</HEAD>
<BODY bgcolor="#b6fcf1" style="margin:0px;">


    <div id="middle">
        <img style="display:block; margin:auto;" width=30% src="image\library.png" alt="mine">
        <h1 style="color: #1B2430;text-align:center;">歡迎來到圖書館線上系統</h1>
            <table align="center"  border="0">
                <tr>
                    <td align='center' valign="middle">
                        <a class="btn" href="user_login.php">使用者登入</a>
                    </td>
                    <td align='center' valign="middle">
                        <a class="btn" href="admin_login.php">管理員登入</a>
                    </td>
                </tr>
            </table>
    </div>
    <div id="bottom">
        <p style="color:rgb(124, 209, 184)">資料庫系統專題   組員:江英碩 郭逸凡</p>
    </div>
</BODY>
</HTML>