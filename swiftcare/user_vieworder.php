<?php
//include database connection
require_once 'database_connection.php';

//include session info
session_start();

$notice = '';

//checks if user is logged in; if not, redirects to user login page
if (!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header('Location: user_login.php');
}

//checks if order authorization was successful; if so, informers user
if (isset($_SESSION['po_success']) && $_SESSION['po_success'] == true) {
    $notice = '<p>Order successfully authorized!</p>';

    unset($_SESSION['po_success']);
}

//checks if order authorization was successful; if so, informers user
if (isset($_SESSION['po_failed']) && $_SESSION['po_failed'] == true) {
    $notice = '<p>Order authorization failed.</p>';

    unset($_SESSION['po_failed']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Swift Care - User Orders</title>
    <meta charset="utf-8" />
    <meta name="author" content="SE2 - Group 2" />
    <meta name="description" content="Main store page of Swift Care" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- First link refers to the main.css file and second link is used for website icon -->
    <link rel="stylesheet" href="css/blue_page.css" type="text/css">
    <link rel="stylesheet" href="css/main.css" type="text/css" />
    <link rel="icon" href="images/sc_logo_admin_K7A_icon.ico" />
</head>

<body>
    <!-- Create a form that takes a username, username, and password. If the requirements are met, then
			post the user details to the database -->
    <div class="BoxPanel" id="LoginPanel">
        <div style="display: flex; align-items: center">
            <img class="LogoSmall" id="LogoForm" src="images/user_panel.png" alt="Swift Care logo" />
            <h1 class="OrbitronTitle" id="TitleForm">User - View Orders</h1>
        </div>
        <center>
            <table class="marginContentSpace" style="margin-bottom: 50px">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="padding-right: 20px;">
                            <p class="SignikaText" style="color: black">Order ID</p>
                        </th>
                        <th scope="col" style="padding-right: 20px;">
                            <p class="SignikaText" style="color: black">Total Price</p>
                        </th>
                        <th scope="col" style="padding-right: 20px;">
                            <p class="SignikaText" style="color: black">Timestamp</p>
                        </th>
                        <th scope="col" style="padding-right: 20px;">
                            <p class="SignikaText" style="color: black"></p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // SQL statement to select all orders from orders table with a UID that matches the logged in users id
                        $stmt = $db->prepare("SELECT * FROM orders WHERE UID=?");
                        $stmt->execute([$_SESSION['id']]);
                        $results = $stmt->fetchAll();

                        if (empty($results)) {
                            echo '<p>No past orders.</p>';
                        } else {

                            // Outputs each order as a row
                            foreach ($results as $row) {
                                echo '<tr>
                            <th scope="row">
                            <p class="SignikaText" style="color: black">' . $row['orderID'] . '</p>
                        </th>
                        <td>
                            <p class="SignikaText" style="color: black">$' . $row['totalPrice'] . '</p>
                        </td>
                        <td>
                            <p class="SignikaText" style="color: black">' . $row['timeStamp'] . '</p>
                        </td>
                        <td><form action="user_order_details.php" method="post">
                        <button type="submit" class="searchButton" style = "margin-left: 10px" name="order_id" value="' . $row['orderID'] . '">
                                <p class="SignikaText" style="margin: 0; color: white">View Details</p>
                            </button></form></td>
                        </tr>';
                            }
                        }
                    } catch (PDOException $e) { // Shouldn't execute in production.
                        echo "Connection failed: " . $e->getMessage();  // Prints error messages while testing
                    }
                    ?>
                </tbody>
            </table>
            <a href="user_profile.php">
                <img class="Icon" id="UserIcon" style="position: absolute; left: 20px; bottom: 25px;" src="images/back_arrow.png" alt="Back Arrow" />
            </a>
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