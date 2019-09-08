
<!-- created using w3 schools tutorial-->
<?php

// Start the session
session_start();

//connect to database
require_once('config.inc.php');
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


if($mysqli -> connect_error)
{
 die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"]))
{
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false)
    {
        echo "File is an image - " . $check["mime"] . ".\n";
        $uploadOk = 1;
    }
    else
    {
        echo "File is not an image.\n";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file))
{
    echo "Sorry, file already exists. Please rename your file.\n";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000)
{
    echo "Sorry, your file is too large.\n";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
{
    echo "Sorry, only JPG, JPEG and PNG files are allowed.\n";
    $uploadOk = 0;
}


// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0)
{
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
}
else
{
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
    {
        $fileName = basename( $_FILES["fileToUpload"]["name"]);
        /* Write the data here. */
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]) . " has been uploaded.\n";
        $userID = (int)$_SESSION['UserID'];
        $sql="UPDATE Users
              SET PictureLink = '$fileName'
              WHERE UserID = $userID";
        if ($mysqli->query($sql) === TRUE) {
          echo "New record created successfully<br>";
        } else {
          echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
        //redirect back to previous page
        header("Location: account.php");
        die();
    }
    else
    {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
