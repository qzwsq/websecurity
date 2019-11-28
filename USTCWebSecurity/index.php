<?php
require_once "includes/Global.php";
require_once "includes/Token.class.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/common.css">
    <link rel="shortcut icon" href="img/icon.ico"/>

    <title>首页</title>
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
        <!--登出按钮-->
        <form action="includes/Global.php" name="form1" method="post">
            <input type="submit" name="logout" value="<?php
            if (isset($user->username))
                echo $user->username . " -";
            ?>登出" class="logout" ">
        </form>
    </div>
    <div class="clearfloat"></div>

    <div class="main">
        <nav class="nav">
            <a href="index.php" class="Home" style="text-decoration: none;color: red;">
                Home
            </a>&nbsp;&nbsp;&nbsp;&nbsp;|
            <a href="users.php" class="Users">
                Users
            </a>&nbsp;&nbsp;&nbsp;&nbsp;|
            <a href="transfer.php" class="Transfer">
                Transfer
            </a>
        </nav>
        <div class="func">
            <form method="POST" name="profileform"
                  action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <?php
                //                    添加token用于防护CSRF
                $token = new Token();
                $tokenName = "profileProtector".date("h:i:sa");
                $TookenArr = $token->getToken($tokenName);

                //设置数据库读取格式为uft-8，避免乱码
                $conn->query("SET NAMES utf8");
                $username = $user->username;
                $personID = $user->personID;



                $sql = "SELECT Profile,Coins FROM Person WHERE  PersonID = '$personID'";
                $rs = $conn->query($sql);
                $rs = mysqli_fetch_array($rs);
                $balance = $rs["Coins"];
                $profile = $rs['Profile'];
                //                   若profile"保存"按钮被按下
                if (isset($_POST['profile_submit'])) {
//                        此判断为profile保护，判断token是否一致,可抵御CSRF攻击
//                        撤销保护将if和其对应的大括号注释掉即可
//                        if (@$_POST[$tokenName] == $_SESSION[$tokenName]) {
                    $profile = $_POST['profile'];
                    $sql = "UPDATE Person SET Profile= '$profile'" .
                        "WHERE PersonID = '$personID'";
                    $conn->query($sql);
//                      此处为使用token防护profile部分的结束花括号
//                        }
                    $sql = "SELECT * FROM Person WHERE  PersonID = '$personID'";
                    $rs = $conn->query($sql);
                    $rs = mysqli_fetch_array($rs);
                    $balance = $rs["Coins"];
                    $profile = $rs['Profile'];
                }
                ?>
                <h4>Account Balance: <?php echo $balance ?> coins</h4>
                <label>Profile</label><br>
                <textarea name="profile" class="profile" cols="30" rows="10"><?php echo $profile ?></textarea><br>
                <input type="submit" value="保存" name="profile_submit">
                <input type="hidden" name="<?php echo $TookenArr['name'] ?>" value="<?php echo $TookenArr['value'] ?>">
            </form>

        </div>

    </div>

</div>
</body>
</html>