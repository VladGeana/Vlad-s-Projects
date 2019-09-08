<?php
session_start();
?>
<!DOCTYPE html>
<html lang = en>
<head>
  <title>Manchatter</title>
  <link rel="stylesheet" type="text/css" href="ourstyle.css">
</head>

<!-- to avoid copy&paste -->
<script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous">
</script>

<script>
$(function(){
  $("#topnav").load("topnav.html");
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

<body>

<div id="overlay"></div>

<!-- Logo -->
<div id="logo"></div>

<!-- Top navigation bar -->
<div id="topnav" class="topnavEx"></div>

<!-- Side navigation -->
<div id="sidenav"></div>

<!-- allows the side bar to be linked to the image -->
<div id="main">
  Thanks for asking a question!
  <p>You can go back to our <a href="HomePage.php">Home page.</p>
<?php

require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass,$group_dbnames[0]);

if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}

if($result = $mysqli -> query("SELECT * FROM Users"))
{
 // printf("Select returned %d rows.\n", $result -> num_rows);
 // echo "<br>";
  $result -> close(); // Remember to release the result set
}
$mysqli -> query("USE $group_dbnames[0]");


  $questionTitle=$_POST["title"];
  $questionText=$_POST["message"];
  $questionCat=$_POST["cat"];
  $questionuserID = $_SESSION["UserID"];

  $mysqli -> query("INSERT INTO Questions(QuestionTitle, QuestionText, UserID,
  Category,Date) VALUES ('$questionTitle','$questionText','$questionuserID',
  '$questionCat',NOW())");

$mysqli -> close();

?>
</div>
<div id="bottomNav"></div>

</body>
</html>
