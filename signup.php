<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../mail/SendGridEmail.php';
ob_start("ob_gzhandler");
session_start();
//retrieve our data from POST
$email = $_POST['email'];
$password = $_POST['password'];
$type = $_POST['type'];
function createSalt()
{
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 3);
}
include '../configuration.php';
$sqlConn = new sqlConnection;
$mysqli = $sqlConn->mysqli;
$sql = "SELECT id FROM member WHERE email='$email'";
$result = $mysqli->query($sql);
if (mysqli_num_rows($result) > 0)
{
    $message = [0 => "This email already exists!"];
}
else
{
	$hash = hash('sha256', $password);
	$salt = createSalt();
	$password = hash('sha256', $salt . $hash);
	$sql = "INSERT INTO member (password, email, salt, type) VALUES ('$password', '$email', '$salt', '$type')";
	$result = $mysqli->query($sql);
	
	$message = [0 => "success"];
	$_SESSION['is_logged_in'] = 1;
    $_SESSION['email'] = $email;
    $sql = "SELECT id FROM member WHERE email='$email'";
	$result = $mysqli->query($sql); // Execute the query
	for ($res = array(); $tmp = $result->fetch_array(MYSQLI_NUM);) $res[] = $tmp;
	$mysqli->close();
	$id=$res[0][0];
	$doc = new DOMDocument();
	$doc->loadHTMLFile("../emailTemplate/signup.html");
	$html = $doc->saveHTML();
	$html = str_replace("%id%", $id, $html);
	$subject = "Welcome to Pengu!";
	sendEmail($email, 'noreply@pengu.co.uk', $subject, $html);	
}
echo json_encode($message);
?>