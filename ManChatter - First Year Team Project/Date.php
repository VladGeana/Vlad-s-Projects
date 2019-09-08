<?php
  require_once('config.inc.php');
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

  if($mysqli -> connect_error)
  {
    die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }

  $questionID = (int)$_POST['id'];

  // To display max 10 answers per page.
  $start = 0;
  $limit = 10;
  $page = (isset($_GET['page']) ? : 0);
  $start = $page * $limit;

  $answers = mysqli_query($mysqli, "SELECT * FROM Answers WHERE QuestionID = $questionID
                                  ORDER BY Date DESC LIMIT {$start}, {$limit}");

  while($result = mysqli_fetch_array($answers))
  {
    $id = $result["AnswerID"];
    $likes = (string) $result["Likes"];
    $dislikes = (string) $result["Dislikes"];
    $text = (string) $result["Answer Text"];

    $userID = $result["UserID"];
    $username = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM Users WHERE UserID = $userID"));
    $username2 = $username["Username"];

    $answerID = $result["AnswerID"];


    echo "
      <div class='answerByDate'>
        <form>
          <p>$username2</p>

          <textarea class='answer' name='answer' readonly>$text</textarea><br>

          <span id='likes' class= '.$answerID.'>$likes</span>
          <button id=\"".$answerID."\" class='answerLikes' type='button' onclick='box()' >&#8593;</button>

          <span id='dislikes' class='".$answerID."'>$dislikes</span>
          <button id='".$answerID."' class='answerDislikes' type='button' >&#8595;</button>

          <button id='".$answerID."' class='reply' onclick='showReply(this.id)'>Reply</button>

          <div class='$answerID' id='replyForm' style='display:none;'>
            <form action='QuestionPage.php' method='get'>
              <input type='hidden' class='id' value='".$questionID."'>
              <input type='hidden' class='idAnswer' value='".$answerID."'>
              <textarea class='newComment'></textarea>
              <input type='submit' value='Submit'>
            </form>
          </div>

        </form>
     </div>
    ";


    $table =  mysqli_query($mysqli, "SELECT * FROM Comments WHERE AnswerID = $answerID");
    while($result = mysqli_fetch_array($table))
    {
      $text = (string) $result["CommentText"];
      $likes = (string) $result["Likes"];
      $dislikes = (string) $result["Dislikes"];

      $userID = $result["UserID"];
      $username = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM Users WHERE UserID = $userID"));
      $username2 = $username["Username"];

      $commentID = $result["CommentID"];


      echo "
        <div class='commentByDate'>
          <form >
            <p>$username2</p>

            <textarea class='comment' name='comment' readonly>$text</textarea><br>

            <span id='likes' class='".$commentID."'>$likes</span>
            <button id='".$commentID."' class='commentLikes' type='button'>&#8593;</button>

            <span id='dislikes' class='".$commentID."'>$dislikes</span>
            <button id='".$commentID."' class='commentDislikes' type='button'>&#8595;</button>
          </form>
       </div>
      ";
    }
  }
 ?>
