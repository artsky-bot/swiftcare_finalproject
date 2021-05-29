<?php 
	//includes database connection
	require_once 'database_connection.php';

	//includes session info
	session_start();

	//redirects user to log in page if not already logged in
	if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    	header('location: user_login.php');
		exit();
	}

	//checks if user has been redirected because item failed to add to cart
	if (isset($_SESSION['rfc_failed']) && $_SESSION['rfc_failed'] == true) {
		$notice = "<p>Unable to remove item from cart. Please try again.</p>";
	
		unset($_SESSION['rfc_failed']);
	}

	//checks if user has been redirected because item failed to add to cart
	if (isset($_SESSION['udc_failed']) && $_SESSION['udc_failed'] == true) {
		$notice = "<p>Unable to update item quantity in cart. Please try again.</p>";
	
		unset($_SESSION['udc_failed']);
	}

	//gets ID of logged in user
	$stmt = 'select id from users where username = :username';
	$query = $db->prepare($stmt);
	$query->bindParam(':username', $_SESSION['username']);
	$query->execute();
	$return = $query->fetch();

	//gets all items stored in cart for logged in user
	$stmt = "select prod_id, qty from cart where user_id = ".$return['id'];
	$query = $db->prepare($stmt);
	$query->execute();
	$results = $query->fetchAll();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Swift Care - User Cart</title> 
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
		<!-- Outputs a table of items in cart -->
		<div class = "BoxPanel" id = "LoginPanel" style = "width: 600px;">
			<div id = "imageTextCenter">
				<img class = "LogoSmall" id = "LogoForm" src = "images/sc_logo.png" alt = "Swift Care logo"/>
				<h1 class = "OrbitronTitle" id = "TitleForm">Swift Care - User Cart</h1>
			</div>
			<?php 
				//if cart is empty, outputs notice informing user and allows them to return to homepage
				if (empty($results)) {
					echo '
					<p class = "SignikaText" id = "MessageInfo" style = "margin-top: 50px; margin-bottom: 50px">No items in cart.</p>
					<a href = "index.php">
						<img class = "Icon" id = "UserIcon" style = "position: absolute; left: 20px; bottom: 25px;" src = "images/back_arrow.png" alt = "Back Arrow"/>
					</a>';
				}
				
				else {
					$total = 0; //holds the total price of all items

					//outputs table to hold all items in cart
					echo '<div class = "marginContentSpace">
					<table style = "width: 100%; border-spacing: 0">
					<tr>
						<th class = "TableHeader" style = "background-color: #283a89; text-align: left;">Product</th>
						<th class = "TableHeader" style = "background-color: #283a89;">Quantity</th>
						<th class = "TableHeader" style = "background-color: #283a89; text-align: right;">Subtotal</th>
					</tr>
					</div>';

					//outputs row for each item
					foreach ($results as $row) {
						$stmt = "select * from products where id = :prod_id";
						$query = $db->prepare($stmt);

						$query->bindParam(":prod_id", $row['prod_id']);
						$query->execute();
						$item = $query->fetch();

						$subtotal = $item['price'] * $row['qty'];
						echo '
					<tr>
						<td>
							<img class = "CartIcon" src="'.$item['image'].'" alt="product image"> 
							<p class = "SignikaText" id = "CartProductText">'.$item['name'].'</p> 
							<p class = "SignikaText" id = "CartPriceText">Price: $'.$item['price'].'</p>
							<form action="process/remove_from_cart.php" method="post">
								<input type="hidden" name="prod_id" value="'.$row['prod_id'].'">
								<input class = "RowButton" style = "margin-left: 0; margin-right: auto; position: relative; left: 10px" type="submit" value="Remove">
							</form>
						</td>
						<td style = "padding: 0; margin: 0">
							<form action="process/update_cart.php" method="post">
								<input type = "number" name = "qty" size = "1" min = "1" max = "10" value ="'.$row['qty'].'">
								<input type="hidden" name="prod_id" value="'.$row['prod_id'].'">
								<input class = "RowButton" style = "margin-left: auto; margin-right: auto" type = "submit">
							</form>
						</td>
						<td>
							<p class = "SignikaText" id = "SubtotalText">$'.$subtotal.'</p> 
						</td>
					</tr>';

					//accumulates subtotal
					$total += $subtotal;
					}

				//closes table and outputs total
				echo '
				</table>
				<table class = "TotalTable">
					<tr>
						<th class = "TableHeader" id = "TotalText">Total:</th>
						<td id = "TotalQuantityText">$'.$total.'</td>
					</tr>
				</table>
				<a class = "ClickButton" id = "CheckoutButton" href = "user_checkout.php">Continue to Checkout</a>
				<a href = "index.php">
					<img class = "Icon" id = "UserIcon" style = "position: absolute; left: 20px; bottom: 20px;" src = "images/back_arrow.png" alt = "Back Arrow"/>
				</a>
				</div>';
				}
		?>
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
	<?php
		//closes db connection
		$db = null;
		exit();
	?>
</html>
