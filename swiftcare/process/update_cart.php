<?php
    //includes database connection
	require_once '../database_connection.php';

	//includes session info
	session_start();

    //gets ID of product to be updated in cart and new quantity
    $prod_id = $_POST['prod_id'];
    $qty = $_POST['qty'];

	//redirects user to log in page if not already logged in
	if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    	header('location: user_login.php');
        exit();
    }

    //gets ID of logged in user
	$stmt = 'select id from users where username = :username';
	$query = $db->prepare($stmt);
	$query->bindParam(':username', $_SESSION['username']);
	$query->execute();
	$return = $query->fetch();
    $user_id = $return['id'];

    //updates item in user's cart
    $stmt = 'update cart set qty = :qty where user_id = :user_id and prod_id = :prod_id';
    $query = $db->prepare($stmt);
    $query->bindParam(':user_id', $user_id);
    $query->bindParam(':prod_id', $prod_id);
    $query->bindParam(':qty', $qty);
    
    //if update is successful, redirects to cart page
    if ($query->execute()) {
        header('location: ../cart.php');
    }

    //if update fails, redirects to homepage
    else {
        $_SESSION['udc_failed'] = true;
        header('location: ../index.php');
    }

    //closes db connection
    $db = null;
    exit();
?>