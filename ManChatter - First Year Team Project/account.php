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

<div id="overlay"></div>

<!-- Logo -->
<div id="logo"></div>

<!-- Top navigation bar -->
<div id="topnav" class="topnavEx"></div>

<!-- Side navigation -->
<div id="sidenav"></div>

<!-- allows the side bar to be linked to the image -->
<div id="main">

  <!-- User profile picture-->
    <h2> Your profile picture </h2>

<?php
require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}
$userID = (int)$_SESSION['UserID'];

$search=$mysqli -> query("SELECT * FROM Users
                   WHERE UserID='$userID'");

while($row=mysqli_fetch_array($search))
{
   $link = $row["PictureLink"];
   echo '<img src=uploads/'.$link.' alt="logo" class="center"
          border="0" style="max-width:250px; max-height:250px;">';
}


?>

  <h4>Upload new profile picture:</h4>
  <form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
  <br>

<!--Output the username and email-->
  <?php

  $userID = (int)$_SESSION['UserID'];
  $search2=$mysqli -> query("SELECT * FROM Users WHERE UserID=$userID");
  while($row=mysqli_fetch_array($search2))
  {
    $username = $row["Username"];
    $email = $row["Email"];
  }
  echo "<h5 style='width: 30%; align: center; margin-left: auto; margin-right: auto;'>Username</h5>
        <p style='margin-top:-20px;'><strong>".$username."</strong></p>

        <h5 style='width: 30%; align: center; margin-left: auto; margin-right: auto;'>Email</h5>
        <p style='margin-top:-20px;'><strong>".$email."</strong></p>";

  ?>


<!--set variables for updating email-->
  <?php
  // define variables and set to empty values
  $emailErr = $passErr =  "";
  $email1 = $email2 = $password = "";
  $empty = $uneven = $invalid = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email1"])) {
      $emailErr = "Email is required";
      $empty = true;
    } else {
      $email1 = test_input($_POST["email1"]);
      // check if e-mail address is well-formed
      if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
        $invalid = true;
        $emailErr = "Invalid email format";
      }
    }

    if (empty($_POST["email2"])) {
      $emailErr = "Email is required";
      $empty = true;
    } else {
      $email2 = test_input($_POST["email2"]);
      // check if e-mail address is well-formed
      if (!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
      }
    }

    if($email1 != $email2)
    {
      $emailErr = "Emails do not match.";
      $uneven = true;
    }

    if (empty($_POST["password"])) {
      $passErr = "Password is required";
      $empty = true;
    } else {
      $set = true;
      $password = test_input($_POST["password"]);
    }
}

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  ?>

     <h4>Update email.</h4>
     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
       Updated e-mail: <input type="text" name="email1" required>*<br><br>
       Confirm updated e-mail: <input type="text" name="email2" required>
       <span class="error">* <?php echo $emailErr;?></span><br><br>
       
       Password: <input type="password" name="password" class = "account" required>
       
       <span class="error">* <?php echo $passErr;?></span><br><br>
       <input type="submit">
       <input type="reset">
   </form>
    <br>

    <?php
    $passSearch=$mysqli -> query("SELECT * FROM Users
                       WHERE UserId='$userID'");

   $OK=0;

    while($row1=mysqli_fetch_array($passSearch))
    {
      if (hash("sha256", $password) == $row1["PasswordHash"])
        $OK=1;
    }

  if($empty==true || $uneven == true ||  $invalid == true)
  {
    echo"Please try again.";
  }
  else if ($OK == 1)
  {
      $userID = (int)$_SESSION['UserID'];
      $sql="UPDATE Users
            SET Email = '$email1'
            WHERE UserID = $userID";
      if ($mysqli->query($sql) === TRUE) {
        echo "New record created successfully<br>";
      } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
      }
      echo "Your new email is: ".$email1;
  }
  else if ($empty = false)
  {
    echo"Incorrect password. Please try again.";
  }
    ?>

<br>
<br>

<br>

<script>
function myFunction() {
  if (confirm(" Press OK to confirm that your account will be deleted."
              +"\nYour personal details will be removed from our website. "
              +"\n                        This cannot be reversed. ")) {
    location.replace("deletedAccount.php")
  }
}
</script>

<button onclick="myFunction()" class = "account">Delete my account</button>
<br>
<br>

<!--Link to the Home page after the user is done with their account-->
<p>
  <font color=black>You can go back to our
  <a href="HomePage.php">Home page
</p>
</div>

<div id="bottomNav"></div>
</body>
</html>
