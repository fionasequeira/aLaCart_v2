<?php
$Invalid_pwd='';
$ID='';
$PWD='';
$invalid_search='';
$input='';
  include('../config.php');
  require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\S3\S3Client;
  
  session_start();
  if(!isset($_SESSION['EmailID'])){
    session_destroy();
    header('Location: '.'login.php');
  }

// Create the AWS service builder, providing the path to the config file
  $dynamoDbClient = DynamoDbClient::factory(array(
    'credentials' => array(
        'key'    => $aws_access_key_id,
        'secret' => $aws_secret_access_key,
    ),
   'region'  => 'us-east-1',
   'version' => 'latest',
));

$s3Client = S3Client::factory(array(
    'credentials' => array(
        'key'    => $aws_access_key_id,
        'secret' => $aws_secret_access_key,
    ),
   'region'  => 'us-east-1',
   'version' => 'latest',
));


?>

<!-- upload a new image to the system and network -->
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <title> SNAP IT</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
    <?php include('../includes/navbar.php'); ?>

    <div class="container">
   

         <form action="fridge_upload.php" method="post" enctype="multipart/form-data">
        <br>
        <p align="center">     <b><u>What's in your fridge today!</b></u>    <br> <br> Select image to upload:<br>
        <input type="file" name="fileToUpload" id="fileToUpload" ><br>
        <input type="image" alt="Submit" src="img_submit.png" name="submitbutton" value="Submit" width="45" height="45"><br>
        </p>
        </form>
            <?php
            
            // Check if image file is a actual image or fake image
            if(isset($_POST["submitbutton"])) {

                if(getimagesize($_FILES["fileToUpload"]["tmp_name"])) {
                $t = time();
                $uploadOk = 1;
                // $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if(($check['mime'] == 'image/png') || ($check['mime'] == 'image/jpg') || ($check['mime'] == 'image/jpeg') || ($check['mime'] == 'image/gif')) {
                    echo "<p> File is an image - " . $check["mime"] . ".</p>";
                    $uploadOk = 1;
                } else {
                    echo "<p> File is not an image.</p>";
                    $uploadOk = 0;
                }
            
                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 5000000) {
                    echo "<p>Sorry, your file is too large.</p>";
                    echo '<br>';
                    $uploadOk = 0;
                }
                // Allow certain file formats
                // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                // && $imageFileType != "gif" ) {
                //     echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
                //     echo '<br>';
                //     $uploadOk = 0;
                // }
                // Check if $uploadOk is set to 0 by an error
                

                if ($uploadOk == 0) {
                    echo "<p>Sorry, your file was not uploaded.</p>";
                    echo '<br>';
                // if everything is ok, try to upload file
                } else {
                   
                    $imageid = random_int(0,1000)+random_int(2000,3000);
                    $result = $s3Client->putObject(array(
                        'Bucket' => 'linefeed-images',
                        'Key'    => $imageid,
                        'SourceFile' => $_FILES["fileToUpload"]["tmp_name"],
                        'ContentType'  => $check['mime'],
                        'ACL'          => 'public-read',
                    ));
                    //Saving S3 image id of the item in a Session variable.
                    $_SESSION['fridge_imageid'] = $imageid;
                    header('Location: '.'fridge_image.php');
                    }
                
            }
            else {
                echo "<p>Please enter an image file</p>";
                echo "<br>";
            }
        }
            
        ?>      
    </div>
  </body>
</html>
            
        ?>      
    </div>
  </body>
</html>