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

  $dynamoDbClient = DynamoDbClient::factory(array(
    'credentials' => array(
        'key'    => $aws_access_key_id,
        'secret' => $aws_secret_access_key,
    ),
   'region'  => 'us-east-1',
   'version' => 'latest',
));



#executes on submit button FROM log in form, it checks for invalid user/password else starts session  
if(isset($_POST["submitbutton"])){

    if(empty($_POST['EmailID'])) {
        $ID= 'No Email ID input';
    }
    else if(empty($_POST['password'])) {
        $PWD= 'No Password idnput';
    }
    else{
        $email = pg_escape_string($_POST["EmailID"]);
        $password = pg_escape_string($_POST["password"]);
        $result = $dynamoDbClient->getItem(array(
        'ConsistentRead' => true,
        'TableName' => 'userinfo',
        'Key' => array(
        'email_id' => array('S' => $email),
        )
    ));
        if($result['Item']['email_id']['S']!=null) {
            echo 'SUCCESSFUL';
        if($password==($result['Item']['password']['S'])) {
            session_start();
            $email = $result['Item']['email_id']['S'];
            $_SESSION['EmailID']= $email;
            header('Location: '.'basehome.php');
        }   
        else {
            $Invalid_pwd = 'Username or Password is invalid. Please try again';
        }
    }
    else{
         $Invalid_pwd = 'Username or Password is invalid. Please try again';
    }
    }
}

?>

<!-- log in page -->
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Welcome to aLaCart</title>
    <style type="text/css">
    body{
    height:100%;
    width:100%;
    margin-right: 5%;
    background-image:url("home.jpeg");
    background-size:cover;
    background-size: cover;
    font-size: 16px;
    font-family: 'Oswald', sans-serif;
    font-weight: 300;
    margin: 0;
    align: right;
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    </style>
    <div float= "center" align="center">
         <form class="signin" action="login.php" align="center" method="post"> 

            <!-- FORM-Existing users and Visitors to shuttershots      -->
            <h4 class="heading">         aLaCart         </h4>
            <h3 align="center"><b>____________ What's on aLaCart, once you sign up? ____________<b></h3> 
            <h3 align="center"><i> tailor-made dishes to suit what's in your fridge </i></h3> 
            <h3 align="center"><i> find best eat-outs around around any location, </i></h3>
            <h3 align="center"><i> publish your recipes and restaurant ventures </i></h3> <br><br>   

            <h3 align="center"><b> Browse feature dishes by aLaCart users  </b></h3> <br>
            <div align="center">
                <button> <a href="browsehome.php" class="button"> aLaCart gallery </a></button> 
            </div>
            <br>
            <br>

            <!-- FORM-Existing user LOGIN       -->
            <h3 align="center"> Returning members, log in here! </h3>
            <input type="text" class="form-control" name="EmailID" placeholder="Email Address" autofocus="" />
            <input type="password" class="form-control" name="password" placeholder="Password" />      
            <br>
            <div align="center">
                <input type="image" alt="Submit" src="submit.png" name="submitbutton" value="Submit" width="70" height="70">
            </div> 
            <div align:"center"; style="color: red; background:rgba(0, 0, 0, .70); font-size: 15px;" ><?php echo $ID ?></div>
            <div align:"center"; style="color: red; background:rgba(0, 0, 0, .70); font-size: 15px;" ><?php echo $PWD ?></div>
            <div align:"center"; style="color: red; background:rgba(0, 0, 0, .70); font-size: 15px;" ><?php echo $Invalid_pwd ?></div>
            <br>              
        <div align="center">
                        <!-- New User SIGN UP LINK -->
        <button><a href="newuser.php" class="button">  Sign UP here! </a></button>
    </div>
</form>
<br>
<br> 
<br>
</body>
</html>

          