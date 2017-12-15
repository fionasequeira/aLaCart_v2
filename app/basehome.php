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
            echo 'WELCOME to your aLaCart homepage ! <br>';
            echo '<br>';
            if(isset($_SESSION['EmailID'])){
              $email = $_SESSION['EmailID'];
             $result = $dynamoDbClient->getItem(array(
            'ConsistentRead' => true,
            'TableName' => 'userinfo',
            'Key' => array(
            'email_id' => array('S' => $email),
            )
      ));
      $email_id = $result['Item']['email_id']['S'];

      $result2 = $dynamoDbClient->getItem(array(
            'ConsistentRead' => true,
            'TableName' => 'feedback',
            'Key' => array(
            'email_id' => array('S' => $email),
            )
      ));

              // Checks if user has previously assigned a display picture for himself, else notifies the user
                $username = $result['Item']['username']['S'];
                $image = $result2['Item']['recipe']['S'];
                $recipeurl = $result2['Item']['recipeurl']['S'];
                $id = $result2['Item']['id']['S'];
                echo '<a href="fridge.php"><img float="center" align="center" src="food1.jpg" width="300" height="200"></a>___________VS___________<a href="pincode.php"><img float="center" align="center" src="food2.png" width="300" height="200"></a><br><br>';
                echo 'Home-cooked or Hotel? Always a dilemma! <br>Hi there '.$username.'! We are here to help! <br><br><br>';
                // User has an option to upload an image with ready, set, shutter
                echo '<button float ="left"><a href = "fridge.php"><img float="center" align="center" src="fridge.png" width="50" height="50"><br>In a moood for a homecooked meal? <br> Click here <br><i> update and view fridge contents <br> browse generated recipes based on the fridge</i></a></button>';
                echo '<button float="right"><a href = "pincode.php"><img float="center" align="center" src="vehicle.gif" width="50" height="50"><br>In a moood for a restaurant meal? <br> Click here <br><i> view restaurants in any area on the map <br> quick information on pricing/ratings </i></a></button>';
                echo '<br><br>';
                // $query= "select last_log_in from fridgeinfo where email_id='".$_SESSION['EmailID']."';";
                // $result=pg_query($query);
                // if($row = pg_fetch_row($result)){
                //   echo 'Your fridge was last updated on '.$row[0].' ! <br>';
                // }
                echo '-----------------------------------------------------------------------<br>';
                echo 'Share a PICTURE about your latest ventures via aLaCart!<br>';
                echo 'Here is where you left off at your last session. Have you shared your feedback yet?<br>';
                echo '<button><a href ="'.$recipeurl.'">'.$id.'<img src="'.$image.'" width=100 height=100></button>';
                echo '<a href="upload_photo.php?id='.$id.'"><img float="center" align="center" src="camera.png" width="60" height="60"></a><br><br>';
                echo 'Click on LiveFeed button to check out activities from other aLaCart users<br>';
                echo '<button><a href="home.php" class="button">  LIVEFEED </a></button>';
            }
            
        ?>
      </p>
    </div>
  </body>
</html>
