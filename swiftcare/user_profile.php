<?php



?>
<!DOCTYPE html>
<html>
	<head>
		<title>Swift Care - User Panel</title>
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
		<!-- Create a form that takes a username, username, and password. If the requirements are met, then
			post the user details to the database -->
		<div class = "BoxPanel" id = "LoginPanel" style = "height: 250px">
			<div style = "display: flex; align-items: center">
				<img class = "LogoSmall" id = "LogoForm" src = "images/user_panel.png" alt = "Swift Care logo"/>
				<h1 class = "OrbitronTitle" id = "TitleForm">User - Control Panel</h1>
			</div>
			<div class = "marginContentSpace" style = "display: flex; align-items: center">
				<img class = "LogoSmall" id = "LogoLabel" src = "images/user_vpo.png">
				<a href = "user_vieworder.php" class = "ClickButton" id = "AdminPanelButton" style = "background-color: #283a89">View Your Orders</a>
			</div>
			</form>
			<!-- Log out button that sends admin back to home page -->
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
</html>
