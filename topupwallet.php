<?php
    session_start();
    include("conf.php");
    include("includes/DB.class.php");
	include("includes/Pembelian.class.php");

    // Membuat objek dari kelas Pembelian
	$opembelian = new Pembelian($db_host, $db_user, $db_password, $db_name);
	$opembelian->open();

    if(isset($_POST['topup'])){
		$opembelian->insertPembelian(2000, $_SESSION['id'], 3, $_POST['nominal']);
    }

    $opembelian->close();

?>