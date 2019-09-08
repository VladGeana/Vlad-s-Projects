<?php
session_start();
?>

<a class="active" href="HomePage.php">HOME</a>

<script>
function loginMessage() {
  alert("Please login.");
}

function startDictation3() {

      if (window.hasOwnProperty('webkitSpeechRecognition')) {

        var recognition = new webkitSpeechRecognition();

        recognition.continuous = false;
        recognition.interimResults = false;

        recognition.lang = "en-UK";
        recognition.start();

        recognition.onresult = function(e) {
          document.getElementById('transcript3').value
                                   = e.results[0][0].transcript;
          recognition.stop();
        };

        recognition.onerror = function(e) {
          recognition.stop();
        }
    }
}

</script>
<?php
//Check to see if a user is logged in
if ( isset( $_SESSION['UserID'] ) )
{
  ?>
  <a href="Ask.php">ASK A QUESTION</a>
  <a href="account.php">PROFILE</a>
  <?php
}
else
{
  ?>
  <a onclick="openNav(); loginMessage()">ASK A QUESTION</a>
  <a onclick="openNav(); loginMessage()">PROFILE</a>
  <?php
}
?>

<a href="About.html">ABOUT</a>



<form  method="post" action="SearchAns.php">
  <input id = "transcript3" type="text" placeholder="Search here.." 
                     name= "searchBar" class="searchBar">
  <img onclick="startDictation3()" src="mic.png"/>
  <br>

</form>
