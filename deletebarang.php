<?php

    session_start();
    include("conf.php");
	include("includes/DB.class.php");	
	include("includes/Produk.class.php");

	// Membuat objek dari kelas produk
	$oproduk = new Produk($db_host, $db_user, $db_password, $db_name);
	$oproduk->open();

    $id_hapus = $_GET['id_hapus'];
	
    $oproduk->deleteBarang($id_hapus);
    echo("<script>
    window.location.href = 'produk.php';
    </script>");
	
	

	$oproduk->close();
?>