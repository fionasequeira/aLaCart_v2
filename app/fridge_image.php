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
    <title>aLaCart: Clear Fridge</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
   <script type="text/javascript" >
    <?php echo 'var a ='.json_encode($_SESSION["fridge_imageid"]).';'?>
    try{
    var xmlHttp = new XMLHttpRequest();
    //xmlHttp.open( "GET","https://maps.googleapis.com/maps/api/geocode/json?address=None+&key=AIzaSyCQk5ru6Y-42Z1kcsiae_O6oHBnDW_ib5w"); 
     //var params = JSON.stringify({ "test": obj.value });
    xmlHttp.open( "POST","https://uo9n9ahww6.execute-api.us-east-1.amazonaws.com/prod/fridge_rekognition")
    xmlHttp.send(JSON.stringify({ search_value: a }));
   var rsp;
   xmlHttp.onreadystatechange = function() { 
      if(xmlHttp.status==200 && xmlHttp.readyState == 4){
        rsp = xmlHttp.responseText;
        // console.log(rsp);
        response = JSON.parse(rsp);
        items = JSON.stringify(response['body']['username']);
        window.location.href = "fridge_confirmation.php?items="+ items;
        //initMap(obj.value);
    }
    else{
      //rsp = xmlHttp.responseText;
        //alert(xmlHttp.status);
    }
    }
  }
catch(err) {
  alert(err.message);
  }
    
   </script>
    <?php include('../includes/navbar.php'); ?>

    <div class= "container" float ="left" align= "center">
      <p align = "center", float= "center">
        FETCHING ITEMS FROM IMAGE  .  .  .
      </p>
    </div>
  </body>
</html>

