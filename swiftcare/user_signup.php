<?php
	// Start Session
	session_start();

	$notice = '';

	//checks if admin is already logged in
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
		//if already logged in, redirects admin to control panel
		$_SESSION['already_li'] = true;
		header('Location: ../index.php');
		exit();
  	}

	// checks if the user tried to sign up and failed
	if (isset($_SESSION['signup_failed']) && $_SESSION['signup_failed'] == true) {
		$notice = '<p class = "SignikaText" id = "IncorrectInfo">Username or email already taken.</p>';

		unset($_SESSION['signup_failed']);
	}

	//checks if there was an error during sign up
	if (isset($_SESSION['signup_error']) && $_SESSION['signup_error'] == true) {
		$notice = '<p class = "SignikaText" id = "IncorrectInfo">An error occurred while signing up.</p>';

		unset($_SESSION['signup_failed']);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Swift Care - User Signup</title>
		<meta charset = "utf-8"/>
		<meta name = "author" content = "SE2 - Group 2"/>
		<meta name = "description" content = "Main store page of Swift Care"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>	
		<!-- First link refers to the main.css file and second link is used for website icon -->
		<link rel = "stylesheet" href = "css/blue_page.css" type = "text/css">
		<link rel = "stylesheet" href = "css/main.css" type = "text/css"/>
		<link rel = "icon" href = "images/sc_logo_CY8_icon.ico"/>
	</head>
	<body>
		<!-- Create a form that takes a username, email, and password. If the requirements are met, then post the user details to the database -->
		<div class = "BoxPanel" id = "LoginPanel" style = "height: 450px">
			<div style = "display: flex; align-items: center">
				<img class = "LogoSmall" id = "LogoForm" src = "images/sc_logo.png" alt = "Swift Care logo"/>
				<h1 class = "OrbitronTitle" id = "TitleForm">Swift Care - User Sign Up</h1>
			</div>
			<form action = "process/signup_process.php" method = "post" autocomplete = "off">
				<div class = "marginContentSpace">
					<label class = "SignikaLabel" id = "LabelForm" for = "username_sec">Username:</label>
					<input class = "InputField" id = "LoginSignupField" name = "username" type = "name" id = "username_sec" placeholder = "username123" required/>
				</div>
				<div class = "marginContentSpace">
					<label class = "SignikaLabel" id = "LabelForm" for = "email_sec">E-Mail:</label>
					<input class = "InputField" id = "LoginSignupField" name = "email" type = "email" id = "email_sec" placeholder = "you@email.com" required/>
				</div>
				<div class = "marginContentSpace">
					<label class = "SignikaLabel" id = "LabelForm" for = "pass_sec">Password:</label>
					<input class = "InputField" id = "LoginSignupField" name = "password" type = "password" id = "pass_sec" placeholder = "********" required/>
				</div>
				<!--TODO: stylize notices-->
				<?php echo $notice ?>
				<input class = "SubmitButton" type = "submit" value = "Submit"/>
			</form>
			<a href = "user_login.php">
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
</html>