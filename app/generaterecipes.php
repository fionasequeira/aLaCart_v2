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
    <title>aLaCart: Find Recipes</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
      <?php include('../includes/navbar.php'); ?>
      <div class= "container" float ="left" align= "center">
      <p align = "center", float= "center">

    <?php 
      $url = base64_decode($_GET['url']);
      // $json = file_get_contents("'".$url."'");
      // $obj = json_decode($json);
      // echo $obj;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      $result = curl_exec($ch);
      curl_close($ch);

      $obj = json_decode($result,true);
      $main = $obj["matches"];
      $size = count($main);
      echo "Following are the recipes generated based on your fridge <br><i>click on the title to select the recipe and write a review</i><br><br>";
      for ($i=0; $i<$size; $i++){
        $id = $main[$i]['id'];
        $cooking_time = $main[$i]['totalTimeInSeconds']/60;
        $tagged_url = "http://www.yummly.co/recipes/".$id;
        echo "<a href='selectrecipe.php?id=".$id."&url=".$main[$i]['smallImageUrls'][0]."'>".$main[$i]['recipeName']." </a><br> <i>rated ".$main[$i]['rating']." stars on 5</i><br>";

        echo "<img src ='".$main[$i]['smallImageUrls'][0]."' width=300 height=200><br>";
        $tagged_url = "http://www.yummly.co/recipes/".$id;
        echo "Total Time :".$cooking_time." minutes";
        echo "<br><a href ='".$tagged_url. "'>Click here for Recipe</a><br>";
        echo "Ingredients: ";
        $count = count($main[$i]['ingredients']);
        $count = $count - 1;
        while($count>=0){
          echo $main[$i]['ingredients'][$count];
          echo ", ";
          $count =$count-1;
        }
        echo "<br><br>____________________________________________________________________<br><br>";

    }
        // curl_close($ch);
        if ($size == 0){
          echo "We're sorry, no recipes were generated for that combination, try selecting items which you wish to be present in the recipe";
        }
        // $obj = json_decode($json,true);
        // echo $obj;
      
    ?>   
      </p>
    </div>
  </body>
</html>

