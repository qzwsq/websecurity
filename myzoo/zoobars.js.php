<?php

   
    require_once("login.php");
    require_once("includes/auth.php");
    require_once("includes/navigation.php");
    require_once("includes/database.class.php");
    
    // Init global variables
    $db = new Database("websecurity");
    $user = new User($db);
    
    if(validate_user($user)) {
?>
    
    var myZoobars = <?php 
          $sql = "SELECT Zoobars FROM Person WHERE PersonID=$user->id";
          $rs = $db->executeQuery($sql);
          $balance = mysqli_fetch_assoc($rs);
          # echo json_encode($balance);
          echo $balance["Zoobars"] > 0 ? $balance["Zoobars"] : 0;
        ?>;
    var div = document.getElementById("myZoobars");
    
    if (div != null)
    {
        div.innerHTML = myZoobars;
    }
<?php
    }
?>
