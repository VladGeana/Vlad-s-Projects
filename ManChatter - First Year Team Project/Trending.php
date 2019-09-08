<?php
require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}


//for every question in the database
//for($i = 1; $i <= $size; $i++)
$qry = mysqli_query($mysqli, "SELECT * FROM Questions");

//Loop through the table
while($result = mysqli_fetch_array($qry))
{
  // JOSIE'S NEW CODE FOR TIME which uses datetime
  //computes the current datetime
  $today = new DateTime();
  $today = date("Y-m-d H:i:s");

  //Get the date of the post from the database
  $date = $result["Date"];

  //the difference in time between the current time and stored time
  $time = abs(strtotime($today) - strtotime($date));

  $i = 0;

  //Get the ID of the question
  $ID = $result["QuestionID"];

  //Make i equal to the ID of the question
  while($i != $ID)
  {
    $i++;
  }

  $comments = 0;
  $likes = 0;
  $dislikes = 0;
  $search=$mysqli -> query("SELECT * FROM Answers WHERE QuestionID='$i'");
  while($row=mysqli_fetch_array($search))
  {
    $answerID = $row["AnswerID"];
    $comments++;
    $likes += $row["Likes"];
    $dislikes += $row["Dislikes"];
    $search2=$mysqli -> query("SELECT * FROM Comments WHERE AnswerID='$answerID'");
    while($row2=mysqli_fetch_array($search2))
    {
      $comments++;
      $likes += $row2["Likes"];
      $dislikes += $row2["Dislikes"];
    }
  }

  /*Compute the total number of comments
  $comments=mysqli_num_rows($mysqli -> query("SELECT * FROM Comments WHERE QuestionID = $i"));
  $comments += mysqli_num_rows($mysqli -> query("SELECT * FROM Answers WHERE QuestionID = $i"));


  //Compute the difference between likes and dislikes
  $likes = mysqli_num_rows($mysqli -> query("SELECT Likes FROM Comments WHERE QuestionID = $i"));
  $likes += mysqli_num_rows($mysqli -> query("SELECT Likes FROM Answers WHERE QuestionID = $i"));

  $dislikes = mysqli_num_rows($mysqli -> query("SELECT Dislikes FROM Comments WHERE QuestionID = $i"));
  $dislikes += mysqli_num_rows($mysqli -> query("SELECT Dislikes FROM Answers WHERE QuestionID = $i"));
  */
  $difference = $likes - $dislikes;

  //Compute the trending value
  $trendingValue = $comments + $comments * abs($difference) + $difference - log10($time);


  //Add the trending value to the database
  $sql = "UPDATE Questions
          SET TrendingValue = $trendingValue
          WHERE QuestionID=$i";

  if ($mysqli->query($sql) === TRUE)
  {}
  else
  {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
  }
}


$categories = array(
    0    => "All",
    1  => "Finances",
    2  => "Accommodation",
    3 => "Applying",
    4  => "Courses",
    5  => "Food",
    6 => "Shopping",
    7  => "Nightlife",
    8  => "Other",
);

$studentStatusArray = array(
    0    => "Standard",
    1  => "UOM student",
    2  => "Alumni",
    3 => "Banned",
    4  => "Admin",
);

$categoryNumber = (int)$_POST['categoryNumber'];
$category = (string)$categories[$categoryNumber];

//echo "<h2>$category</h2>";

if ($categoryNumber > 0){
  $search=$mysqli -> query("SELECT * FROM Questions WHERE Category = '$category' ORDER BY TrendingValue DESC");
} else {
  $search=$mysqli -> query("SELECT * FROM Questions ORDER BY TrendingValue DESC");
}

$pageNumber = $_POST['pageNumber'];
$transition = $_POST['transition'];
$limit = 20;
if ($transition == 1)
{
  $pageNumber += 1;
  $from = $pageNumber * $limit;
  $numberOfRows = 0;
  while($row=mysqli_fetch_array($search))
  {
    $numberOfRows += 1;
  }
  if($from >= $numberOfRows)
  {
    $from -= $limit;
    $pageNumber -= 1;
  }
}

else if ($transition == -1)
{
  $pageNumber -= 1;
  $from = $pageNumber * $limit;
  if($from < 0)
  {
    $from = 0;
    $pageNumber += 1;
  }
}
// else start with the first Question
else $from = 0;



if ($categoryNumber > 0){
  $search=$mysqli -> query("SELECT * FROM Questions WHERE Category = '$category' ORDER BY TrendingValue DESC LIMIT $from, 20");
} else {
  $search=$mysqli -> query("SELECT * FROM Questions ORDER BY TrendingValue DESC LIMIT $from, 20");
}

echo'<table>
       <thead>
         <tr>
           <th></th>
           <th>Category: '.$category.'</th>
           <th></th>
           <th></th>
         </tr>
         <tr>
           <th>Username</th>
           <th>Question</th>
           <th>Replies</th>
           <th>Date</th>
         </tr>
      </thead>
      <tbody>';
//Find all details about each question
while($row=mysqli_fetch_array($search))
{
  $questionTitle = $row["QuestionTitle"];
  $date = $row["Date"];
  $questionID = $row["QuestionID"];
  $userID = $row["UserID"];
  $noOfComments = 0;
  $studentStatus = "";
  // Find details about user who wrote the question
  $search2=$mysqli -> query("SELECT * FROM Users WHERE UserID='$userID'");
  while($row2=mysqli_fetch_array($search2))
  {
    $username = $row2["Username"];
    $studentStatusNo = (int)$row2["StudentStatus"];
    $studentStatus = $studentStatusArray[$studentStatusNo];
  }
  $search3=$mysqli -> query("SELECT * FROM Answers WHERE QuestionID='$questionID'");
  // Find number of answers and comments
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
  echo '<tr>
          <td>('.$studentStatus.') '.$username.'</td>
          <td id="question"><a href = "QuestionPage.php?id='.$row['QuestionID'].'">'.$questionTitle.'</a></td>
          <td>'.$noOfComments.'</td>
          <td>'.$date.'</td>
        </tr>';
}
echo "  </tbody>
      </table>";
$userPageNumber = $pageNumber + 1;
echo "<h4>Page Number: $userPageNumber</h4>";
if ($categoryNumber > 0){
  echo ("<form name='form1' action='HomePage.php?cat=$categoryNumber' method='post' >
      <input type='hidden' name='pageNumber' id='pageNumber' value='$pageNumber'>
      <input type='submit' name='submit' value='<<< Previous'>
      <input type='submit' name='submit' value='Next >>>'>
      </form>");
} else {
  echo ("<form name='form1' action='HomePage.php' method='post' >
      <input type='hidden' name='pageNumber' id='pageNumber' value='$pageNumber'>
      <input type='submit' name='submit' value='<<< Previous'>
      <input type='submit' name='submit' value='Next >>>'>
      </form>");
}
?>
