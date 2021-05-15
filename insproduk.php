<?php
    include("conf.php");
	include("includes/DB.class.php");	
	include("includes/Produk.class.php");

	// Membuat objek dari kelas produk
	$oproduk = new Produk($db_host, $db_user, $db_password, $db_name);
	$oproduk->open();

	$oproduk->insertProduk();

	// Menutup koneksi database
	$oproduk->close();
?>