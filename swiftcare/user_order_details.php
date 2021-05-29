<?php

//include database connection

require_once 'database_connection.php';



//include session info

session_start();



//checks if user is logged in; if not, redirects to user login page

if (!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

    header('Location: user_login.php');

}



if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    header('Location: user_vieworder.php');

}



// SQL statements to delete order contents from orders and orderitems tables

if (isset($_POST['options'])) {

    $stmt = $db->prepare("DELETE FROM orders WHERE (orderID=?)");

    $stmt->execute([$_POST['options']]);

    $stmt = $db->prepare("DELETE FROM orderitems WHERE (orderID=?)");

    $stmt->execute([$_POST['options']]);



    header('Location: user_vieworder.php');

} else {

?>



<!DOCTYPE html>

<html>



<head>

    <title>Swift Care - Order Details</title>

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

    <div class="BoxPanel" id="LoginPanel">

        <div style="display: flex; align-items: center">

            <img class="LogoSmall" id="LogoForm" src="images/user_panel.png" alt="Swift Care logo" />

            <h1 class="OrbitronTitle" id="TitleForm">User - Order Details</h1>

        </div>

        <center>

            <?php

    // SQL statement to display order table contents with matching orderID

    try {

        $stmt = $db->prepare("SELECT * FROM orders WHERE orderID=?");

        $stmt->execute([$_POST['order_id']]);

        $results = $stmt->fetchAll();



        if (empty($results)) {

            echo '<p>No past orders.</p>';

        } else {



            //outputs order address and cardInfo as rows

            foreach ($results as $row) {

                echo '

                    <p class="SignikaText" style="color: black">' . $row['address'] . '</p>

                    <p class="SignikaText" style="color: black">' . $row['cardInfo'] . '</p>

                    ';

            }

        }

    } catch (PDOException $e) { // Shouldn't execute in production

        echo "Connection failed: " . $e->getMessage();  // Prints error messages while testing

    }

    ?>

            <table class="marginContentSpace" style="margin-bottom: 50px">

                <thead class="thead-dark">

                    <tr>

                        <th scope="col" style="padding-right: 20px;">

                            <p class="SignikaText" style="color: black">Product</p>

                        </th>

                        <th scope="col" style="padding-right: 20px;">

                            <p class="SignikaText" style="color: black">Price</p>

                        </th>

                        <th scope="col" style="padding-right: 20px;">

                            <p class="SignikaText" style="color: black">Quantity</p>

                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

        // SQL statement to display all orderitems rows with an orderID that matches the orderID in the orders table

        try {

            $stmt = $db->prepare("SELECT * FROM orderitems OI, products P WHERE OI.IID=P.ID AND OI.orderID=?");

            $stmt->execute([$_POST['order_id']]);

            $results = $stmt->fetchAll();



            if (empty($results)) {

                echo '<p>No past orders.</p>';

            } else {



                //outputs each order as a row

                foreach ($results as $row) {

                    echo '<tr>

                    <th scope="row">

                    <p class="SignikaText" style="color: black">' . $row['name'] . '</p>

                </th>

                <td>

                    <p class="SignikaText" style="color: black">' . $row['price'] . '</p>

                </td>

                <td>

                    <p class="SignikaText" style="color: black">' . $row['quantity'] . '</p>

                </td>

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

                <img class="Icon" id="UserIcon" style="position: absolute; left: 20px; bottom: 25px;"

                    src="images/back_arrow.png" alt="Back Arrow" />

            </a>

            <div style="margin-top: 80px">

                <form action="" method="post">

                    <?php

            echo '

            <button type="submit" class="searchButton" name="options" value="' . $_POST['order_id'] . '">

            <p class="SignikaText" style="margin: 0; color: white">Cancel Order</p>

            </button>

            ';

        ?>

        </form>

        </div>

            </div>

            <div>

                <!-- Footer portion is used to showcase group number, college, and course. -->

                <footer style="text-align: center">

                    <p class="SignikaText" id="footerText">

                        &copy; 2021 - 2021 Group 2.<br />

                        Montclair State University - Computer Science and Technology.<br />

                        CSIT415 - Software Engineering II.

                    </p>

                </footer>

            </div>

    </div>

    </center>

</body>



</html>



<?php

}

?>


