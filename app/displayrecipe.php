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
      $url = $_GET['url'];
      $id = $_GET['id'];
      echo $url;
      echo $id;
      // $id = $_GET['id'];
      echo "Here is your ingredient list for the selected recipe: ".$id."<br>";

      
        // echo "<img src ='".$main[$i]['smallImageUrls'][0]."' width=300 length=200><br>";
        // echo  $main[$i]['rating']." stars on 5<br><br>";
        // echo  "Ingredients :<br".$main[$i]['ingredients']."br>";
    ?> 
      </p>
    </div>
  </body>
</html>

