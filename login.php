<?php
ob_start("ob_gzhandler");
session_start();
//retrieve our data from POST
$email = $_POST['email'];
$password = $_POST['password'];

// Connect to DB
include '../configuration.php';
$sqlConn = new sqlConnection;
$mysqli = $sqlConn->mysqli;

$sql = "SELECT password, salt FROM members WHERE email='$email'";
$result = $mysqli->query($sql);

if (mysqli_num_rows($result) === 0)
{
    $message = [0 => "The email is invalid. Please try again, or sign up."];
}
else
{
	$passArr = mysqli_fetch_assoc($result);
	$passhash = $passArr['password'];
	$salt = $passArr['salt'];
	if (hash('sha256', $salt.hash('sha256', $password)) === $passhash) {
	    $message = [0 => "success"];
	    $_SESSION['is_logged_in'] = 1;
      	$_SESSION['email'] = $email;
	} else {
	    $message = [0 => "Invalid password. Please try again, or request a new one."];
	}
	$mysqli->close();
}
echo json_encode($message);
?>