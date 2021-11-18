<?php
session_start();

require_once "pdo.php";

if ( isset($_POST['cancel'] ) ) {

  header("Location: index.php");
  return;
}


$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';


if ( isset($_POST['email']) && isset($_POST['pass']) ) {
  unset($_SESSION["name"]); 
  if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
      $_SESSION['error'] = "User name and password are required";
      header("Location: login.php");
      return; 
   } 

  else {
 
    $email = test_input($_POST["email"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "Email must have an at-sign (@)";
      header("Location: login.php");
      return; 
    }else {
      $check = hash('md5', $salt.$_POST['pass']);
      if ( $check == $stored_hash ) {
       
          $_SESSION['name'] = $_POST['email'];
          header("Location: index.php");
          error_log("Login success ".$_POST['email']);
          return;
      } else {
          $_SESSION['error'] = "Incorrect password";
          header("Location: login.php");
          error_log("Login fail ".$_POST['email']." $check");
          return; 
      }
    }
  }
}

function test_input($data) {

  $data = trim($data);

  $data = stripslashes($data);

  $data = htmlspecialchars($data);
  return $data;
}
?>

<!DOCTYPE html>
<html>
  <head>
  <title>Uttam Rao</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>

<body>

<div class="container">
<h1>Please Log In</h1>
<?php

if ( isset($_SESSION['error']) ) {
      echo('<p style="color:red">'.htmlentities( $_SESSION["error"] )."</p>\n");
      unset($_SESSION["error"]);
    }
if ( isset($_SESSION['success']) ) {
      echo('<p style="color:green">'.htmlentities( $_SESSION["success"] )."</p>\n");
      unset($_SESSION["success"]);
    }
?>
<form method="post">
  <p>User Name: <input type="text" name="email"></p>
  <p>Password: <input type="text" name="pass"></p>
  <input type="submit" value="Log In">
  <input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password, view source and find a password
in the HTML comments.
</p>
</div>
</body>


