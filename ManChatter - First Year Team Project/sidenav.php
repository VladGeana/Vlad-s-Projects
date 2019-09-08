<script>
   var mouse_position;
   var animating = false;
   //GET MOUSE POSITION
   $(document).mousemove(function (e) {

       if (animating) {
           return;
       }
       mouse_position = e.clientX;

       console.log(mouse_position);
       if (mouse_position <= 20) {
           //SLIDE IN MENU
           animating = true;
           openNav();

       } else if (mouse_position > 300) {
           animating = true;
           closeNav();
           }
               animating = false;
           });
</script>

<?php
session_start();
?>


<?php
//Check to see if a user is logged in
if ( isset( $_SESSION['UserID'] ) )
{

  ?>

    <a href="HomePage.php">
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
                border="0" style="max-width:200px; max-height:200px;">';
      }
      ?>
    </a>

    <?php
    require_once('config.inc.php');
    $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


    if($mysqli -> connect_error)
    {
     die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
    }

    $userID = (int)$_SESSION['UserID'];
    $search=$mysqli -> query("SELECT * FROM Users WHERE UserID=$userID");
    while($row=mysqli_fetch_array($search))
    {
      $username = $row["Username"];
      $email = $row["Email"];
    }
    echo "<h5>User</h5>
          <p style='margin-top:-20px;'><strong>".$username."</strong></p>

          <h5>Email</h5>
          <p style='margin-top:-20px;'><strong>".$email."</strong></p>";

    ?>


<br>

  <a href='logout.php'><font color="red">Click here to log out</font></a>
  <br><br>
  <a href='account.php'><font color="red">Go to my account</font></a>
  <?php
}
else
{
?>

    <a href="HomePage.php">
<img src="beelogo.png" alt="logo" class="center"
     border="0" width="150" height="100">
</a>

   <?php

  echo "<h2 style='font-size: 180%'>Log in</h2>

  <form method='POST' action='LogInThanks.php'>
  <div class='container'>
      <label for='uname'><b>Username</b></label><br>
      <input type='text' placeholder='Enter Username' name='uname' required class = 'uname'><br><br>
      <label for='psw'><b>Password</b></label><br>
      <input type='password' placeholder='Enter Password' name='psw' class ='sidenav' required><br><br>

      <button type='submit'>Login</button>
      <label>
        <input type='checkbox' checked='checked' name='remember'> Remember me
      </label>
    </div>

<br>
<br>

      <span class='psw'><a href='#' style='text-align: center;'>Forgot password?</a></span>
  </div>
</form>

  <!--Link to the registration page if the user does not have an account-->
  <br>Don't have an account?
  <a href='RegisterUser.html'>Register</a>";





}
?>
