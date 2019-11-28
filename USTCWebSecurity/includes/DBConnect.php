<?php
define("HOST", "mysql");
define("USER", "root");
define("PASS_WORD", "123456");
define("DB_NAME", "websecurity");

function getConnection(){
    return mysqli_connect(HOST,USER,PASS_WORD, DB_NAME);
}
