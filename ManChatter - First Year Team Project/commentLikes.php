<?php
require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}

$answerID = (int)$_GET['id'];
$userID = (int)$_POST['??'];

$userReaction = mysqli_query($mysqli, "SELECT UserReaction FROM UsersToComments
                             WHERE UserID = $userID AND AnswerID = $answerID");

if($userReaction)//idk how $userReaction works so im gonna leave it like this
{
  $result =mysqli_query($mysqli, "SELECT * FROM Comments WHERE AnswerID = $answerID");
  $likes = $result["Dislikes"] + 1;

  $sql = "UPDATE Comments
          SET Likes = $likes
          WHERE AnswerID=$answerID";

  if ($mysqli->query($sql) === TRUE)
  {}
  else
  {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
  }
}
}

?>
