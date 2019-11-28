<?php

class User
{
    var $username = null;
    var $personID = 0;
    var $conn = null;
    var $cookieName = "Weblogin";

    function __construct(&$conn)
    {
        $this->conn = $conn;
        if (isset($_COOKIE[$this->cookieName])) {
            $this->checkRemember($_COOKIE[$this->cookieName]);
        }
    }

    //利用cookie登陆
    function checkRemember($cookie)
    {
        $arr = unserialize(base64_decode($cookie));
//        从cookie中得到用户名和token
        list($username, $token) = $arr;
        if (!$username or !$token) {
            return;
        }
//      校验  username 和 token 是否和数据库内的一致，一致方可成功登入
        $sql = "SELECT * FROM Person WHERE " .
            "(Username = '$username') AND (Token = '$token')";
        $rs = $this->conn->query($sql);
        $rs = mysqli_fetch_array($rs);
        if ($rs) {
            $this->personID = $rs["PersonID"];
            $this->username = $rs["Username"];
        }
    }

    function validate_login()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $sql = "SELECT `Salt` FROM `Person` WHERE `Username` = '$username'";
        $result = $this->conn->query($sql);
        $rs = mysqli_fetch_array($result);
        //查询返回为null的话，即用户名不存在
        if (!$rs) {
            setcookie("errorMessage", "用户不存在", time() + 2000, '/');
            header("Location:../login.php");
            return;
        }
        //查询当前用户的盐值
        $salt = $rs["Salt"];
	$hashedpassword = md5($password . $salt);
	error_log("error is on the road, md5:");
	error_log("'$hashedpassword'");
        $sql = "select * from Person where Username = '$username' and Password = '$hashedpassword'";
        $result = $this->conn->query($sql);
        $rs = mysqli_fetch_assoc($result);
        //若查询为空，则密码不对
        if (!$rs) {
            setcookie("errorMessage", "密码错误", time() + 2000, '/');
            header("Location:../login.php");
            return;
        }
        $this->_updateToken($rs);
        header("Location:../index.php");
    }

    function safe_login()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $stmt = $this->conn->prepare("select Salt from Person where Username=?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($salt);
        $successQuery = $stmt->fetch();
        $stmt->close();

        //查询成功，即用户名有对应Salt，则继续比对密码进行登陆
        if ($successQuery) {
            $hashedpassword = md5($password . $salt);
            $stmt = $this->conn->prepare("select PersonID from Person where Username=? and Password = ?");
            $stmt->bind_param('ss', $username, $hashedpassword);
            $stmt->execute();
            $stmt->bind_result($personID);
            $successQuery = $stmt->fetch();
            $stmt->close();
            //用户名和密码能查出对应的PersonID，则用户名密码匹配且有效
            if ($successQuery) {
                $sql = "SELECT * FROM Person WHERE Username = '$username'";
                $result = $this->conn->query($sql);
                $rs = mysqli_fetch_assoc($result);
                $this->_updateToken($rs);
//                    echo "<script type='text/javascript'> alert(\"登陆成功！！\");location.href = \"../index.php\";</script>";

                header("Location: ../index.php");
            } //查不出PersonID则密码不对，但用户名是有效的
            else {
                setcookie("errorMessage", "密码错误", time() + 2000, '/');
                header("Location:../login.php");
                return;
            }
        } //查询失败，即对应用户名无Salt，即用户名无效
        else {
            setcookie("errorMessage", "用户不存在", time() + 2000, '/');
            header("Location:../login.php");
            return;
        }
    }

    function validate_registration()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        //查询用户名是否可用
        $sql = "SELECT PersonID FROM Person WHERE Username='$username'";
        $rs = $this->conn->query($sql);
        $rs = mysqli_fetch_array($rs);
        //检测用ID，若用户名已存在则ID非零
        if ($rs) {
            setcookie("errorMessage", "用户名已被占用,注册失败！", time() + 2000, '/');
            header("Location:../login.php");
            return;
        }
        $salt = substr(md5(rand()), 0, 4);
        $hashedpassword = md5($password . $salt);
        $sql = "INSERT INTO Person (Username, Password, Salt) " .
            "VALUES ('$username', '$hashedpassword', '$salt')";
        $this->conn->query($sql);
//        echo "<script type='text/javascript'> alert(\"注册成功！\");</script>";
        $this->validate_login();
    }

    function logout()
    {
        setcookie($this->cookieName, "nothing", time() - 3600, "/");
        $this->personID = 0;
        $this->username = null;
//        session_destroy();
    }

    function _setCookie($token)
    {
//        用一个数组保存用户名和token
        $arr = array($this->username, $token);
//        封装该数组，并base64编码之，结果命名为$cookieData
        $cookieData = base64_encode(serialize($arr));
//        将$cookieData写入cookie中存起来，设置为全项目有效
        setcookie($this->cookieName, $cookieData, time() + 31104000, "/");
    }

    function _updateToken($values)
    {
        $this->personID = $values["PersonID"];
        $this->username = $values["Username"];
//      MD5运算“密码+随机数”产生token，
        $token = md5($values["Password"] . mt_rand());
//        设置cookie，cookie中有密码
        $this->_setCookie($token);
//        将token写入数据库
        $sql = "UPDATE Person SET Token = '$token' " .
            "WHERE PersonID = $this->personID";
        $this->conn->query($sql);
    }
}

?>
