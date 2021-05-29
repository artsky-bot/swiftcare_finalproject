<?php
require 'database_connection.php';

session_start();

//checks if admin is logged in; if not, redirects to admin login page
if (!(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true)) {
    header('Location: admin_login.php');
}

$r_notice = "";
$au_notice = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        if ($_POST['options'] == 'Delete') {
            // SQL DELETE products where user input credentials match
            $stmt = $db->prepare("SELECT * FROM products WHERE (id=?)");
            $stmt->execute([$_POST['ID']]);
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            // If to delete product image from directory on server
            if (!unlink($results['image'])) { 
                $r_notice = $results['image'] . 'cannot be deleted due to an error'; 
            } 
            else { 
                $r_notice = $results['image'] . ' has been deleted '; 
            } 
            $stmt = $db->prepare("DELETE FROM products WHERE (id=?)");
            $stmt->execute([$_POST['ID']]);
            // Print deleted products or print an error
            if ($stmt->rowCount() > 0) {
                $r_notice = 'Rows affected: ' . $stmt->rowCount();
            } else {
                $r_notice = "An error has occurred.  The item was not deleted.";
            }
        } else {

            $ID = $_POST['ID'];
            $name = $_POST['name'];
            $category = $_POST['category'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            
            // Prepare image file for proper storage
            $target_dir = "product_pics/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    $au_notice = "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $au_notice = "File is not an image.";
                    $uploadOk = 0;
                }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $au_notice = "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $au_notice = "Sorry, your file was not uploaded.";
            }
            // if everything is ok, try to upload file
            else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $au_notice = "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["tmp_name"])) . " has been uploaded.";
                } else {
                    $au_notice = "Sorry, there was an error uploading your file.";
                }
            }

             // Protect against injections by adding slashes if they aren't present  
            $ID = addslashes($ID);
            $name = addslashes($name);
            $category = addslashes($category);
            $description = addslashes($description);
            $price = addslashes($price);

            if ($_POST['options'] == 'create') {
                // SQL statement to add a new row to the products database
                $stmt = $db->prepare("INSERT INTO products (ID, name, category, description, price, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$ID, $name, $category, $description, $price, $target_file]);
                if ($stmt->rowCount() > 0) {
                    $au_notice = 'Rows affected: ' . $stmt->rowCount();
                } else {
                    $au_notice = "An error has occurred.  The item was not created.";
                }
            } elseif ($_POST['options'] == 'update') {
                $stmt = $db->prepare("SELECT * FROM products WHERE (id=?)");
                $stmt->execute([$_POST['ID']]);
                $results = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!unlink($results['image'])) {
                    $r_notice = $results['image'] . 'cannot be deleted due to an error';
                } else {
                    $r_notice = $results['image'] . ' has been deleted ';
                }
                //SQL statement to change attributes of product with matching ID
                $stmt = $db->prepare("UPDATE products SET name=?, category=?, description=?, price=?,  image=? WHERE ID=?");
                $stmt->execute([$name, $category, $description, $price, $target_file, $ID]);

                //Print statement to show if product was updated or not
                if ($stmt->rowCount() > 0) {
                    $au_notice = 'Rows affected: ' . $stmt->rowCount();
                } else {
                    $au_notice = "An error has occurred.  The item was not updated.";
                }
            }
        }
    } catch (PDOException $e) { // Shouldn't execute in production.
        echo "Connection failed: " . $e->getMessage();  // Prints error messages while testing
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Swift Care - Add or Delete Products</title>
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
    <div class="marginContentSpace">
        <div class="BoxPanel" id="LoginPanel">
            <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
                <div style="display: flex; align-items: center">
                    <img class="LogoSmall" id="LogoForm" src="images/admin_adp.png" alt="Swift Care logo" />
                    <h1 class="OrbitronTitle" id="TitleForm" style="color: #a10000">Admin - Add/Update Product</h1>
                </div>
                <?php 
                    if ($au_notice != "") {
                    	echo '<p class = "SignikaText" id = "MessageInfo">' . $au_notice . '</p></br>';
                    }
                ?>
                <div class="marginContentSpace">
                    <label class="SignikaLabel" for="option">Would you like to create or update a product?</label>
                    <select style = "margin-left: 5px; width: 75px"name="options" id="productOptions" required>
                        <option value="create">Create</option>
                        <option value="update">Update</option>
                    </select>
                </div>
                <div class="marginContentSpace">
                    <label class="SignikaLabel" id="LabelForm" for="username_sec">ID:</label>
                    <input class="InputField" id="LoginSignupField" name="ID" type="number" id="productname_sec" required />
                </div>
                <div class="marginContentSpace">
                    <label class="SignikaLabel" id="LabelForm" for="username_sec">Name:</label>
                    <input class="InputField" id="LoginSignupField" name="name" type="text" id="productname_sec" required />
                </div>
                <div class="marginContentSpace">
                    <label class="SignikaLabel" id="LabelForm" for="">Category:</label>
                    <input class="InputField" id="LoginSignupField" name="category" type="text" id="product_cat_sec" required />
                </div>
                <div class="marginContentSpace">
                    <label class="SignikaLabel" id="LabelForm" for="">Price:</label>
                    <input class="InputField" id="LoginSignupField" name="price" type="number" id="product_price_sec" required />
                </div>
                <div class="marginContentSpace">
                    <label class="SignikaLabel" id="LabelForm" style="position: relative; bottom: 20px" for="">Description:</label>
                    <textarea name="description" id="" cols="30" rows="2" class="InputField" style="resize: none; outline: 0; border-radius: 25px; padding: 15px; border: 0;width: 40%; min-width: 120px;"></textarea>
                </div>
                <div class="marginContentSpace" style = "margin-top: 25px; margin-bottom: 50px">
                    <label class="SignikaLabel" id="LabelForm" for="">Image:</label>
                    <input class="InputField" type="file" name="fileToUpload" id="LoginSignupField"  required>
                </div>
                <!-- Dynamic message that displays a message to the user. -->
                <input class="SubmitButton" style="background-color: #a10000" type="submit" />
                <a href="admin_panel.php">
                    <img class="Icon" id="UserIcon" style="position: absolute; left: 20px; bottom: 25px;" src="images/back_arrow_admin.png" alt="Back Arrow" />
                </a>
            </form>
        </div>

        <div class="BoxPanel" id="LoginPanel">
            <div style="display: flex; align-items: center">
                <img class="LogoSmall" id="LogoForm" src="images/admin_adp.png" alt="Swift Care logo" />
                <h1 class="OrbitronTitle" id="TitleForm" style="color: #a10000">Admin - Remove Product</h1>
            </div>
            <center>
                <table style="margin-top: 35px">
                	<?php 

                    if ($r_notice != "") {
                    	echo '<p class = "SignikaText" id = "MessageInfo">' . $r_notice . '</p></br>';
                    }
                	?>
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" style="padding-right: 20px;">
                                <p class="SignikaText" style="color: black">Product ID</p>
                            </th>
                            <th scope="col" style="padding-right: 20px;">
                                <p class="SignikaText" style="color: black">Product Name</p>
                            </th>
                            <th scope="col" style="padding-right: 20px;">
                                <p class="SignikaText" style="color: black">Product Category</p>
                            </th>
                            <th scope="col" style="padding-right: 20px;">
                                <p class="SignikaText" style="color: black">Product Price</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- **TODO: Show Product Here -->
                        <?php

                        //general search statement for if no search was performed
                        $st = 'select id, name, price, category from products';

                        //preparese db query
                        $query = $db->prepare($st);

                        //runs query and gets results
                        $query->execute();
                        $results = $query->fetchAll();


                        if ($results) {
                            //table is created
                            foreach ($results as $row) {
                                //each item is output in a table cell
                                echo '
                                <tr>
                                   <th scope="row"><p class = "SignikaText" style = "color: black; margin: 0">' . $row['id'] . '</p></th>
                                   <td><p class = "SignikaText" style = "color: black; margin: 0">' . $row['name'] . '</p></td>
                                   <td><p class = "SignikaText" style = "color: black; margin: 0">' . $row['category'] . '</p></td>
                                   <td><p class = "SignikaText" style = "color: black; margin: 0">$' . $row['price'] . '</p></td>
                                </tr>
				        		';
                            }
                        }

                        $db = null;
                        ?>
                        <!-- Loop End HERE -->
                    </tbody>
                </table>
                <!-- Delete Function Here -->
                <div style="margin-top: 80px">
                    <form action="" method="post">
                        <input class="InputField SubmitButton" type="text" name="ID" placeholder="Enter Product ID" style="padding-right: 50px; background-color: #FFF; color: black">
                        <input class="SubmitButton" style="background-color: #a10000" type="submit" name="options" value="Delete"/>
                    </form>
                    <a href="admin_panel.php">
                        <img class="Icon" id="UserIcon" style="position: absolute; left: 20px; bottom: 25px;" src="images/back_arrow_admin.png" alt="Back Arrow" />
                    </a>
                </div>
            </center>
        </div>
    </div>
    <!-- Footer portion is used to showcase group number, college, and course. -->
    <footer style="text-align: center">
        <p class="SignikaText" id="footerText">
            &copy; 2021 - 2021 Group 2.<br />
            Montclair State University - Computer Science and Technology.<br />
            CSIT415 - Software Engineering II.
        </p>
    </footer>
</body>

</html>