<?php
$Invalid_pwd='';
$ID='';
$PWD='';
$invalid_search='';
$input='';
$msg='';
  include('../config.php');
  require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

// Create the AWS service builder, providing the path to the config file
  $dynamoDbClient = DynamoDbClient::factory(array(
    'credentials' => array(
        'key'    => $aws_access_key_id,
        'secret' => $aws_secret_access_key,
    ),
   'region'  => 'us-east-1',
   'version' => 'latest',
));
?>


<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <title>aLaCart: Sign UP</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
    <style type="text/css">
    body{
    height:100%;
    width:100%;
    margin-right: 5%;
    background-image:url("newuser.jpg");
    background-size:cover;
    background-size: cover;
    font-size: 16px;
    font-family: 'Oswald', sans-serif;
    font-weight: 300;
    margin: 0;
    align: right;
    }
    </style>
</head>

<body>

  
  <a href="home.php" color="white"><h4> aLaCart </h4></a>
  <div align:"center"; style="color: red; background:rgba(0, 0, 0, .70); font-size: 20px;" ><?php echo $msg ?></div>
<!-- checks for existing user, else enters new user details in the backed -->
  <?php
    if(isset($_POST["newuser_submit"])){ 
      $email = pg_escape_string($_POST["form_email"]);
      $result = $dynamoDbClient->getItem(array(
        'ConsistentRead' => true,
        'TableName' => 'userinfo',
        'Key' => array(
        'email_id' => array('S' => $email),
        )
      ));
      if($result['Item']['email_id']!=null){
        echo "<p align='center' float='center'>Email already exists. Please go back and login with your credentials.</p>";
    }
    else{
      $username = pg_escape_string($_POST["form_username"]);
          $firstname = pg_escape_string($_POST["form_first"]);
          $lastname = pg_escape_string($_POST["form_last"]);
          $password = $_POST['form_password'];
          $result = $dynamoDbClient->putItem(array(
          'TableName' => 'userinfo',
          'Item' => array(
            'email_id'      => array('S' => $email),
            'password' => array('S' => $password),
            'first_name' => array('S'=> $firstname),
            'last_name'   => array('S' => $lastname),
            'username' => array('S'=> $username),
            )
          ));
          echo "<p align='center' float='center'>New user created successfully. Please log in with your email ID and password on the login screen.</p>";
          echo '<a href="login.php><button> LOGIN </button></a>';
        } 
    } 
  ?>
    </div>

    
<!-- Form for user to fill out details about them -->
      <div align="center" float="center"> 
      <form align="center" method="POST">
        <p float ="center">
          <label>Username: <input type="text" name="form_username" required="" /></label><br>
          <label>First Name: <input type="text" name="form_first" required="" /></label><br>
          <label>Last Name: <input type="text" name="form_last" required="" /></label><br>
          <label>Email ID: <input type="text" name="form_email" required="" /></label><br>
          <label>Password: <input type="password" name="form_password" required="" /></label><br>
        <input type="submit" alt="Submit" src="submit.png" name="newuser_submit" width="80" height="80">
        <input type="reset" alt="Submit" src="submit.png" name="resetbutton" width="80" height="80">
        </p>
      </form>
      </div>
</body>
</html>
