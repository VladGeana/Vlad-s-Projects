<?php
// Start the session
session_start();

$dir = "uploads/";
  if(!file_exists($dir))
    mkdir($dir);

require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}
//$userID = (int)$_SESSION['UserID'];

//$search=$mysqli -> query("SELECT * FROM Users
  //                 WHERE UserID='$userID'");

//while($row=mysqli_fetch_array($search))
//{
//   $link = $row["PictureLink"];
//   move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $dir . $link;)
//}

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

<!--Link to the Home page after the user logs in-->

<?php
  require_once('config.inc.php');
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

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

$username="";
$password="";
$email="";
if(!empty($_POST["uname"]))
  $username=$_POST["uname"];
if(!empty($_POST["psw"]))
  $password=hash("sha256", $_POST["psw"]);
if(!empty($_POST["email"]))
  $email=$_POST["email"];

  $search=$mysqli -> query("SELECT * FROM Users
                     WHERE Username='$username' OR Email='$email'");

  $OK=0;
  $status=0;

  while($row=mysqli_fetch_array($search))
  {
    $status = $row["StudentStatus"];

    if ($status != 3 && $password == $row["PasswordHash"])
    {
      $OK=1;
      $username=$row["Username"];

      $_SESSION["UserID"] = $row["UserID"];
      //stores the time that the user last logged in
      $_SESSION['last_login_timestamp'] = time();
    }
  }

  //$query = mysqli_query($mysqli, "SELECT StudentStatus FROM Users WHERE Username = '$username'");

  if ($status == 3)
  {
    echo "<br>";
    echo "<p>";
    echo "<font color=red>";
    echo "Your account has been blocked from using this website.";
    echo "<br>";
    echo "</p>";
  }
  else if($OK==1)
  {
    echo "<br><br>";
    echo "Hello, ";
    echo "<font color=orange>";
    echo "$username";
    echo " !";
    echo "<h2>Thank you for logging in!</h2>";
  }
  else
  {
    echo "<br>";
    echo "<p>";
    echo "<font color=red>";
    echo "Login incorrect";
    echo "<br>";
    echo "</p>";

    echo "Try again";
  }

$mysqli -> close();
?>

<p>
  <font color=black>You can go back to our
  <a href="HomePage.php">Home page
</p>
</div>

<div id="bottomNav"></div>
</body>
</html>
