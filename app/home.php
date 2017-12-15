<?php
$Invalid_pwd='';
$ID='';
$PWD='';
$invalid_search='';
$input='';
  include('../config.php');
  require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

// Create the AWS service builder, providing the path to the config file
  session_start();
  if(!isset($_SESSION['EmailID'])){
    session_destroy();
    header('Location: '.'login.php');
  }

  $dynamoDbClient = DynamoDbClient::factory(array(
    'credentials' => array(
        'key'    => $aws_access_key_id,
        'secret' => $aws_secret_access_key,
    ),
   'region'  => 'us-east-1',
   'version' => 'latest',
));
?>

<!-- Directs existing user to livefeed from login page -->
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <title>aLaCart: LiveFeed</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
    <div class= "container" align= "center" float="center">
      <p align = "center", float= "center">
        <?php
            echo 'WELCOME to the aLaCart photo gallery! <br> Click previous/next to browse our users ventures';
            echo '<br>';
            if(isset($_SESSION['EmailID'])){

              echo 'Hi there<br>! Welcome back! <br><br>';
                // User has an option to upload an image with ready, set, shutter
              echo 'Share a PICTURE about your latest ventures via aLaCart!<br>';
              echo '<a href="upload_photo.php"><button>  UPLOAD  </button></a>';

              echo '<br><br><br> Below you will find updates from our network <br><br>';
              echo '<br>';
              echo '<br>';
              $iterator = $dynamoDbClient->getIterator('Scan', array(
                    'TableName'     => 'multimedia'
                ));
              
              foreach ($iterator as $item) {
                  // Grab the time number value
                  echo "This recipe got a rating of:   ". $item['rating']['S'] . " on 10 stars</b><br>";
                  echo "<b><u>Snapshot</u>:   <img src='". $item['url']['S'] ."' width='300' height='200'></b><br>";
                  echo "Description:".$item['description']['S']."<br>";
                  echo "<i> Added on  ". $item['date']['S']." </i> and submitted by  ". $item['email']['S']."<br>";
                  echo "<br><br>________________________________________<br><br>";
                  }          
            }
        ?>

      </p>
    </div>
  </body>
</html>