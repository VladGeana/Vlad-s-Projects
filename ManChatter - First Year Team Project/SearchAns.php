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
<div id="topnav" class = "topnavEx"></div>

<!-- Side navigation -->
<div id="sidenav"></div>

<!-- allows the side bar to be linked to the image -->
<div id="main">

<?php

  error_reporting(0);
  $text=$_POST['searchBar'];
  echo "<br><br><br>";
  echo "<br>Search results for '";
  echo "$text': <br><br><br><br><br>";


  $keywords = explode(" ", $text);  //create an array of strings
  $keywordsPlural = $keywords;
  $matching = [];
  $ansTitle = [];
  $ansCategory = [];
  $ansText = [];
  $indices = [];  //in the end, this array will contain sorted indices
                  //this is to sort multiple vectors by the same criteria

  for ($i=0; $i<count($keywordsPlural); $i++)
  {
    $word=str_split($keywordsPlural[$i]);
    $len=count($word);

    if($word[$len-1]=='s')
    {
      $newWord="";
      for ($j=0; $j<count($word)-1; $j++)
      {
        $newWord.=$word[$j];
      }
      //add new word at singular form to keywords array
      array_push($keywords,$newWord);
    }
  }
  //now the array we have to search through is 'keywords'

require_once('config.inc.php'); //connect to DB
$mysqli = new mysqli($database_host, $database_user, $database_pass,$group_dbnames[0]);

if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}

if($result = $mysqli -> query("SELECT * FROM Users"))
{
  $result -> close(); // Remember to release the result set
}
$mysqli -> query("USE $group_dbnames[0]");
//connecting to DB ends here

$studentStatusArray = array(
    0    => "Standard",
    1  => "UOM student",
    2  => "Alumni",
    3 => "Banned",
    4  => "Admin",
);

  $str = "SELECT * FROM Questions
                   WHERE ";
  for ($i=0; $i<count($keywords); $i++)
  {
    $str .= "QuestionTitle LIKE '%$keywords[$i]%' OR  Category LIKE '%$keywords[$i]%' OR QuestionText LIKE '%$keywords[$i]%' OR ";
  }

  $str .= "\"";
  $sql = (substr($str,0,-5));
  $search=$mysqli -> query($sql);
  $var=0;




  while($row=mysqli_fetch_array($search))
  {

    $ansTitle[$var] = $row["QuestionTitle"];  //push to ansTitle
    $ansCategory[$var] = $row["Category"];    //push to ansCategory
    $ansText[$var] = $row["QuestionText"];    //push to ansText
    $ansDate[$var] = $row["Date"];
    //Find userID and questionID to find further info about the questions
    $questionID = $row["QuestionID"];
    //Initialise variables we are searching for
    $noOfComments = 0;
    $studentStatus = "";
    $userID = $row["UserID"];
    // Find details about user who wrote the question
    $search2=$mysqli -> query("SELECT * FROM Users WHERE UserID='$userID'");
    while($row2=mysqli_fetch_array($search2))
    {
      $username = $row2["Username"];
      $studentStatusNo = (int)$row2["StudentStatus"];
      $studentStatus = $studentStatusArray[$studentStatusNo];
    }
    // Find number of answers and comments
    $search3=$mysqli -> query("SELECT * FROM Answers WHERE      	QuestionID='$questionID'");
    while($row3=mysqli_fetch_array($search3))
    {
      $answerID = $row3["AnswerID"];
      $noOfComments++;
      $search4=$mysqli -> query("SELECT * FROM Comments WHERE AnswerID='$answerID'");
      while($row4=mysqli_fetch_array($search4))
      {
        $noOfComments++;
      }
    }
    // Adding found values to the arrays
    $ansStudentStatus[$var] = $studentStatus;
    $ansUsername[$var] = $username;
    $ansNoOfComments[$var] = $noOfComments;
    $ansQuestionID[$var] = $questionID;

    //code here to construct 'matching' array
    $match=0;
    for ($i=0; $i<count($keywords); $i++)
    {
      $wordToCheck=$keywords[$i];

      $str = explode(" ", $ansTitle[$var]); //get each word from ansTitle[var]
      for ($index=0; $index< count($str); $index++)
        if($wordToCheck == $str[$index])
          $match++;

      $str=explode(" ", $ansCategory[$var]);
      for ($index=0; $index<count($str); $index++)
        if($wordToCheck == $str[$index])
          $match++;

      $str=explode(" ",$ansText[$var]);
      for ($index=0; $index<count($str); $index++)
        if($wordToCheck == $str[$index])
          $match++;
    }
    $matching[$var]=$match;  //store how many words matched
                             //in 'var' line of the query
    $indices[$var]=$var;
    $var++;
  }
  //now sort indices depending on 'matching' array
  array_multisort($matching,$indices);

  //CREATE BASIC TABLE HEADINGS

  echo'<table class = "searchTable">
       <thead>
         <tr>
           <th>Username</th>
           <th>Question</th>
           <th>Replies</th>
           <th>Date</th>
           <th>Category</th>
         </tr>
      </thead>
      <tbody>';

  for($i=$var-1; $i>=0; $i--)
  {
    $sortedIndex=$indices[$i];
    echo '<tr>
            <td>('.$ansStudentStatus[$sortedIndex].') '.$ansUsername[$sortedIndex].'</td>
            <td><a href = "QuestionPage.php?id='.$ansQuestionID[$sortedIndex].'">'.$ansTitle[$sortedIndex].'</a></td>
            <td>'.$ansNoOfComments[$sortedIndex].'</td>
            <td>'.$ansDate[$sortedIndex].'</td>
            <td>'.$ansCategory[$sortedIndex].'</td>
          </tr>';
    /*$j=$i+1;
    echo "Result no $j (sorted by no of matched words) : ";

    echo "Question Title : ";
    echo "<font color=orange>$ansTitle[$sortedIndex]<font color=black>";

    echo "; Question Category : ";
    echo "<font color=orange>$ansCategory[$sortedIndex]<font color=black>";

    echo "; Question Text : ";
    echo "<font color=orange> $ansText[$sortedIndex] <br><br><br><font
                                                      color=black>"; */
  }
  
echo "</table>";

$mysqli -> close();
?>

</div>

<div id="bottomNav"></div>
</body>
</html>
