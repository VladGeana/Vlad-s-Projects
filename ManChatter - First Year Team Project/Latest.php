<?php
//Connect to the database
require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
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
  $search=$mysqli -> query("SELECT * FROM Questions WHERE Category = '$category' ORDER BY Date DESC");
} else {
  $search=$mysqli -> query("SELECT * FROM Questions ORDER BY Date DESC");
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
  $search=$mysqli -> query("SELECT * FROM Questions WHERE Category = '$category' ORDER BY Date DESC LIMIT $from, 20");
} else {
  $search=$mysqli -> query("SELECT * FROM Questions ORDER BY Date DESC LIMIT $from, 20");
}

echo'<table>
      </thead>
         <tr>
           <th></th>
           <th>Category: '.$category.'</th>
           <th></th>
           <th></th>
         </tr>
        <tr></tr>
       <tr>
         <th>Username</th>
         <th>Question</th>
         <th>Replies</th>
         <th>Date</th>
       </tr>
             </thead>';
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
  echo "<tr>
          <td>(".$studentStatus.") ".$username."</td>
          <td><a href = 'QuestionPage.php?id=".$row['QuestionID']."'>".$questionTitle."</a></td>
          <td>".$noOfComments."</td>
          <td>".$date."</td>
        </tr>";
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
