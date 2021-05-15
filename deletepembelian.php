<?php

    session_start();
    include("conf.php");
	include("includes/DB.class.php");	
	include("includes/Produk.class.php");
    include("includes/Pembelian.class.php");

	// Membuat objek dari kelas pembelian
	$opembelian1 = new Pembelian($db_host, $db_user, $db_password, $db_name);
	$opembelian1->open();

    $id_hapus = $_GET['id_hapus'];
	if(isset($_GET['statusk'])){
		$statusK = $_GET['statusk'];
		$statusW = $_GET['statusw'];

		$opembelian1->deletePembelian($id_hapus, $statusK, $statusW);
		echo("<script>
		window.location.href = 'index.php';
		</script>");
	}
	else {
		$opembelian1->deletePembelianAdmin($id_hapus);
		echo("<script>
		window.location.href = 'adminpembelian.php';
		</script>");
	}
	
	

	$opembelian1->close();
?>