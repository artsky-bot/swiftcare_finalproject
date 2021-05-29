<?php
//includes db connection
require_once 'database_connection.php';

//includes session info
session_start();

$notice = "";

//if user is logged in, allows them to access cart and profile pages
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
	$user_icon_link = "user_profile.php";
	$cart_icon_link = "cart.php";
	$message_box = "<div class = 'BoxPanel' id = 'MessagePanel'>".$notice."</div>";
	$logout_icon = "<a href = 'process/process_logout.php'><img class = 'Icon' id = 'UserLogoutIcon' src = 'images/sc_logout.png' alt = 'Logout Icon'/></a>";
	//TODO: create page for user profile
}

//if user is not logged in, redirects them to log in page if they click profile or cart icon
else {
	$user_icon_link = "user_login.php";
	$cart_icon_link = "user_login.php";
	$message_box = "";
	$logout_icon = "";
}

//checks if user has newly logged in
if (isset($_SESSION['new_log']) && $_SESSION['new_log'] == true) {
	$notice = "<p class = 'SignikaText' id = 'MessageInfo'>You are now logged in!</p>";

	unset($_SESSION['new_log']);
}

//checks if user has been redirected because they were already logged in
if (isset($_SESSION['already_li']) && $_SESSION['already_li'] == true) {
	$notice = "<p class = 'SignikaText' id = 'MessageInfo'>You are already logged in.</p>";
	
	unset($_SESSION['already_li']);
}

//checks if user has newly signed up
if (isset($_SESSION['su_success']) && $_SESSION['su_success'] == true) {
$notice = "<p class = 'SignikaText' id = 'CorrectInfo' style = 'animation: none; margin-top: 0; margin-bottom: 0; margin-left: auto; margin-right: auto'>Signup successful!</p>";

unset($_SESSION['su_success']);
}

//checks if user has been redirected because item failed to add to cart
if (isset($_SESSION['atc_failed']) && $_SESSION['atc_failed'] == true) {
	$notice = "<p class = 'SignikaText' id = 'MessageInfo'>Unable to add item to cart. Please try again.</p>";
	
	unset($_SESSION['atc_failed']);
}

//checks if user has been redirected because item has been added to cart
if (isset($_SESSION['atc_success']) && $_SESSION['atc_success'] == true) {
	$notice = "<p class = 'SignikaText' id = 'MessageInfo'>Item successfully added to cart!</p>";

	unset($_SESSION['atc_success']);
}

//general search statement for if no search was performed
$stmt = 'select id, name, description, price, image from products';

//checks if input was sent through search bar; searches by term if so
if (!empty($_POST['searchbar'])) {
	$term = trim($_POST['searchbar']);
	$stmt = $stmt.' where name like :term';
}

//checks if input was sent through category selection; searches by category if so
if (!empty($_POST['sleep']) || !empty($_POST['pain']) || !empty($_POST['antacid']) || !empty($_POST['allergy'])) {
	$stmt = $stmt.' where';

	//adds sleep aid category if selected
	if (!empty($_POST['sleep'])) {
		$sleep = trim($_POST['sleep']);
		$stmt = $stmt.' category = :sleep';

		//checks if other categories were selected
		if (!empty($_POST['pain']) || !empty($_POST['antacid']) || !empty($_POST['allergy']))
			$stmt = $stmt.' or';
	}

	//adds pain relief category if selected
	if (!empty($_POST['pain'])) {
		$pain = $_POST['pain'];
		$stmt = $stmt.' category = :pain';

		//checks if other categories were selected
		if (!empty($_POST['antacid']) || !empty($_POST['allergy']))
			$stmt = $stmt.' or';
	}

	//adds antacid category if selected
	if (!empty($_POST['antacid'])) {
		$antacid = $_POST['antacid'];
		$stmt = $stmt.' category = :antacid';

		//checks if other categories were selected
		if (!empty($_POST['allergy']))
			$stmt = $stmt.' or';
	}

	//adds allergy category if selected
	if (!empty($_POST['allergy'])) {
		$allergy = $_POST['allergy'];
		$stmt = $stmt.' category = :allergy';
	}
}

//preparese db query
$query = $db->prepare($stmt);

//binds term parameter if not empty
if (!empty($term)) {
	$term = '%'.$term.'%';
	$query->bindParam(':term', $term);
}

//binds sleep parameter if not empty
if (!empty($sleep))
	$query->bindParam(':sleep', $sleep);

//binds pain parameter if not empty
if (!empty($pain))
	$query->bindParam(':pain', $pain);

//binds antacid parameter if not empty
if (!empty($antacid))
	$query->bindParam(':antacid', $antacid);

//binds allergy parameter if not empty
if (!empty($allergy))
	$query->bindParam(':allergy', $allergy);

//runs query and gets results
$query->execute();
$results = $query->fetchAll();

?>
<!DOCTYPE html>

<html>
	<head>
		<title>Swift Care - Home Page</title>
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
		<!-- This holds the top portion of the website with a box container that holds the logo, title, search bar, search button, user icon, and cart icon --> 
		<div class = "BoxPanel" id = "HeaderPanel">
			<img class = "LogoLarge" id = "ScLogoTitle" src = "images/sc_logo.png" alt = "Swift Care logo"/>
			<h1 class = "OrbitronTitle" id = "ScTitle">Swift Care</h1>
			<form style = "width: 70%" action = "index.php" method = "post">
				<button type = "submit" class = "searchButton">
					<img style = "width: 25px" src = "images/sc_search.png"/>
				</button>
				<input class = "InputField" type = "text" id = "SearchField" name = "searchbar" placeholder = "Search..."/>
			</form>
			<a href = "<?php echo $user_icon_link ?>">
				<img class = "Icon" id = "UserIcon" src = "images/sc_user.png" alt = "User Icon"/>
			</a>
			<?php echo $logout_icon; ?>
			<a href = "<?php echo $cart_icon_link ?>">
				<img class = "Icon" id = "ViewCartIcon" src = "images/sc_cart.png" alt = "Cart Icon"/>
			</a>
		</div>
		<!-- This is the second box container that holds categories of different medicine that can be searched for -->
		<!-- Names of categories changed to match categories of items in db; names and values of checkbox items changed for easier PHP handling -->
		<div style = "display: inline-block; vertical-align: top; margin-left: 8%; margin-top: 30px"> 
			<div class = "BoxPanel" id = "CategoryPanel">
				<h1 class = "SignikaTitle" id = "CategoryTitle">Category</h1>
				<form style = "margin-left: 20px;" action = "index.php" method = "post">
					<input type = "checkbox" name = "sleep" value = "Sleep Aid"/>
					<label class = "SignikaLabel" for = "sleep">Sleep Aid</label><br>
					<input type = "checkbox" name = "pain" value = "Pain Relief"/>
					<label class = "SignikaLabel" for = "pain">Pain Relief</label><br>
					<input type = "checkbox" name = "antacid" value = "Antacid"/>
					<label class = "SignikaLabel" for = "antacid">Antacid</label><br>
					<input type = "checkbox" name = "allergy" value = "Allergy Relief"/>
					<label class = "SignikaLabel" for = "allergy">Allergy Relief</label><br>
					<input class = "SubmitButton" type = "submit" value = "Submit"/>
				</form>
			</div>
			<!-- Dynamic message that displays a message to the user. -->
			<?php 
				if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && $notice != "") {
					echo "<div class = 'BoxPanel' id = 'MessagePanel'>".$notice."</div>";
				}
			?>
			<!-- Footer portion is used to showcase group number, college, and course. -->
			<footer style = "display:block; margin-top: 30px; margin-right: auto; margin-left: auto; text-align: center; width: 250px">
				<p class = "SignikaText" id = "footerText">
					&copy; 2021 - 2021 Group 2.<br/>
					Montclair State University - Computer Science and Technology.<br/>
					CSIT415 - Software Engineering II.
				</p>
			</footer>
		</div>
		<!-- all/searched products output -->
		<div class = "ProductPanel">
			<?php 
				if ($results) {
					//table is created
					foreach ($results as $row) {
						//starts a new row once 3 items have been output
						//each item is output in a table cell
						echo '
							<div style = "display: inline-block; padding: 20px; vertical-align: top;">
								<div class = "BoxPanel" style = "text-align: center; border-radius: 0px; width: 325px; height: 480px; padding: 20px; position: relative">
									<img style = "border: 2px solid black; width: 200px; height: 200px" src="'. $row['image'].'" alt="product image">
									<p class = "SignikaText" style = "color: black;">'.$row['name'].'</p>
									<p class = "SignikaText" style = "color: black;">'.$row['description'].'</p>
									<p class = "SignikaText" style = "color: yellow; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;">$'.$row['price'].'</p>
									<form action = "process/add_to_cart.php" method = "post" autocomplete = "off">
										<input type="hidden" name="prod_id" value="'.$row['id'].'">
										<input class = "SubmitButton" type = "submit" value = "Add to Cart"/>
									</form>
								</div>
							</div>
						';
					}
				}
			?>
		</div>
	</body>
	<?php
		//closes db connection
		$db = null;
		exit();
	?>
</html>