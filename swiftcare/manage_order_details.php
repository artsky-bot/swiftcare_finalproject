<?php
    //include database connection
    require_once 'database_connection.php';

    //include session info
    session_start();

    //gets order ID
    $order_id = $_POST['order_id']; 

    //checks if admin is logged in; if not, redirects to admin login page
    if (!(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true)) {
	    header('Location: admin_login.php');
	    exit();
    }

    //gets items in order
    $stmt = 'select * from orderitems where orderID = :order_id';
    $query = $db->prepare($stmt);
    $query->bindParam(':order_id', $order_id);
    $query->execute();
    $results = $query->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Swift Care - Order Details</title>
    <meta charset="UTF-8">
    <meta name="author" content="SE2 - Group 2" />
    <meta name="description" content="Main store page of Swift Care" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- First link refers to the main.css file and second link is used for website icon -->
    <link rel="stylesheet" href="css/red_page.css" type="text/css">
    <link rel="stylesheet" href="css/main.css" type="text/css" />
    <link rel="icon" href="images/sc_logo_admin_K7A_icon.ico" />
    <style>
        th {
            padding: 10px;
        }
        td {
            text-align: center;
        }
    </style>
</head>

<body>
        <div class="BoxPanel" id="LoginPanel" style="min-height: 300px; width: 500px">
                <div style="display: flex; align-items: center">
                    <img class="LogoSmall" id="LogoForm" src="images/admin_vpo.png" alt="Swift Care logo" />
                    <h1 class="OrbitronTitle" id="TitleForm" style="color: #a10000">Admin - Order Details - #<?php echo $order_id ?></h1>
                </div>
            <center style = "margin-top: 50px">
        		<table style = "margin-bottom: 75px">
		            <tr>
		                <th><p class = "SignikaText" style = "color: black; margin: 0">Product</p></th>
		                <th><p class = "SignikaText" style = "color: black; margin: 0">Price</p></th>
		                <th><p class = "SignikaText" style = "color: black; margin: 0">Quantity</p></th>
		            </tr>
            		<?php
		                foreach ($results as $row) {
		                    //gets name and price of product from ID
		                    $stmt = 'select name, price from products where id = :prod_id';
		                    $query = $db->prepare($stmt);
		                    $query->bindParam(':prod_id', $row['IID']);
		                    $query->execute();
		                    $return = $query->fetch();

                    		//outputs information in table row
		                    echo '<tr>
		                        <td><p class = "SignikaText" style = "color: black; margin: 0">'.$return['name'].'</p></td>
		                        <td><p class = "SignikaText" style = "color: black; margin: 0">$'.$return['price'].'</p></td>
		                        <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['quantity'].'</p></td>
		                    </tr>';
		                }
		            ?>
		        </table>
                <div class="form">
                    <a href = "manage_order.php">
                        <img class = "Icon" id = "UserIcon" style = "position: absolute; left: 20px; bottom: 25px;" src = "images/back_arrow_admin.png" alt = "Back Arrow"/>
                    </a>
                </div>
            </center>
        </div>
</body>
<!-- Footer portion is used to showcase group number, college, and course. -->
<footer style="text-align: center">
    <p class="SignikaText" id="footerText">
        &copy; 2021 - 2021 Group 2.<br />
        Montclair State University - Computer Science and Technology.<br />
        CSIT415 - Software Engineering II.
    </p>
</footer>

</html>