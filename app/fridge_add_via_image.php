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

<!-- upload a new image to the system and network -->
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <title>aLaCart: SNAP to Fridge</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
    <?php include('../includes/navbar.php'); ?>

    <div class="container">
            <?php
            $main_item = $_GET['item'];
            // Check if image file is a actual image or fake image
              $fridgeitems = $_SESSION["fridge"];
              $date =$_POST['date'];
              $email = $_SESSION['EmailID'];
              $id = time();
              $count = count($fridgeitems);
              $flag =0;
              $date = time();

              while($count!=0){
                if (trim(strtolower($main_item)) == trim(strtolower($fridgeitems[$count-1]))) {
                  $flag=$flag+1;
                }
                $count = $count-1;
              }

              if($flag==0){
                $main_item=strtolower($main_item);
              $result = $dynamoDbClient->putItem(array(
                  'TableName' => 'fridgeinfo',
                  'Item' => array(
                    'email_id'   => array('S' => $email),
                    'id' => array('N' => (string)$id),
                    'item' => array('S' => $main_item),
                    'date' => array('S'=> (string)$date),
                  )
          ));

              
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
                  $fridgeitem = $item['item']['S'] . "\n";
                  array_push($stack, $fridgeitem);
                  // Grab the error string value
              }

              $_SESSION['fridge']=$stack;

              echo "<p align='center' float='center'>".$main_item." has been successfully added! <br>Add more items via your current image by clicking the + button or Upload a new image via camera <br><i> Click on the Fridge Icon on the navigation bar to view updates </i>";
              echo '<br><br><a href="fridge_image.php"><img src="add.png" width="60" height="60"></a><br>';
              echo '<a href="fridge_upload.php"><img src="flash.gif" width="300" height="200"><br>';
              echo '<br><br></p>';

              }

              else {
                 echo "<p align='center' float='center'>".$main_item." is already present!  <br>Add more items via your current image by clicking the + button or Upload a new image via camera <br><i> Click on the Fridge Icon on the navigation bar to view updates </i>";
              echo '<br><br><a href="fridge_image.php"><img float="center" src="add.png" width="60" height="60"></a>';
              echo '<a href="fridge_upload.php"><img src="flash.gif" float="center" width="100" height="80"><br><br><br></p>';

              }
        ?>     
    </div>
  </body>
</html>