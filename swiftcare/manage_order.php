<?php
    //include database connection
    require_once 'database_connection.php';

    //include session info
    session_start();

    $notice = '';

    //checks if admin is logged in; if not, redirects to admin login page
    if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) {
        header('Location: admin_login.php');
        exit();
    }

    //checks if order authorization was successful; if so, informers user
    if (isset($_SESSION['po_success']) && $_SESSION['po_success'] == true) {
        $notice = "<p class = 'SignikaText' id = 'CorrectInfo' style = 'animation: none; margin-top: 0; margin-bottom: 0; margin-left: auto; margin-right: auto'>Order successfully authorized!</p>";

        unset($_SESSION['po_success']);
    }

    //checks if order authorization was successful; if so, informers user
    if (isset($_SESSION['po_failed']) && $_SESSION['po_failed'] == true) {
        $notice = "<p class = 'SignikaText' id = 'CorrectInfo' style = 'animation: none; margin-top: 0; margin-bottom: 0; margin-left: auto; margin-right: auto'>Order authorization failed.</p>";

        unset($_SESSION['po_failed']);
    }
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
        <div class="BoxPanel" id="LoginPanel" style="min-height: 500px; width: 700px">
                <div style="display: flex; align-items: center">
                    <img class="LogoSmall" id="LogoForm" src="images/admin_vpo.png" alt="Swift Care logo" />
                    <h1 class="OrbitronTitle" id="TitleForm" style="color: #a10000">Admin - Unprocessed Orders</h1>
                </div>
            <center style = "margin-top: 50px">
                <?php echo $notice ?>
                <?php 
                //gets unprocessed orders
                $stmt = 'select * from orders where adID = 0';
                $query = $db->prepare($stmt);
                $query->execute();
                $results = $query->fetchAll();

                if (empty($results))
                    echo '<p class = "SignikaText" id = "MessageInfo" style = "margin-top: 50px; margin-bottom: 50px">No unprocessed orders.</p>';

                else {
                    echo '<table>
                        <tr>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Order ID</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Total Price</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">User ID</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Timestamp</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Authorized By</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Order Details</p></th>
                        </tr>';

                    //outputs each order as a row
                    foreach ($results as $row) {
                        echo '<tr>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['orderID'].'</p></td>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">$'.$row['totalPrice'].'</p></td>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['UID'].'</p></td>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['timeStamp'].'</p></td>
                            <td>
                                <form action="process/process_order.php" method="post">
                                    <input type="hidden" name="order_id" value="'.$row['orderID'].'">
                                    <input class = "RowButton" style = "margin-left: auto; margin-right: auto; width: 80px; background-color: #a10000" type="submit" value="Authorize">
                                </form>
                            </td>
                            <td>
                                <form action="manage_order_details.php" method="post">
                                    <input type="hidden" name="order_id" value="'.$row['orderID'].'">
                                    <input class = "RowButton" style = "margin-left: auto; margin-right: auto; width: 100px; background-color: #a10000" type="submit" value="View Details">
                                </form>
                            </td>
                        </tr>';
                    }

                    echo '</table>';
                }
                ?>
                <div class="form">
                    <a href = "admin_panel.php">
                        <img class = "Icon" id = "UserIcon" style = "position: absolute; left: 20px; bottom: 25px;" src = "images/back_arrow_admin.png" alt = "Back Arrow"/>
                    </a>
                </div>
            </center>
        </div>
        <div class="BoxPanel" id="LoginPanel" style= "min-height: 500px; width: 700px">
                <div style="display: flex; align-items: center">
                    <img class="LogoSmall" id="LogoForm" src="images/admin_vpo.png" alt="Swift Care logo" />
                    <h1 class="OrbitronTitle" id="TitleForm" style="color: #a10000">Admin - Processed Orders</h1>
                </div>
            <center style = "margin-top: 50px">
            <?php 
                //gets processed orders
                $stmt = 'select * from orders where adID <> 0';
                $query = $db->prepare($stmt);
                $query->execute();
                $results = $query->fetchAll();

                if (empty($results))
                    echo '<p class = "SignikaText" id = "MessageInfo" style = "margin-top: 50px; margin-bottom: 50px">No processed orders.</p>';

                else {
                    echo '<table>
                        <tr>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Order ID</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Total Price</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">User ID</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Timestamp</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Authorized By</p></th>
                            <th><p class = "SignikaText" style = "color: black; margin: 0">Order Details</p></th>
                        </tr>';

                    //outputs each order as a row
                    foreach ($results as $row) {
                        echo '<tr>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['orderID'].'</p></td>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">$'.$row['totalPrice'].'</p></td>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['UID'].'</p></td>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['timeStamp'].'</p></td>
                            <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['adID'].'</p></td>
                            <td>
                                <form action="manage_order_details.php" method="post">
                                    <input type="hidden" name="order_id" value="'.$row['orderID'].'">
                                    <input class = "RowButton" style = "margin-left: auto; margin-right: auto; width: 100px; background-color: #a10000" type="submit" value="View Details">
                                </form>
                            </td>
                        </tr>';
                    }

                    echo '</table>';
                }
            ?>
                <!-- Delete Function Here -->
                <div class="form">
                    <a href = "admin_panel.php">
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