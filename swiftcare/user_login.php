<?php
// Start Session
session_start();

$notice = "";

//checks if admin is already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true) {
	//if already logged in, redirects admin to control panel
	$_SESSION['already_li'] = true;
	header('Location: index.php');
	exit();
}

// checks if the user tried to login and failed
if (isset($_SESSION['login_fail']) && $_SESSION['login_fail'] == true) {
		$notice = "<p class = 'SignikaText' id = 'IncorrectInfo'>Incorrect login info! Please try again!</p>";
		unset($_SESSION['login_fail']);
}


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Swift Care - User Login</title> 
		<meta charset = "utf-8"/>
		<meta name = "author" content = "SE2 - Group 2"/>
		<meta name = "description" content = "Main store page of Swift Care"/>
		<meta name = "viewport" content = "width=device-width, initial-scale=1"/>
		<!-- First link refers to the main.css file and second link is used for website icon -->
		<link rel = "stylesheet" href = "css/blue_page.css" type = "text/css">
		<link rel = "stylesheet" href = "css/main.css" type = "text/css"/>
		<link rel = "icon" href = "images/sc_logo_CY8_icon.ico"/>
	</head>
	<body>
		<!-- Create a form that takes a username and password. If the requirements are met through the database, then the user will successfully log in. -->
		<div class = "BoxPanel" id = "LoginPanel" style = "height: 450px">
			<div id = "imageTextCenter">
				<img class = "LogoSmall" id = "LogoForm" src = "images/sc_logo.png" alt = "Swift Care logo"/>
				<h1 class = "OrbitronTitle" id = "TitleForm">Swift Care - User Login</h1>
			</div>

			<!-- Inside the box container, we have the labels and inputs for username and password. There is a link that sends a user to a signup page. -->
			<form action = "process/process_user_login.php" method = "post" autocomplete = "off">
				<div class = "marginContentSpace">
					<label class = "SignikaLabel" id = "LabelForm" for = "username_sec">Username:</label>
					<input class = "InputField" id = "LoginSignupField" name = "username" type = "name" id = "username_sec" placeholder = "username123" required/>
				</div>
				<div class = "marginContentSpace">
					<label class = "SignikaLabel" id = "LabelForm" for = "pass_sec">Password:</label>
					<input class = "InputField" id = "LoginSignupField" name = "password" type = "password" id = "pass_sec" placeholder = "********" required/>
				</div>
				<div class = "marginContentSpace">
					<a href = "user_signup.php" class = "ClickButton" id = "SignupButton">Don't have an account? Sign up here!</a>
				</div>
				<?php echo $notice;?>
				<input class = "SubmitButton" type = "submit" value = "Submit"/>
			</form>
			<a href = "index.php">
				<img class = "Icon" id = "UserIcon" style = "position: absolute; left: 20px; bottom: 25px;" src = "images/back_arrow.png" alt = "Back Arrow"/>
			</a>
		</div>
	</body>
	<!-- Footer portion is used to showcase group number, college, and course. -->
	<footer style = "text-align: center">
			<p class = "SignikaText" id = "footerText">
				&copy; 2021 - 2021 Group 2.<br/>
				Montclair State University - Computer Science and Technology.<br/>
				CSIT415 - Software Engineering II.
			</p>
	</footer>
		<a href = "admin_login.php">
			<img class = "Icon" id = "AdminIcon" src = "images/admin_gear.png" alt = "Admin Icon"/>
		</a>
</html>
