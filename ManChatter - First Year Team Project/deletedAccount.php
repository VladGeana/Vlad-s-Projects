<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang = en>
<head>
  <title>Manchatter</title>
  <link rel="stylesheet" type="text/css" href="ourstyle.css">

  <!-- to avoid copy&paste -->
  <script
      src="https://code.jquery.com/jquery-3.3.1.js"
      integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
      crossorigin="anonymous">
  </script>

  <script>
  $(function(){
    $("#topnav").load("topnav.php");
    $("#sidenav").load("sidenav.php");
    $("#logo").load("logo.html");
    $("#bottomNav").load("bottomNav.html");
  });
  </script>

  <script>
  /* Set the width of side navigation to 300px on opening */
  function openNav() {
    document.getElementById("sidenav").style.width = "298px";
    //document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    document.getElementById("overlay").style.display = "block";
  }

  /* Set width of the side navigation bar to 0px on closing */
  function closeNav() {
    document.getElementById("sidenav").style.width = "0";
    //document.body.style.backgroundColor = "white";
    document.getElementById("overlay").style.display = "none";
  }
  </script>

</head>

<body>



  <div id="overlay" onclick="closeNav()"></div>

  <!-- Logo -->
  <div id="logo"></div>

  <!-- Top navigation bar -->
  <div id="topnav" class="topnavEx"></div>

  <!-- Side navigation -->
  <div id="sidenav"></div>

  <!-- allows the side bar to be linked to the image -->
  <div id="main">

  <?php
  require_once('config.inc.php');
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


  if($mysqli -> connect_error)
  {
   die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }

  $userID = (int)$_SESSION['UserID'];

  $delete=$mysqli -> query("DELETE  FROM Users
                            WHERE UserID='$userID'");

  session_destroy();
  echo 'Your account has been deleted. <a href="HomePage.php">Go back to the homepage</a>';
  ?>

  </div>


<div id="bottomNav"></div>
</body>
</html>
