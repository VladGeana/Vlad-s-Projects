<?php
require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

echo "<p>this works</p>";

if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}

  $questionID = (int)$_GET['id'];

$answers = mysqli_query($mysqli, "SELECT * FROM Answers WHERE QuestionID = $questionID");

  while($result = mysqli_fetch_array($answers))
  {
    $id = $result["AnswerID"];
    $likes = mysqli_query($mysqli, "SELECT Likes FROM Answers WHERE AnswerID = $id");
    $dislikes = mysqli_query($mysqli, "SELECT Dislikes FROM Answers WHERE AnswerID = $id");
    $text = mysqli_query($mysqli, "SELECT AnswerText FROM Comments WHERE AnswerID = $id");

    $userID = $result["UserID"];
    $username = mysqli_query($mysqli, "SELECT Username FROM Users WHERE UserID = $userID");

    $result["AnswerID"] = $answerID;


    echo "
      <div name='answerByDate'>
        <form>
          <p>$userID</p>

          <textarea class='answer' name='answer' readonly>$text</textarea><br>

          <span id='likes'>$likes</span>
          <button id='$answerID' class='answerLikes' type='button' name='likes'>&#8593;</button>

          <span id='dislikes'>$dislikes</span>
          <button id='$answerID' class='answerDislikes' type='button' name='dislikes'>&#8595;</button>
        </form>
     </div>
    ";


    $table =  mysqli_query($mysqli, "SELECT * FROM Comments WHERE AnswerID = $answerID");
    while($result = mysqli_fetch_array($table))
    {
      $text = $result["CommentText"];
      $likes = $result["Likes"];
      $dislikes = $result["Dislike"];

      $userID = $result["UserID"];
      $username = mysqli_query($mysqli, "SELECT Username FROM Users WHERE UserID = $userID");

      $commentID = $result["CommentID"];


      echo "
        <div class='commentByDate'>
          <form >
            <p>$userID</p>

            <textarea class='comment' name='comment' readonly>$text</textarea><br>

            <span id='likes'>$likes</span>
            <button id='$commentID' class='commentLikes' type='button' name='likes'>&#8593;</button>

            <span id='dislikes'>$dislikes</span>
            <button id='$commentID' class='commentDislikes' type='button' name='dislikes'>&#8595;</button>
          </form>
       </div>
      ";
    }
  }


  $answer = $mysqli -> query("SELECT * FROM Answers ORDER BY Likes");

  while($result = mysqli_fetch_array($answers))
  {
    $id = $result["AnswerID"];
    $likes = mysqli_query($mysqli, "SELECT Likes FROM Answers WHERE AnswerID = $id");
    $dislikes = mysqli_query($mysqli, "SELECT Dislikes FROM Answers WHERE AnswerID = $id");
    $text = mysqli_query($mysqli, "SELECT AnswerText FROM Comments WHERE AnswerID = $id");

    $userID = $result["UserID"];
    $username = mysqli_query($mysqli, "SELECT Username FROM Users WHERE UserID = $userID");

    $result["AnswerID"] = $answerID;



    echo "
      <div name='answerByVotes'>
        <form>
          <p>$userID</p>

          <textarea class='answer' name='answer' readonly>$text</textarea><br>

          <span id='likes'>$likes</span>
          <button id='$answerID' class='answerLikes' type='button' name='likes'>&#8593;</button>

          <span id='dislikes'>$dislikes</span>
          <button id='$answerID' class='answerDislikes' type='button' name='dislikes'>&#8595;</button>
        </form>
     </div>
    ";


    $table =  mysqli_query($mysqli, "SELECT * FROM Comments WHERE AnswerID = $answerID");
    while($result = mysqli_fetch_array($table))
    {
      $text = $result["CommentText"];
      $likes = $result["Likes"];
      $dislikes = $result["Dislike"];

      $userID = $result["UserID"];
      $username = mysqli_query($mysqli, "SELECT Username FROM Users WHERE UserID = $userID");

      $commentID = $result["CommentID"];


      echo "
        <div class='commentByVotes'>
          <form >
            <p>$userID</p>

            <textarea class='comment' name='comment' readonly>$text</textarea><br>

            <span id='likes'>$likes</span>
            <button id='$commentID' class='commentLikes' type='button' name='likes'>&#8593;</button>

            <span id='dislikes'>$dislikes</span>
            <button id='$commentID' class='commentDislikes' type='button' name='dislikes'>&#8595;</button>
          </form>
       </div>
      ";
    }
  }

?>
