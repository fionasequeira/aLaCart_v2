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
            echo 'WELCOME to the aLaCart photo gallery! <br>';
            echo '<br><br><br> Below you will find updates from our network.<br><br>';
            echo 'Like what you see? <button><a href="newuser.php" class="button">  Sign UP here! </a></button>';
              echo '<br>';
              echo '<br>';
              $iterator = $dynamoDbClient->getIterator('Scan', array(
                    'TableName'     => 'multimedia'
                ));
              
              foreach ($iterator as $item) {
                  // Grab the time number value
                  echo "This recipe got a rating of:   ". $item['rating']['S'] . " on 10 stars</b><br>";
                  echo "<img src='". $item['url']['S'] ."' width='600' height='400'></b><br>";
                  echo $item['description']['S']."<br>";
                  echo "<i>submitted by  ". $item['email']['S']." on ".$item['date']['S']."<br>";
                  echo "<br><br>________________________________________<br><br>";         
            }
        ?>

      </p>
    </div>
  </body>
</html>