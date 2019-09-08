<!DOCTYPE html>
<html lang = en>
<head>
  <title>Manchatter</title>
  <link rel="stylesheet" type="text/css" href="ourstyle.css">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1">

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
  $("#date").load(
    "Date.php",
    {
      'id': '<?php echo $_GET['id']; ?>',
    }
  );
  $("#vote").load(
    "Vote.php",
    {
      'id': '<?php echo $_GET['id']; ?>',
    }
  );
  //$("#theQ").load("TheQuestion.php");
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

function box()
{
  var x = this.id;
  alert(x);
}

function showReply(id)
{
  var forms = document.getElementsByClassName("replyForm");
  var i;
  for(i = 0; i < forms.length; i++)
  {
    if(forms[i].id == id)
    {
      if(forms[i].style.display == "none")
      {
        forms[i].style.display = "block";
      }
      else if(forms[i].style.display == "block") {
        forms[i].style.display = "none";
      }
    }
  }
}

function show(name)
{

  var i, content, links;
  content = document.getElementsByClassName("content");
  for(i = 0; i < content.length; i++){
    content[i].style.display = "none";
  }

  links = document.getElementsByClassName("links");

  for(i = 0; i < links.length; i++){
    links[i].className = links[i].className.replace(" active", "");
  }

  document.getElementById(name).style.display = "block";
  evt.currentTarget.className += " active";


}
document.getElementById("defaultOpen").click();

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function()
{
  $(".answerLikes").click(function()
  {
     alert("anything");
    /*var answerID = $(this).attr('class');

    $.ajax(
    {
      type: 'GET',
      url: 'answerLikes.php',
      data: { 'id': answerID , 'userID': < echo $_SESSION['UserID'];? ,},
      success: function(result){alert('ok');},
      error: function(result){alert('error');}
    });*/
  });
});


  /*$(".answerDislikes").click(function(e)
  {
    e.preventDefault();
    $.ajax(
    {
      type: "GET",
      url: "answerDislikes.php",
      data: {id: $(this).val()},
      success: function(result){alert('ok');},
      error: function(result){alert('error');}
    });
  });



  $(".commentLikes").click(function(e)
  {
    e.preventDefault();
    $.ajax(
    {
      type: "GET",
      url: "answerLikes.php",
      data: {id: $(this).val()},
      success: function(result){alert('ok');},
      error: function(result){alert('error');}
    });
  });



  $(".commentDislikes").click(function(e)
  {
    e.preventDefault();
    $.ajax(
    {
      type: "GET",
      url: "answerDislikes.php",
      data: {id: $(this).val()},
      success: function(result){alert('ok');},
      error: function(result){alert('error');}
    });
  });*/


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
  <!-- Part of the page containing the question -->
  <div id="QandUser">
    <div id="UserInfo">
      <?php
        $questionID = (int)$_GET['id'];

        require_once('config.inc.php');
        $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

        if($mysqli -> connect_error)
        {
         die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
        }

        $search=$mysqli -> query("SELECT * FROM Questions WHERE QuestionID = $questionID");

        while($row=mysqli_fetch_array($search))
        {
          $userID = $row["UserID"];
          $userLookUp = $mysqli -> query("SELECT * FROM Users WHERE UserID = $userID");
          $user = mysqli_fetch_array($userLookUp);
          $username = $user["Username"];
          $date = $row["Date"];
          echo "<h4>$username</h4><br>";
          echo "$date<br>";
        }
      ?>
    </div>
    <div id="theQ">
      <?php
        $questionID = (int)$_GET['id'];

        require_once('config.inc.php');
        $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

        if($mysqli -> connect_error)
        {
         die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
        }

        $search=$mysqli -> query("SELECT * FROM Questions WHERE QuestionID = $questionID");

        while($row=mysqli_fetch_array($search))
        {
          $questionTitle = $row["QuestionTitle"];
          $questionText = $row["QuestionText"];
          echo "<h4>$questionTitle</h4><br>";
          echo "$questionText<br>";
        }
      ?>
    </div>
  </div>

<form action="QuestionPage.php" method="get">
  <input type="hidden" class="id" name="id" value='<?php echo $questionID; ?>'>
  <textarea class="newAnswer" name="newAnswer"></textarea><br>
  <input type="submit" class="submit" value="Submit">
</form>

<?php
  require_once('config.inc.php');
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

  if($mysqli -> connect_error)
  {
    die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }

  if ( isset( $_SESSION['UserID'] ) && isset( $_GET["newAnswer"] ))
  {
    $answer =(string) $_GET["newAnswer"];
    $userID = (int) $_SESSION['UserID'];
    $questionID = (int)$_GET['id'];

    $today = new DateTime();
    $today = date("Y-m-d H:i:s");

    $string = "";

    $sql = "INSERT INTO `Answers` (`AnswerID`, `QuestionID`, `UserID`,
            `Answer Text`, `Date`, `NumberOfReports`, `Banned`, `Likes`, `Dislikes`)
            VALUES (NULL, '$questionID', '$userID','$answer','$today','0','0','0','0')";
    if ($mysqli->query($sql) === TRUE) { }
    else
    {
      echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
  }
 ?>

<div id="answers">

  <button class="button links" onclick="show('date')" id="defaultOpen">Date</button>
  <button class="button links" onclick="show('vote')">Vote</button>

  <div id="date" class="content"></div>
  <div id="vote" class="content" style="display:none;"></div>

</div>

</div> <!-- main div close -->
<div id="bottomNav"></div>
</body>
</html>
