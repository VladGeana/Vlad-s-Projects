<?php
require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}

$answerID = (int)$_GET['id'];

if(isset($_GET['userID']))
{
  $userID = (int)$_GET['userID'];

$table = mysqli_fetch_array(mysqli_query($mysqli, "SELECT UserReaction FROM UsersToAnswers
                             WHERE UserID = $userID AND AnswerID = $answerID"));
$userReaction = $table["UserReaction"];

if(isset($userReaction) && $userReaction != 1)
{
  $result =mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM Answers
                                           WHERE AnswerID = $answerID"));
  $likes = (int)$result["Likes"] + 1;

  $sql = "UPDATE Answers
          SET Likes = $likes
          WHERE AnswerID = $answerID";

  if ($mysqli->query($sql) === TRUE)
  {}
  else
  {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
  }
}
  else if(!isset($userReaction)){
  $sql = "INSERT INTO `UsersToAnswers` (`AReactionID`, `UserID`, `AnswerID`,
          `UserReaction`, `Reported`) VALUES (NULL, '$userID', '$answerID','1','0')";
  }
  else {
    $sql = "UPDATE UsersToAnswers SET UserReaction = 1 WHERE UserID = $userID";
  }
  if ($mysqli->query($sql) === TRUE)
  {}
  else
  {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
  }

}

?>
