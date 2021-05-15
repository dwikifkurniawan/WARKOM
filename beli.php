<?php

    session_start();
    include("conf.php");
	include("includes/DB.class.php");	
	include("includes/Produk.class.php");
    include("includes/Pembelian.class.php");
    include("includes/User.class.php");

	// Membuat objek dari kelas
	$opembelian = new Pembelian($db_host, $db_user, $db_password, $db_name);
	$opembelian->open();
    
    $ouser = new User($db_host, $db_user, $db_password, $db_name);
    $ouser->open();

    $ouser->getUserProfil($_SESSION['id']);
    list($id, $email, $nama, $username, $password, $status, $wallet) = $ouser->getResult();


    if($_GET['total'] > $wallet){
        echo("<script>
            alert('Uang anda kurang, Silakan top up dahulu');
            window.location.href = 'topup.php';
            </script>");
    }
    else {
        // $id_beli = json_decode($_GET['id'], true);
        $id_beli = unserialize(urldecode($_GET['id']));;
        foreach($id_beli as $value){
            // echo $value;
            $opembelian->melakukanPembelian($value);
        }
        echo("<script>
                alert('Pembelian successful!');
                window.location.href = 'konfirmasi.php?id_beli=" .urlencode(serialize($id_beli)). "';
                </script>");
    }
    

	$opembelian->close();
    $ouser->close();
?>