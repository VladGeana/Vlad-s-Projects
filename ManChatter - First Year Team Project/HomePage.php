<?php
  // Start the session
  session_start();
  // Get the category of questions to display, 0 = any
  $categoryNumber = 0;
  if (isset($_GET['cat'])) {
    $categoryNumber = $_GET['cat'];
  }
  $pageNumber = 0;
  $transition = 0;
  if (isset($_POST['pageNumber']) && $_POST['submit'] == 'Next >>>')
  {
    $pageNumber = $_POST['pageNumber'];
    $transition = 1;
  }
  else if (isset($_POST['pageNumber']) && $_POST['submit'] == '<<< Previous')
  {
    $pageNumber = $_POST['pageNumber'];
    $transition = - 1;
  }
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
  $('#trending').load(
    "Trending.php",
    {
        'categoryNumber': '<?php echo $categoryNumber; ?>',
        'pageNumber': '<?php echo $pageNumber; ?>',
        'transition': '<?php echo $transition; ?>',
    }
  );
  $("#latest").load(
    "Latest.php",
    {
        'categoryNumber': '<?php echo $categoryNumber; ?>',
        'pageNumber': '<?php echo $pageNumber; ?>',
        'transition': '<?php echo $transition; ?>',
    }
  );
  $("#categories").load("Categories.php");
  $("#session").load("Sessions.php")
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

function openTab(tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");

  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
  setTrending();
}

function setTrending(){
    document.getElementById("trendingButton").style.backgroundColor = "#d6ccdd";
    document.getElementById("latestButton").style.backgroundColor = "inherit";

}
function setLatest(){
    document.getElementById("latestButton").style.backgroundColor = "#d6ccdd";
    document.getElementById("trendingButton").style.backgroundColor = "inherit";
}

</script>

<style>
/* Style the tab */
.tab {
  overflow: hidden;
  border-radius: 5px;
  border: 1px solid #ccc;
  background-color: rgb(240, 240, 240);
  float: left;
  width: 40%;
    margin-left: 15%;

  margin-bottom: 20px;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  color: #000000;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #d6ccdd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #d6ccdd;
}

/* Style the tab content */
.tabcontent {
  display: block;
  border-top: none;
        margin-left: 30px;
}
</style>

<body>

<div id="overlay"></div>

<!-- Logo -->
<div id="logo"></div>

<!-- Top navigation bar -->
<div id="topnav" class="topnavEx"></div>

<!-- Side navigation -->
<div id="sidenav"></div>

<!-- allows the side bar to be linked to the image -->
<div id="session"></div>

<div id="main">

<div class="tab">
  <button class="tablinks" onclick="setTrending(); openTab('trending');" style="background-color: #d6ccdd;" id = "trendingButton">Trending</button>
  <button class="tablinks" onclick="setLatest(); openTab('latest');" id = "latestButton">Latest</button>
</div>

  <div id="trending" style="display:block;" class="tabcontent"></div>
  <div id="latest" class="tabcontent" style="display:none;"></div>
  <?php include('Categories.php'); ?>

</div>

<div id="bottomNav"></div>


</body>
</html>
