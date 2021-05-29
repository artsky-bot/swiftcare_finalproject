<?php
    //includes db connection
    require_once '../database_connection.php';

    //includes session information
    session_start();

    //gets ID of product to be removed from cart
    $prod_id = $_POST['prod_id'];

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

    //deletes item from user's cart
    $stmt = 'delete from cart where user_id = :user_id and prod_id = :prod_id';
    $query = $db->prepare($stmt);
    $query->bindParam(':user_id', $user_id);
    $query->bindParam(':prod_id', $prod_id);
    
    //if removal is successful, redirects to cart page
    if ($query->execute()) {
        header('location: ../cart.php');
    }

    //if removal fails, redirects to homepage
    else {
        $_SESSION['rfc_failed'] = true;
        header('location: ../cart.php');
    }

    //closes db connection
    $db = null;
    exit();
?>