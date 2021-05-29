<?php
    //includes database connection
	require_once '../database_connection.php';

	//includes session info
	session_start();

    //gets ID of product to be added to cart
    $prod_id = $_POST['prod_id'];

	//redirects user to log in page if not already logged in
	if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    	header('location: ../user_login.php');
        exit();
    }

    //gets ID of logged in user
	$stmt = 'select id from users where username = :username';
	$query = $db->prepare($stmt);
    $username = $_SESSION['username'];
	$query->bindParam(':username', $username);
	$query->execute();
	$return = $query->fetch();
    $user_id = $return['id'];

    //checks if item already exists in cart
    $stmt = 'select qty from cart where user_id = :user_id and prod_id = :prod_id';
    $query = $db->prepare($stmt);
    $query->bindParam(':user_id', $user_id);
    $query->bindParam(':prod_id', $prod_id);
    $query->execute();
    $return = $query->fetch();

    $new_qty = $return['qty'] + 1;

    //if item already exists in cart, updates quantity by 1
    if ($return) {
        $stmt = 'update cart set qty = :new_qty where user_id = :user_id and prod_id = :prod_id';
        $query = $db->prepare($stmt);
        $query->bindParam(':user_id', $user_id);
        $query->bindParam(':prod_id', $prod_id);
        $query->bindParam(':new_qty', $new_qty);
    }

    //if item does not exist in cart, inserts item into cart
    else {
        $stmt = 'insert into cart (user_id, prod_id, qty) values (:user_id, :prod_id, 1)';
        $query = $db->prepare($stmt);
        $query->bindParam(':user_id', $user_id);
        $query->bindParam(':prod_id', $prod_id);
    }

    //if insert is successful, redirects to homepage page
    if ($query->execute()) {
        $_SESSION['atc_success'] = true;
        header('location: ../index.php');
    }

    //if insert fails, redirects to homepage
    else {
        $_SESSION['atc_failed'] = true;
        header('location: ../index.php');
    }

    //closes db connection
    $db = null;
    exit();
?>