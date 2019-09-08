<?php
  // Start the session
  session_start();
  // Connection to database
  require_once('config.inc.php');
  // Establish connection with database
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

  // Output connection error if something goes wrong
  if($mysqli -> connect_error)
  	die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
?>

<!DOCTYPE html>
<html lang = en>
  <head>
    <title>Manchatter</title>
    <link rel="stylesheet" type="text/css" href="ourstyle.css">

    <!-- JAVASCRIPT FOR SLIDING SIDE BAR -->
    <script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
    <script src="scriptForMenu.js"></script>

    <script>
      function updateLike(likeType, answerID)
      {
        alert(likeType + answerID);
        document.getElementById("aID").value = answerID;
        document.getElementById("likeType").value = likeType;
        document.getElementById("form1").submit();
      }

      function updateReport()
           {
            // alert("hello");
             if (confirm("Are you sure you want to report?")) {

               document.getElementById("reportedQuestion").value = "yes";
               document.getElementsByName("form4")[0].submit();
             }
           }

           function updateReportAnswer(answerID)
               {
              //   alert(answerID);
                 if (confirm("Are you sure you want to report?")) {

                   document.getElementById("aID").value = answerID;
                   document.getElementById("reportedAnswer").value = "yes";
                   document.getElementsByName("form3")[0].submit();
            //    alert("Hloo");
                }
               }

$(document).ready(function(){

  $(".reply").click(function()
  {
    this.style.display = "none";

    var form = document.createElement("form");
    var questionID = document.createElement("input");
    var answerID = document.createElement("input");
    var textarea = document.createElement("textarea");
    var p = document.createElement("p");
    var submit = document.createElement("input");

    form.appendChild(questionID)
    form.appendChild(answerID);
    form.appendChild(textarea);
    form.appendChild(p);
    form.appendChild(submit);

    form.setAttribute("action","QuestionPage.php");
    form.setAttribute("method","post");

    questionID.setAttribute("type","hidden");
    answerID.setAttribute("type","hidden");
    submit.setAttribute("type","submit");

    questionID.setAttribute("class","qID")
    answerID.setAttribute("class","aID");
    textarea.setAttribute("class","commentText");

    questionID.setAttribute("name","qID");
    answerID.setAttribute("name","aID");
    textarea.setAttribute("name","commentText");
    submit.setAttribute("name","commentSubmit");

    var classPart = $(this).attr("class").split(' ')[0];

    questionID.setAttribute("value",$(this).attr("name"));
    answerID.setAttribute("value",classPart);
    submit.setAttribute("value","Submit comment");

    $(form).insertAfter($(this));
  });
});

    </script>


  </head>

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

    <?php
      // Copy question id into a local variable.
      if     (isset($_GET['id']))   $qID = $_GET['id'];
      elseif (isset($_POST['qID'])) $qID = $_POST['qID'];
      else                          $qID = 11;

      if(isset($_POST['answerText']) && isset($_SESSION['UserID']))
      {
        $userID = $_SESSION["UserID"];
        $answerText = $_POST["answerText"];
        $today = new DateTime();
        $today = date("Y-m-d H:i:s");

        $sql = "INSERT INTO  `Answers` (`AnswerID`,`QuestionID`,`UserID`,`Answer Text`,
                `Date`,`NumberOfReports`, `Banned`, `Likes`, `Dislikes`)
                VALUES (NULL, '$qID', '$userID', '$answerText',
                '$today', '0', '0', '0', '0')";

        if ($mysqli->query($sql) === TRUE)
        {}
        else
        {
          echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
      }

      if(isset($_POST['commentText']) && isset($_SESSION['UserID']))
      {
        $userID = $_SESSION["UserID"];
        $commentText = $_POST["commentText"];
        $today = new DateTime();
        $today = date("Y-m-d H:i:s");
        $answerID = $_POST["aID"];

        $sql = "INSERT INTO `Comments` (`CommentID`, `AnswerID`, `UserID`,
                `CommentText`, `Date`, `NumberOfReports`, `Banned`, `Likes`, `Dislikes`)
                VALUES (NULL, '$answerID', '$userID', '$commentText', '$today', '0', '0', '0', '0')";

        if ($mysqli->query($sql) === TRUE)
        {}
        else
        {
          echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
      }

      //  Check to see if the question has been reported
      if (isset($_POST['reportedQuestion']) && isset($_SESSION["UserID"]))
        if($_POST['reportedQuestion']=='yes')
         reportQuestion($qID, $_SESSION["UserID"], $mysqli);

         //  Check to see if the answer has been reported
         if (isset($_POST['reportedAnswer']) && isset($_SESSION["UserID"]))
           if($_POST['reportedAnswer']=='yes')
            reportAnswer($_POST["aID"], $qID, $_SESSION["UserID"], $mysqli);

      //  Check to see if an answer has been liked or disliked
      if (isset($_POST['submit']) && isset($_SESSION['UserID']) && $_POST['submit'] == 'Like')
      {
        likeDislikeAnswer($qID, 'like',$_POST['aID'], $mysqli, $_SESSION['UserID']);
      }
      else if(isset($_POST['submit']) && isset($_SESSION['UserID']) && $_POST['submit'] == 'Dislike')
      {
        likeDislikeAnswer($qID, 'dislike',$_POST['aID'], $mysqli, $_SESSION['UserID']);
      }

      // Display the question
      displayQuestion($qID, $mysqli);

      $limit = 2;

      // Check to see if answers have already been displayed, if so get number
      if (isset($_POST['numOfAnswersShown']) && $_POST['submit'] == 'Next >>>')
      {
        $from = $_POST['numOfAnswersShown'];

        $result = mysqli_fetch_array($mysqli->query("SELECT COUNT(AnswerID) FROM Answers WHERE QuestionID = $qID"));
        $numberOfRows = (int) $result['COUNT(AnswerID)'];

        if($from == $numberOfRows || $from == $numberOfRows + 1)
          $from -= $limit;

      }

      else if (isset($_POST['numOfAnswersShown']) && $_POST['submit'] == '<<< Previous')
      {
        $from = (int) $_POST['numOfAnswersShown'];
        if($from >= 2*$limit)
          $from -= 2*$limit;
        else
          $from -= $limit;
      }
      // else start with the first answer
      else $from = 0;
      // limit to n answers to display

      ?>

      <div id="answerQuestion">
        <br>
        <form action="QuestionPage.php" method="post">

          <input type="hidden" id="qID" name="qID" value="<?php echo $qID; ?>">
          <textarea id="answerText" class="answerText" name="answerText"></textarea>
          <br>
          <input type="submit" name="answerSubmit" value="Submit">
          <p></p>
        </form>
     </div>

      <?php
      $sql = "SELECT * FROM Answers WHERE QuestionID = $qID LIMIT $from, $limit";


      // Get the answer information from the database for given question id number
      $result=$mysqli->query($sql);

      // Display the answers
      if ($row = mysqli_fetch_array($result) )
      {
        //displayAnswers($qID, $from, $limit, $mysqli);
        $to = $from + $limit - 1;
        $sql2 = "SELECT * FROM Answers WHERE QuestionID = $qID
                ORDER BY Date DESC LIMIT $from, $limit";

       //echo('<div id="submit"');

        //echo ("<b>" . $sql . "</b>");
        // Get the answer information from the database for given question id number
        $result2=$mysqli->query($sql2);

       //echo('</div>');

        // Display the answers
        while ($row = mysqli_fetch_array($result2) )
        {
          $userID = $row["UserID"];
          $sql2 = "SELECT * FROM Users WHERE UserID = $userID";
          $result3 = mysqli_fetch_array($mysqli -> query($sql2));
          $userName = $result3["Username"];

          echo('<div class="answers"');
          echo ('<br>
                 <p class="answer">' . $userName . ' answered : ' . $row["Answer Text"] . '</p>
                 <br>');

      $answerID = $row["AnswerID"];
      displayComments($answerID,$mysqli);

//Reporting answers form3
      echo("<div class='reportAndReply'>");
      echo("<form name='form3' action ='$_SERVER[PHP_SELF]' method='post'>");
      echo("<span class='report'>Report Answer!</span>");
      echo("<input type='image' onclick='updateReportAnswer($row[AnswerID])' src='redx.png' id='redx' alt='Report User' style='width:25px;height:25px;' class='reportImage' >");
      echo("<input type='hidden' name='reportedAnswer' id='reportedAnswer' value=''>");
      echo("<input type='hidden' name='aID' id='aID' value='no'>");
      echo("<input type='hidden' name='qID' id='qID' value='$qID'>");
      echo("</form>");




          $likes = $row["Likes"];
          $dislikes = $row["Dislikes"];

          echo ("<button class='".$answerID." reply' name='$qID'>Reply</button>
                 </div>
                 <p class='clear'></p>
                 <p class='likes'>$likes Likes</p>
                 <p class='dislikes'>$dislikes Dislikes</p>
                 <form name='form2' action ='$_SERVER[PHP_SELF]' method='post'>
                 <input type='hidden' name='qID' id='qID' value='$qID'>
                 <input type='hidden' name='aID' id='aID' value='$answerID'>
                 <input type='submit' name='submit' class='Like' value='Like'>
                 <input type='submit' name='submit' class='Dislike' value='Dislike'>
                 <p></p>
                 </form>");
          echo ('</div>');
        }

        $to++;
        // We need to remember if answers have been displayed
        echo ("<form name='form1' action='$_SERVER[PHP_SELF]' method='post' >
                <br>
                <input type='hidden' name='qID' id='qID' value='$qID'>
                <input type='hidden' name='numOfAnswersShown' id='numOfAnswersShown' value='$to'>
                <input type='submit' name='submit' value='<<< Previous'>
                <input type='submit' name='submit' value='Next >>>'>
              </form>");
      }
      else echo "Nothing left to display";
    ?>


    <!-- closing div for main -->
    </div>

    <div id="bottomNav"></div>
  </body>
</html>

<?php
function displayQuestion($questionID, $mysqli)
{
  // Get the question information from the database for given question id number
  $search=$mysqli->query("SELECT UserID, QuestionTitle, QuestionText, Date FROM Questions WHERE QuestionID = $questionID");


  // Copy information into an associate array called row
  $row=mysqli_fetch_array($search);

  // Assign to local variables
  $userID = $row["UserID"];
  $questionTitle = $row["QuestionTitle"];
  $questionText = $row["QuestionText"];
  $date = $row["Date"];

  // Get the user id of the user that posted this question
  $userLookUp = $mysqli -> query("SELECT Username FROM Users WHERE UserID = $userID");

  // Copy information to an associate array
  $user = mysqli_fetch_array($userLookUp);

  // Assign to local variables.
  $username = $user["Username"];

  // Display the required information
  echo('<div id="QandUser">
          <div id="UserInfo">
            <h4>' . $username . '</h4><br />'
                  . $date . '<br /></div>
            <div id="theQ">
              <h4>' . $questionTitle . '</h4> <br />'
                    . $questionText
      );

  // if the user clicks on the report image then post the values captured (user id and question id)
  echo ("<form name='form4' action='$_SERVER[PHP_SELF]' method='post' >
          <br>
          <input type='hidden' name='qID' id='qID' value='$questionID'>
          <input type='hidden' name='reportedQuestion' id='reportedQuestion' value=''>
          <input type='hidden' name='uID' id='uID' value='$userID'>
          <input type='image' onclick='updateReport()' src='redx.png' id='redx' alt='Report User' style='width:25px;height:25px;'' > Report Question!
        </form>");

  // close the divs
  echo('   </div>
        </div>');
}

function displayComments($answerID, $mysqli)
{
  $sql = "SELECT * FROM Comments WHERE AnswerID = $answerID";

  $result = $mysqli->query($sql);

  echo ('<div class="comments">');

  while ($row = mysqli_fetch_array($result) )
  {
    $userID = $row["UserID"];
    $sql2 = "SELECT * FROM Users WHERE UserID = $userID";
    $result3 = mysqli_fetch_array($mysqli -> query($sql2));
    $userName = $result3["Username"];


    echo ( '<p class="comment">' . $userName . ' commented : ' . $row["CommentText"] . '</p>
           <br>');

  }
  echo ('</div>
         <br>');
}

function reportQuestion($questionID, $uID, $mysqli)
{
  // Get user id from reported question where user id and question id both exist.
  $sql = "SELECT UserID from reportedquestion WHERE QuestionID = '$questionID' AND UserID = '$uID'";
//  echo $sql;
  // Query the database
  $result=$mysqli -> query($sql);
  // Count how many rows were returned from database.
  $rowCount=mysqli_num_rows($result);

  // if a row (or more) was returned then this user has already reported.
  if ($rowCount > 0) // if they have already reported.
  {
    echo ("You have already reported this question!");
  }
  else  // if row count is 0, then they have not yet reported.
  {
    // construct sql to get number of reports for the question id posted
    $sql = "SELECT NumberOfReports from Questions WHERE
          QuestionID = '$questionID'";
    // for testing to see what the SQL statement looks like
//    echo ('<p><b>The SQL:</b>' . $sql . "</p>");
    // query the database
    if ($result=$mysqli -> query($sql))
    {
      // fetch the row from the array
      $row = mysqli_fetch_array($result);
      // copy number of reports into a local variable.
      $nOR = $row['NumberOfReports'];
      // increment number of reports
      $nOR++;
      // construct SQL to update
      $sql = "UPDATE Questions SET NumberOfReports=$nOR WHERE QuestionID = $questionID";
      // for testing to see what the SQL statement looks like
//      echo ('<p><b>The SQL:</b>' . $sql . "</p>");
      // query the database
      if ($mysqli -> query($sql))
      {
        echo ("<p>thank you for the report!</p>");

        // Now we need to updated reported Question for this question and user.
        $sql = "INSERT INTO reportedquestion VALUES ($uID, $questionID)";
        // query the database
        $mysqli -> query($sql);
      }
      else
      {
        echo ('<p>Something went wrong!</p>');
      }

    }
    else
    {
      echo ('Something went wrong!');
    }
  }
   // Just a button to continue
      echo ("<form name='form1' action='$_SERVER[PHP_SELF]' method='post' >");
      echo ("<input type='hidden' name='qID' id='qID' value='$questionID'>");
      echo ("<input type='submit' name='submit' value='Continue >>>'>");
      echo ('</form>');
}

function likeDislikeAnswer($questionID, $likeType, $answerID, $mysqli, $userID)
{
  $sql = "SELECT * FROM UsersToAnswers WHERE AnswerID = $answerID AND UserID = $userID";
  $result = $mysqli -> query($sql);
  $row = mysqli_fetch_array($result);
  $reaction = $row['UserReaction'];

  // so that we can get the correct field from the database
  if ($likeType == 'like') $fName = 'Likes';
  else $fName = 'Dislikes';

  if( !isset($reaction) || (isset($reaction) && $fName == 'Likes' && $reaction != 1) ||
      (isset($reaction) && $fName == 'Dislikes' && $reaction != 2))
  {
    // construct sql to get number of likes/dislikes for the question and answer id posted
    $sql = "SELECT " . $fName . " FROM Answers WHERE
          QuestionID = '$questionID' AND AnswerID = '$answerID' ";

    // query the database and check it worked
    if ($result=$mysqli -> query($sql))
    {
      // fetch the row from the array
      $row = mysqli_fetch_array($result);
      // copy number of likes or dislikes into a local variable.
      $nOFLORD = $row[$fName];
      // increment number of likes or dislikes
      $nOFLORD++;
      // construct SQL to update
      $sql = "UPDATE Answers SET " . $fName . "=" . $nOFLORD . " WHERE
          QuestionID = '$questionID' AND AnswerID = '$answerID' ";
      // query the database
      if (!$mysqli -> query($sql))
      {
        echo ('<p>Something went wrong!</p>');
      }
    }
    else
    {
      echo ('Something went wrong!');
    }

    if($fName == 'Likes') $reactionNew = 1;
    else $reactionNew = 2;

    if(!isset($reaction))
    {

      $sql = "INSERT INTO `UsersToAnswers` (`AReactionID`, `UserID`, `AnswerID`,
              `UserReaction`) VALUES (NULL, '$userID', '$answerID',
              '$reactionNew')";

      if(!$mysqli -> query($sql))
        echo ('Something went wrong!');
    }
    else
    {
      $sql = "UPDATE UsersToAnswers SET UserReaction = $reactionNew WHERE
              AnswerID = $answerID AND UserID = $userID ";
      if(!$mysqli -> query($sql))
        echo ('Something went wrong!');
    }
   }

  }

  function reportAnswer($aID, $qID, $uID, $mysqli)
  {
  // echo("You're about to report".$aID);
   // Get user id from reported question where user id and question id both exist.
   $sql = "SELECT UserID from reportedanswer WHERE AnswerID = '$aID' AND UserID = '$uID'";
//   echo $sql;
   // Query the database
   $result=$mysqli -> query($sql);
   // Count how many rows were returned from database.
   $rowCount=mysqli_num_rows($result);

   // if a row (or more) was returned then this user has already reported.
   if ($rowCount > 0) // if they have already reported.
   {
     echo ("You have already reported this question!");
   }
   else  // if row count is 0, then they have not yet reported.
   {
     // construct sql to get number of reports for the question id posted
     $sql = "SELECT NumberOfReports from Answers WHERE
           AnswerID = '$aID'";
     // for testing to see what the SQL statement looks like
  //   echo ('<p><b>The SQL:</b>' . $sql . "</p>");
     // query the database
     if ($result=$mysqli -> query($sql))
     {
       // fetch the row from the array
       $row = mysqli_fetch_array($result);
       // copy number of reports into a local variable.
       $nOR = $row['NumberOfReports'];
       // increment number of reports
       $nOR++;
       // construct SQL to update
       $sql = "UPDATE Answers SET NumberOfReports=$nOR WHERE AnswerID = $aID";
       // for testing to see what the SQL statement looks like
    //   echo ('<p><b>The SQL:</b>' . $sql . "</p>");
       // query the database
       if ($mysqli -> query($sql))
       {
         echo ("<p>thank you for the report!</p>");

         // Now we need to updated reported Question for this question and user.
         $sql = "INSERT INTO reportedanswer VALUES ($uID, $aID)";
         // query the database
         $mysqli -> query($sql);
       }
       else
       {
         echo ('<p>Something went wrong!</p>');
       }

     }
     else
     {
       echo ('Something went wrong!');
     }
   }
    // Just a button to continue
       echo ("<form name='form1' action='$_SERVER[PHP_SELF]' method='post' >");
       echo ("<input type='hidden' name='aID' id='aID' value='$aID'>");
            echo ("<input type='hidden' name='qID' id='qID' value='$qID'>");
       echo ("<input type='submit' name='submit' value='Continue >>>>>'>");
       echo ('</form>');
  }



?>
