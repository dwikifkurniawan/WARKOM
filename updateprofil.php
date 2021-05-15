<?php
    session_start();
    include("conf.php");
    include("includes/DB.class.php");
	include("includes/User.class.php");

    // Membuat objek dari kelas user
	$ouser = new User($db_host, $db_user, $db_password, $db_name);
	$ouser->open();

    if(isset($_POST['update'])){
		$ouser->updateUser($_SESSION['id']);
    }

?>