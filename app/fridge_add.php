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
    <title>aLaCart: Update Fridge</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
    <?php include('../includes/navbar.php'); ?>

    <div class="container">
   

        <form align="center" method="POST">
         <p>
      <label>Name: <input type="text" name="name" required="" /></label><br>
      <label>Date (Eg: 01-January-2017): <input type="text" name="date" required="" /></label><br> 
      <input type="submit" alt="Submit"  name="submitbutton" width="80" height="80">   
      </form>
      <form align="center">
      <p>
      OR share a PICTURE of your groceries and we will add it to your alacart FRIDGE!<br>
      <a href ="fridge_upload.php"><img src="flash.gif" width="100" height="80"></a>
    </form>
            <?php
            
            // Check if image file is a actual image or fake image
            if(isset($_POST["submitbutton"])) {
              $item= $_POST['name'];
              $date =$_POST['date'];
              $email = $_SESSION['EmailID'];
              $id = time();

              $fridgeitems = $_SESSION["fridge"];
              $count = count($fridgeitems);
              $flag =0;

              while($count!=0){
                if (trim(strtolower($item)) == trim(strtolower($fridgeitems[$count-1]))) {
                  $flag=$flag+1;
                }
                $count = $count-1;
              }

              if($flag==0){
                $item=strtolower($item);
              $result = $dynamoDbClient->putItem(array(
                  'TableName' => 'fridgeinfo',
                  'Item' => array(
                    'email_id'   => array('S' => $email),
                    'id' => array('N' => (string)$id),
                    'item' => array('S' => $item),
                    'date' => array('S'=> $date),
                  )

          ));
              echo "<p>".$item." has been successfully added! <br>Add more items or Click on the Fridge Icon on the navigation bar to view updates </p>";
                        echo '<br>';
              }

              else {
                 echo "<p>".$item." is already present! Please fill the above for the next item </p>";
                        echo '<br>';

              }

             }
        ?>     
    </div>
  </body>
</html>