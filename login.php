<?php
//Start session
session_start();

//Include database connection details
require_once('databaseconfig.php');

//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Connect to mysql server
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
  die('Failed to connect to server: ' . mysql_error());
}

//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
  die("Unable to select database");
}

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
  $str = @trim($str);
  if(get_magic_quotes_gpc()) {
    $str = stripslashes($str);
  }
  return mysql_real_escape_string($str);
}

//Sanitize the POST values
$username = clean($_POST['username']);
$password = clean($_POST['password']);

//Input Validations
if($username == '') {
  $errmsg_arr[] = 'User name missing';
  $errflag = true;
}
if($password == '') {
  $errmsg_arr[] = 'Password missing';
  $errflag = true;
}

//If there are input validations, redirect back to the registration form
if($errflag) {
  $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
  session_write_close();
  header("location: index.php");
  exit();
}

//Create INSERT query
$query="SELECT * FROM userinfo WHERE username='$username' AND password='".md5($password)."'";
$result = @mysql_query($query);

//Check whether the query was successful or not
if($result) {
  header("location: admin.php");
  exit();
}else {
  die("Query failed");
}
?>
