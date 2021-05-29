<?php

//includes db connection
require_once 'database_connection.php';

//general search statement for if no search was performed
$stmt = 'SELECT * FROM `users`';

//preparese db query
$query = $db->prepare($stmt);

//runs query and gets results
$query->execute();
$results = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Swift Care - View User Details</title>
    <meta charset="UTF-8">
    <meta name="author" content="SE2 - Group 2" />
    <meta name="description" content="Main store page of Swift Care" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- First link refers to the main.css file and second link is used for website icon -->
    <link rel="stylesheet" href="css/red_page.css" type="text/css">
    <link rel="stylesheet" href="css/main.css" type="text/css" />
    <link rel="icon" href="images/sc_logo_admin_K7A_icon.ico" />
</head>

<body>
        <div class="BoxPanel" id="LoginPanel" style = "min-height: 300px">
                <div style="display: flex; align-items: center">
                    <img class="LogoSmall" id="LogoForm" src="images/admin_ud.png" alt="Swift Care logo" />
                    <h1 class="OrbitronTitle" id="TitleForm" style="color: #a10000">Admin - View User Details</h1>
                </div>
            <center>
                <table style="margin-top: 35px; margin-bottom: 75px">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" style="padding-right: 20px;"><p class = "SignikaText" style = "color: black">User ID</p></th>
                            <th scope="col" style="padding-right: 20px;"><p class = "SignikaText" style = "color: black">User Name</p></th>
                            <th scope="col" style="padding-right: 20px;"><p class = "SignikaText" style = "color: black">User Email</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- **TODO: Show User Here -->
                        <?php 
				           if ($results) {
					       //table is created
					       foreach ($results as $row) {
						   //each item is output in a table cell
						    echo '
                                <tr>
                                   <th scope="row"><p class = "SignikaText" style = "color: black; margin: 0">'.$row['id'].'</p></th>
                                   <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['username'].'</p></td>
                                   <td><p class = "SignikaText" style = "color: black; margin: 0">'.$row['email'].'</p></td>
                                </tr>
				        		';
				        	}
			        	}
		            	?>
                        <!-- Loop End HERE -->
                    </tbody>
                </table>
                <a href = "admin_panel.php">
                    <img class = "Icon" id = "UserIcon" style = "position: absolute; left: 20px; bottom: 25px;" src = "images/back_arrow_admin.png" alt = "Back Arrow"/>
                </a>
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