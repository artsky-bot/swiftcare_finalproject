<?php
    //includes db connection
    require_once '../database_connection.php';

    //includes session info
	session_start();

    //gets ID of order to be processed
    $order_id = $_POST['order_id'];

    //checks if admin is logged in; if not, redirects to admin login page
    if (!(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true)) {
	    header('Location: admin_login.php');
	    exit();
    }

    //gets ID of admin
    $stmt = 'select id from admins where username = :username';
	$query = $db->prepare($stmt);
    $username = $_SESSION['admin'];
	$query->bindParam(':username', $username);
	$query->execute();
	$return = $query->fetch();
    $admin_id = $return['id'];

    //changes admin ID of order to current admin's ID
    $stmt = 'update orders set adID = :admin_id where orderID = :order_id';
    $query = $db->prepare($stmt);
    $query->bindParam(':admin_id', $admin_id);
    $query->bindParam(':order_id', $order_id);
    
    //attempts to update
    if ($query->execute())
        $_SESSION['po_success'] = true;
    
    else
        $_SESSION['po_failed'] = true;

    header('Location: ../manage_order.php');
    $db = null;
    exit();
?>