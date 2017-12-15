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
  echo $_SESSION['EmailID'];

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
    <title>aLaCart: WELCOME</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
      <?php include('../includes/navbar.php'); ?>
      <div class= "container" float ="left" align= "center">
      <p align = "center", float= "center">

    <?php 
      $items = json_decode($_GET['items']);
      $size = count($items);
      echo "Following are the items (or its variations) scanned from your fridge <br><i> click on the appropriate item to add to FRIDGE</i><br><br>";
      for ($i=0; $i<$size; $i++){
        echo "<button><a href='fridge_add_via_image.php?item=".$items[$i]."'>*  ".$items[$i]."  *</a></button>";
    }
    echo "<br><br>";
    ?>
      </p>
    </div>
  </body>
</html>

