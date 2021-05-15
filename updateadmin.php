<?php
    include("conf.php");
	include("includes/DB.class.php");	
	include("includes/User.class.php");

	// Membuat objek dari kelas User
	$ouser = new User($db_host, $db_user, $db_password, $db_name);
	$ouser->open();

    $id = $_GET['id'];

	$ouser->updateAdmin($id);

	$ouser->close();
?>