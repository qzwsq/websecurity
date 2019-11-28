<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/common.css">
    <link rel="shortcut icon" href="img/icon.ico"/>
    <title>登陆</title>
</head>
<body>
<div class="takeplace"></div>
<div class="content">
    <!--浮动的校徽-->
    <div class="logo">
        <img src="img/logo.jpg" class="logo"/>
    </div>
    <div class="header">
        <h1>USTC Web Security Channel</h1>
        <h3>Designed for Web Security Courses</h3>
    </div>
    <div class="clearfloat"></div>

    <div class="main">
        <div class="func">
            <form action="includes/Global.php" method="POST" class="loginform">
                用户名：<input type="text" name="username" required="required" placeholder="请输入用户名">
                <br>
                <br>
                密码：&nbsp;&nbsp;<input type="password" name="password" required="required"
                                                  placeholder="请输入密码">
                <br>
                <br>
                <p class="funcbtn">
                    <input type="submit" value="登陆" name="submit_login"> &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="submit" value="注册" name="submit_registration">&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="submit" value="安全登陆" name="safe_login">
                </p>
            </form>
        </div>
        <?php
        if (isset($_COOKIE['errorMessage'])) {
            setcookie("errorMessage", "nothing", time() - 2000, '/');
            echo "<h4 style='color: red'>" . $_COOKIE["errorMessage"] . "</h4>";
        }
        ?>
    </div>
</body>
</html>
