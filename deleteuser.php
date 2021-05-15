<?php

    session_start();
    include("conf.php");
	include("includes/DB.class.php");	
	include("includes/User.class.php");

	// Membuat objek dari kelas produk
	$ouser = new User($db_host, $db_user, $db_password, $db_name);
	$ouser->open();

    $id_hapus = $_GET['id_hapus'];
	
    $ouser->deleteUser($id_hapus);

    $ouser->close();

    echo("<script>
    window.location.href = 'user.php';
    </script>");
?>