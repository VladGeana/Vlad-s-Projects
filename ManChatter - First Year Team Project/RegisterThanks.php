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
<h2>Thank you for your registration!</h2>

<?php
require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

if($mysqli -> connect_error)
{
die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}

//Validate email
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (empty($_POST["name"]) or empty($_POST["firstname"]) or empty($_POST["surname"]) or empty($_POST["email"]) or empty($_POST["password"])){
  echo "Please enter a username, your firstname, surname, email and a password.";
}
//email validation taken from w3schools tutorial (modified slightly)
else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  echo "Invalid email format.";
}
else{
  $firstname=$_POST["firstname"];
  $surname=$_POST["surname"];
  $username=$_POST["name"];
  $email=$_POST["email"];
  $password=hash( "sha256", $_POST["password"]);
  $usertype=$_POST["usertype"];
  TestUpdateDatabase($username,$firstname,$surname,$email,$password,$usertype,$mysqli);
  echo "Your email is: ".$email;
}
}
//Checking to see if email is in database.
function TestUpdateDatabase($username,$firstname,$surname,$email,$password,$usertype,$mysqli){
//select record containing the entry (if one exists)
$sql="SELECT Email , Username FROM Users
           WHERE email='$email' OR Username ='$username' ";
$result = mysqli_query($mysqli, $sql);
$sql2="SELECT Email FROM Users
           WHERE Email ='$email' ";
$result2 = mysqli_query($mysqli,$sql2);
//if an entry does not exist:
if ($result->num_rows <= 0 ){
  $sql="INSERT INTO Users (FirstName,Surname,Email,Username,PasswordHash,StudentStatus)
             VALUES('$firstname','$surname','$email','$username','$password',$usertype) ";
   if ($mysqli->query($sql) === TRUE) {
      echo "You have successfully registered<br>
      you will still need to log in to ask a question<br>";
    } else {
      echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
  }
  else if($result2->num_rows <= 0 ){
      echo "I'm sorry, this username is already taken.";
  }
  else{
      echo "You already have an account! Login using the sidebar.";
  }
}
$mysqli->close();

?>

<p>You can go back to our <a href="HomePage.php">Home page</p>
</div>

<div id="bottomNav"></div>
</body>
</html>
