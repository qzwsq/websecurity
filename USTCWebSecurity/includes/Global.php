<?php
require_once "LoginCheck.php";
require_once "DBConnect.php";

//定义$isLogged用于区分是否已登陆
$isLogged = false;

$conn = getConnection();

$user = new User($conn);

if (isset($_POST["submit_login"])) {
    $isLogged = true;
    $user->validate_login();
} elseif (isset($_POST["submit_registration"])) {
    $isLogged = true;
    $user->validate_registration();
} elseif (isset($_POST["safe_login"])) {
    $isLogged = true;
    $user->safe_login();
}

if (isset($_POST['logout'])) {
    $isLogged = false;
    $user->logout();
    header("location:../login.php");
}

//若无法通过cookie登陆，跳转login.php进行登陆。
if (!isset($_COOKIE[$user->cookieName]) && !$isLogged) {
    header("location:login.php");
}

