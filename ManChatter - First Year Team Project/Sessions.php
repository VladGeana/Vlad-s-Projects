<?php
  if(isset($_SESSION["UserID"]))
  {
     if((time() - $_SESSION['last_login_timestamp']) > 60) // 900 = 15 * 60
     {
        //log out the user from the system
        header("location:logout.php");
     }
     else
     {
        //update the time
        $_SESSION['last_login_timestamp'] = time();
     }
  }
?>
