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
    <title>aLaCart: Fridge</title>
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

              echo 'Recommendations for our top rated recipes you may like<br>';
                 $iterator = $dynamoDbClient->getIterator('Scan', array(
                    'TableName'     => 'multimedia'
                ));
              
              foreach ($iterator as $item) {
                  // Grab the time number value
                $rating = $item['rating']['S'];
                if((trim($rating)=='8')||(trim($rating)=='9')||(trim($rating)=='10')){
                  echo "<button><a href='home.php'> This recipe got a rating of:   ". $rating . " on 10 stars<br>";
                  echo "<b> <img src='". $item['url']['S'] ."' width='150' height='100'></b><br>";
                  echo $item['description']['S']."<br></a></button>";
                                    }
                                    } 
            echo '<img float="center" align="center" src="chef.gif" width="300" height="300">';
                echo '<br>Welcome back!! Lets check out your FRIDGE! <br>';
                echo '<br>';
                echo '_______________________________________________________<br><br>';         

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
                  echo "<b><u>".strtoupper($item['item']['S']) . "</u></b>";
                  echo "<i>   on:   ". $item['date']['S'].' </i><br>';
                  $fridgeitem = $item['item']['S'] . "\n";
                  array_push($stack, $fridgeitem);
                  // Grab the error string value
              }

              $_SESSION['fridge']=$stack;
              echo '<br>_______________________________________________________<br>';

              if(count($stack) > 0){
                echo 'Your fridge has '.count($stack).' items!<br><br>';
                echo 'Please enter your number to deliver your fridge contents to your phone';
                echo "<form name='form' method='post' action='test.php'>";
                echo "<input type='text' name='form_number'/></label><br>";
                echo "<input type='submit' alt='Submit' src='submit.png' name='newuser_submit' width='80' height='80'>";
                echo "</form>";
              }

                else{
                  echo 'Your fridge is empty! ';
                }

                // echo 'Your fridge contains '.($count-1).' items! <br><br>';
                echo '<br><br><a href="fridge_add.php"><button>*  add  *</button></a>';
                echo '<a href="fridge_delete.php"><button>*  delete  *</button></a>';
                echo '<a href="recipes.php"><button>*  find recipes  *</button></a>';
                
              }  
        ?>
    </div>
  </body>
</html>
