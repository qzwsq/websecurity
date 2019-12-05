<?php

// Cookie-based authentication logic

class User {
  var $db = null;
  var $id = 0; // the current user's id
  var $username = null;
  var $cookieName = "ZoobarLogin";

  function User(&$db) {
    $this->db = $db;
    if ( isset($_COOKIE[$this->cookieName]) ) {
      $this->_checkRemembered($_COOKIE[$this->cookieName]);
    }
  } 

  function _checkLogin($username, $password) {
    $sql = "SELECT Salt FROM Person WHERE Username = '$username';";
    $rs = $this->db->executeQuery($sql);
    $rs = mysqli_fetch_array($rs);
	
	$salt=$rs["Salt"];
	$hashedpassword = md5($password . $salt);
  
	$sql = "select * from Person where Username = '$username' and Password = '$hashedpassword';";
    $result = $this->db->executeQuery($sql);
      $result = mysqli_fetch_array($result);
	   if($result){
	      $this->_setCookie($result, true);
      return true;
    } else {
      return false;
    }
  } 
	
  function _addRegistration($username, $password) {
    $sql = "SELECT PersonID FROM Person WHERE Username='$username';";
    $rs = $this->db->executeQuery($sql);
    $rs = mysqli_fetch_array($rs);
    
    $salt = substr(md5(rand()), 0, 4);
    $hashedpassword = md5($password . $salt);
    $sql = "INSERT INTO Person (Username, Password, Salt) " .
	    "VALUES ('$username', '$hashedpassword', '$salt');";
    $this->db->executeQuery($sql);
	return $this->_checkLogin($username, $password);
  }
	
  function _logout() {
    if(isset($_COOKIE[$this->cookieName])) setcookie($this->cookieName);
    $this->id = 0;
    $this->username = null;
  }

  function _setCookie(&$values, $init) {
    
	
	$this->id = $values["PersonID"];
      $this->username = $values["Username"];
  	$token = md5($values["Password"].mt_rand());

    $this->_updateToken($token);
    $session = session_id();
    $sql = "UPDATE Person SET Token = '$token' " .
           "WHERE PersonID = $this->id;";
    $this->db->executeQuery($sql);
  } 

  function _updateToken($token) {
    $arr = array($this->username, $token);
    $cookieData = base64_encode(serialize($arr));
	
    setcookie($this->cookieName, $cookieData, time() + 31104000);
  }
	
  function _checkRemembered($cookie) {
    $arr = unserialize(base64_decode($cookie));
    list($username, $token) = $arr;
    if (!$username or !$token) {
      return;
    }
    $sql = "SELECT * FROM Person WHERE " .
           "(Username = '$username') AND (Token = '$token')";
    $rs = $this->db->executeQuery($sql); 
    $rs = mysqli_fetch_array($rs);
	
   	if($rs){
      	$this->id = $rs["PersonID"];
	$this->username = $rs["Username"];

    }
  } 
}
?>
