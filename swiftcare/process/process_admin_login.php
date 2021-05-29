<?php
//includes db connection
require_once '../database_connection.php';

// Start Session
session_start();

//db query used to enter hashed password for manually entered admin account
/*
$pass = password_hash('Blah5', PASSWORD_DEFAULT);
$query = $db->prepare("update admins set password = :pass where username = 'super23'");
$query->bindParam(':pass', $pass);
$query->execute();
*/

//checks if admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) {
  //if already logged in, redirects admin to control panel
  $_SESSION['admin_already_li'] = true;
  header('Location: ../admin_panel.php');
  exit();
}

$username = strtolower(trim($_POST['username']));
$password = trim($_POST['password']);

$stmt = "select * from admins where username = :username";

//preparese db query
$query = $db->prepare($stmt);

//binds paramenters
$query->bindParam(':username', $username);

//runs query and gets results
$query->execute();
$result = $query->fetch();


//checks if username exists in db
if (!$result) {
	// if username does not exist, redirects to login page
	$_SESSION['login_fail'] = true;
	header('Location: ../admin_login.php');
}

//checks if password is correct
else if (password_verify($password, $result['password'])) {
    //if password is correct, redirects to homepage
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin'] = $username;
    $_SESSION['admin_new_log'] = true;
    header('Location: ../admin_panel.php');
}

//if password is incorrect, redirects to login page
else {
    $_SESSION['login_fail'] = true;
    header('Location: ../admin_login.php');
}

	//closes db connection
	$db = null;
	exit();
?>