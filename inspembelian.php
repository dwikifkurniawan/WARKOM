<?php

    session_start();
    include("conf.php");
	include("includes/DB.class.php");	
	include("includes/Produk.class.php");
    include("includes/Pembelian.class.php");

	// Membuat objek dari kelas pembelian
	$opembelian = new Pembelian($db_host, $db_user, $db_password, $db_name);
	$opembelian->open();

    if(!isset($_SESSION['id'])){
        echo("<script>
                        alert('Anda belum Login!');
                        window.location.href = 'login.php';
                        </script>");
    }

    $id_prod = $_GET['id_produk'];
    $id_cust = $_SESSION['id'];
    $layanan = $_GET['layanan'];
    $harga = $_GET['harga'];

	$opembelian->insertPembelian($id_prod, $id_cust, $layanan, $harga);

    // Menutup koneksi database
	$opembelian->close();
?>