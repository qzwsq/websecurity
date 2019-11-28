<?php
require_once "includes/Global.php";
include("includes/Token.class.php");
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
    <title>转账</title>
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
            <a href="users.php" class="Users">
                Users
            </a>&nbsp;&nbsp;&nbsp;&nbsp;|
            <a href="transfer.php" class="Transfer" style="text-decoration: none;color: red;">
                Transfer
            </a>
        </nav>
        <div class="func send">

            <?php
            //添加token用于防护CSRF
            $token = new Token();
            $tokenName = "transfer";
            $tokenName = "transferProtector".date("h:i:sa");
            $TookenArr = $token->getToken($tokenName);
            $personID = $user->personID;
            $sql = "SELECT Coins FROM Person WHERE PersonID= '$personID'";
            $rs = $conn->query($sql);
            $rs = mysqli_fetch_array($rs);
            $balance = $rs["Coins"];
            $balance = $balance > 0 ? $balance : 0;
            ?>
            <?php
            if (isset($_POST['transfer_coins'])) {
                //此处为CSRF防御的判断
                //Token一致方可进行转账操作，否则表单无效
//                if (@$_POST[$tokenName] == $_SESSION[$tokenName]) {
                $sendAmount = $_POST["sendAmount"];
                //若转账金额比余额大，则不能转账
                if ($sendAmount > $balance) {
                    $message = "账户余额不足";
                }elseif ($sendAmount <= 0){
                    $message = "违规操作，转账金额应大于0";

                }   else {
                    $receiver = $_POST['receiver'];
                    $sql = "SELECT * FROM Person WHERE Username= '$receiver'";
                    $rs = $conn->query($sql);
                    $rs = mysqli_fetch_array($rs);
                    if ($rs) {
                        $receiverID = $rs["PersonID"];
                        //拒绝自己给自己转账
                        if ($receiverID != $personID) {
                            //从转出者账户扣除转出金额
                            $balance = $balance - $sendAmount;
                            $sql = "UPDATE Person SET Coins = $balance " .
                                "WHERE PersonID = '$personID'";
                            $conn->query($sql);
                            //给接收者账户金额加上转入金额
                            $sql = "SELECT Coins FROM Person " . "WHERE Username= '$receiver'";
                            $rs = $conn->query($sql);
                            $rs = mysqli_fetch_array($rs);
                            $receiverBalance = $rs["Coins"];
                            $receiverBalance = $receiverBalance + $sendAmount;
                            $sql = "UPDATE Person SET Coins = $receiverBalance " .
                                "WHERE PersonID = '$receiverID'";
                            $conn->query($sql);
                            $message = "转给$receiver 了 $sendAmount 个币";
                        } else {
                            $message = "不允许自己给自己转账";
                        }
                    } else {
                        $message = "转账对象不存在";
                    }
                }
//          此处为CSRF防御的判断结尾的花括号
//                }
            }
            ?>
            <h4>Account Balance: <?php echo $balance ?> Coins</h4>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" style="padding-left: 80px;">
                <p>Send <input type="text" class="SendAmount" name="sendAmount" id="sendAmount" autocomplete="off">
                    conins</p>
                <p>To &nbsp;&nbsp;<input type="text" class="SendUser" name="receiver" id="receiver"
                                                     autocomplete="off"></p>
                <input type="hidden" name="<?php echo $TookenArr['name'] ?>" value="<?php echo $TookenArr['value'] ?>">
                <input type="submit" value="转账" name="transfer_coins"
                       style="margin-left: -200px;width: 80px;height: 40px;">
            </form>
            <br>
            <span class=warning>
                <?php
                if (isset($message)) {
                    echo "$message";
                }
                ?></span>
        </div>
    </div>
</div>
</body>
</html>
