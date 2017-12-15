<?php
  $invalid_search='';
  $input='';
  include('../includes/db_connect.php');  

  $id= "select user_id from userinfo where email_id like '".$_SESSION['EmailID']."';";
              $result1=pg_query($id);
              $id_op=pg_fetch_row($result1);
  $query="Update fridgeinfo set last_log_in=current_timestamp where user_id='".$id_op[0]."';";
  $execute=pg_query($query);
  
  session_start();
  if(!isset($_SESSION['EmailID'])){
    session_destroy();
    header('Location: '.'login.php');
  }
?>

<!-- upload a new image to the system and network -->
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <title>Shuttershots: SNAP IT</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
    <?php include('../includes/navbar.php'); ?>

    <div class="container">
   

        <form align="center" action="restaurant.php" method="POST">
         <p float ="center">
          PINCODE <input type="number" name="name" require=""><br>
      <input type="submit" alt="Submit"  name="submitbutton" width="80" height="80">
        </p>
        </form>
    </div>
  </body>
</html>