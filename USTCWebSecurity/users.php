<?php
require_once "includes/Global.php";
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
    <title>用户查询</title>
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
            <a href="index.php" class="Home">
                Home
            </a>&nbsp;&nbsp;&nbsp;&nbsp;|
            <a href="users.php" class="Users" style="text-decoration: none;color: red;">
                Users
            </a>&nbsp;&nbsp;&nbsp;&nbsp;|
            <a href="transfer.php" class="Transfer">
                Transfer
            </a>
        </nav>
        <div class="func">
            <form method="POST" name="userform"
                  action="<?php echo $_SERVER['PHP_SELF'] ?>" style="margin-top: 50px;">
                User: <input type="text" name="searched_user" required=required>
                <input type="submit" value="查询" name="search_user">
                </nobr>
                <br>
                <?php
                //设置数据库读取格式为uft-8，避免乱码
                $conn->query("SET NAMES utf8");
                if (isset($_POST['search_user'])) {
                    $balance = 0;
                    $username = null;
                    $searched_user = $_POST['searched_user'];
                    //           下面为对SQL注入进行防护的查询操作
                    if ($stmt = $conn->prepare("select Username,Coins,Profile from Person where Username=?")) {
                        $stmt->bind_param('s', $searched_user);
                        $stmt->execute();
                        $stmt->bind_result($username, $balance, $profile);
                        $successQuery = $stmt->fetch();
                        $stmt->close();
                    }
                    if ($successQuery) {
//           添加防护的代码至此截止

//           下面为未对SQL注入进行防护的查询操作
//                   $sql = "SELECT * FROM Person ".
//                      "WHERE Username = '$searched_user'";
//                   $rs = $conn->query($sql);
//                   $rs = mysqli_fetch_array($rs);
//                   if ($rs){
//                        $username = $rs["Username"];
//                        $balance = $rs['Coins'];
//                        $profile = $rs['Profile'];
//              未添加防护代码至此截止

                        echo "<p id=\"profileheader\" <br><hr></p>";
                        echo "<div class=profilecontainer><b>Profile</b>";
                        $allowed_tags =
                            '<a><br><b><h1><h2><h3><h4><i><img><li><ol><p><strong><table>' .
                            '<tr><td><th><u><ul><em><span><script>';
                        $profile = strip_tags($profile, $allowed_tags);
                        $disallowed =
                            'javascript:|window|eval|setTimeout|setInterval|target|' .
                            'onAbort|onBlur|onChange|onClick|onDblClick|' .
                            'onDragDrop|onError|onFocus|onKeyDown|onKeyPress|' .
                            'onKeyUp|onLoad|onMouseDown|onMouseMove|onMouseOut|' .
                            'onMouseOver|onMouseUp|onMove|onReset|onResize|' .
                            'onSelect|onSubmit|onUnload';
                        $profile = preg_replace("/$disallowed/i", " ", $profile);
                        echo "<p id=profile>$profile</p></div>";
                    } else {
                        echo "<br><hr>查询的用户名不存在，请输入有效用户名";
                    }
                    $balance = ($balance > 0) ? $balance : 0;
                    echo "<span id='balance' class='$balance'/>";
                }
                ?>
            </form>
        </div>
        <script type="text/javascript">
            var total = eval(document.getElementById('balance').className);

            function showCoins(balance) {
                document.getElementById("profileheader").innerHTML =
                    "<?php echo $username ?>'s Coins:" + balance;
                if (balance < total) {
                    setTimeout("showCoins(" + (balance + 1) + ")", 100);
                }
            }

            if (total > 0) showCoins(0);  // count up to total
        </script>
    </div>
</div>
</body>
</html>