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
    <title>aLaCart: Delete Item</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
    <?php include('../includes/navbar.php'); ?>

    <div class="container">
   

        <form align="center" method="POST">
         <p float ="center">
      <label>Name: <input type="text" name="name" required="" /></label><br>
      <input type="submit" alt="Submit"  name="submitbutton" width="80" height="80">
        </p>
        </form>
            <?php
            
            // Check if image file is a actual image or fake image
            if(isset($_POST["submitbutton"])) {
              $item0 = $_POST['name'];
              $email = $_SESSION['EmailID'];
              $fridgeitems = $_SESSION["fridge"];
              $count = count($fridgeitems);
              $flag =0;
              $id=0;

              while($count>0){
                // echo $fridgeitems[$count-1];
                // echo strcmp($item0,$fridgeitems[$count-1]);
                if (trim(strtolower($item0)) == trim(strtolower($fridgeitems[$count-1]))) {
                  $flag=$flag+1;
                }
                $count = $count-1;
              }
        
              if($flag>0){
                $email = $_SESSION['EmailID'];
                $iterator = $dynamoDbClient->getIterator('Scan', array(
                    'TableName'     => 'fridgeinfo',
                    'ScanFilter' => array(
                    'email_id' => array(
                      'AttributeValueList' => array(
                          array('S' => $email)
                      ),
                    'ComparisonOperator' => 'EQ'
                  ),
                    'item' => array(
                      'AttributeValueList' => array(
                          array('S' => $item0)
                      ),
                    'ComparisonOperator' => 'EQ'
                  )
                )
              ));


                foreach ($iterator as $item) {
                  $id = $item['id']['N'];
                }
                
              $delete = $dynamoDbClient->deleteItem(array(
              'TableName' => 'fridgeinfo',
              'Key' => array(
                'id' => array('N' => (string)$id),
               )
              ));
              

              echo "<p>".$item0." has been successfully deleted! </p>";
                       echo '<br>';
              }

              else {
                 echo "<p>".$item0." was not found! </p>";
                        echo '<br>';

              }

             }
        ?>      
    </div>
  </body>
</html>