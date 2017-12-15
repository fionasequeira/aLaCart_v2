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
    <?php include('../includes/navbar.php'); ?>

    <div class= "container" align= "center" float ="center">
      <p align = "center", float= "center">
         <?php
            if(isset($_SESSION['EmailID'])){

              echo 'Recommendations for our top rated recipes you may like<br><br>';
                 $iterator = $dynamoDbClient->getIterator('Scan', array(
                    'TableName'     => 'multimedia'
                ));
              
              foreach ($iterator as $item) {
                  // Grab the time number value
                $rating = $item['rating']['S'];
                if((trim($rating)=='8')||(trim($rating)=='9')||(trim($rating)=='10')){
                  echo "<button> This recipe got a rating of:   ". $rating . " on 10 stars</b><br>";
                  echo "<b><u>Snapshot</u>:   <img src='". $item['url']['S'] ."' width='300' height='200'></b><br>";
                  echo "Description:".$item['description']['S']."</button>";
                                    }
                                    } 
            echo '<br><br>Welcome back!! Put on the chefs hat <br>';
            echo '<br><img float="center" align="center" src="chef.gif" width="300" height="300"><br>';
            echo '<br>';         

              $email = $_SESSION['EmailID'];
              $iterator = $dynamoDbClient->getIterator('Scan', array(
                    'TableName'     => 'fridgeinfo',
                    'ScanFilter' => array(
                    'email_id' => array(
                      'AttributeValueList' => array(
                          array('S' => $email)
                      ),
                    'ComparisonOperator' => 'EQ'
                  )
                )
              ));
              // echo $iterator;
              // Each item will contain the attributes we added
              $stack = array();
              foreach ($iterator as $item) {
                  // Grab the time number value
                  echo "<b><u>Item</u>:   ". $item['item']['S'] . "</b>\n";
                  echo "<i> Added on:   ". $item['date']['S'].' </i><br>';
                  $fridgeitem = $item['item']['S'] . "\n";
                  array_push($stack, $fridgeitem);
                  // Grab the error string value
              }

              $_SESSION['fridge']=$stack;

              if(count($stack) > 0){
                echo '<br><br>Your fridge has '.count($stack).' items! <br><br>';

              }

                else{
                  echo 'Your fridge is empty! <br><br>';
                }

                // echo 'Your fridge contains '.($count-1).' items! <br><br>';
                echo '<br><br><a href="fridge_add.php"><button>*  add  *</button></a>';
                echo '<a href="fridge_delete.php"><button>*  delete  *</button></a><br>';
                echo '<a href="recipes.php"><button>*  find recipes  *</button></a>';
                
              }  
        ?>
      </p>
    </div>
  </body>
</html>
