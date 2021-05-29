<?php

//includes db connection
require_once '../database_connection.php';

// Start Session
session_start();

//redirects user to log in page if not already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
	//if already logged in, redirects user to homepage
	$_SESSION['already_li'] = true;
	header('Location: ../index.php');
	exit();
  }

$Username = strtolower(trim($_POST['username']));
$Email = strtolower(trim($_POST['email']));
$Password = trim($_POST['password']);

//checks if username or email are already taken
$stmt = "select * from users where username = :username or email =:email";

//preparese db query
$query = $db->prepare($stmt);

//binds paramenters
$query->bindParam(':username', $Username);
$query->bindParam(':email', $Email);

//runs query and gets results
$query->execute();
$result = $query->fetchAll();

//if username or email are taken, returns to sign up page
if ($result) {
		$_SESSION['signup_failed'] = true;
		header('Location: ../user_signup.php');
}

else {
	$stmt = "insert into users (username, password, email)
				values(:username, :password, :email)";
				
	//preparese db query
	$query = $db->prepare($stmt);

	//hashes password
	$Password = password_hash($Password, PASSWORD_DEFAULT);
	
	//binds parameters
	$query->bindParam(':username', $Username);
	$query->bindParam(':email', $Email);
	$query->bindParam(':password', $Password);

	//returns user to homepage if registration was sucessful
	if($query->execute()) {
		$_SESSION['username'] = $Username;
		$_SESSION['su_success'] = true;
		$_SESSION['logged_in'] = true;
		header('location: ../index.php');
	}
	
	//returns user to sign up page if there was an error
	else {
	    $_SESSION['signup_error'] = true;
	    header('Location: ../user_signup.php');
	}
}

	//closes db connection
	$db = null;
	exit();
?>
