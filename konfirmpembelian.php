<?php
    session_start();
    include("conf.php");
    include("includes/DB.class.php");
	include("includes/Pembelian.class.php");
    include("includes/User.class.php");

    // Membuat objek dari kelas
	$opembelian = new Pembelian($db_host, $db_user, $db_password, $db_name);
	$opembelian->open();

    $ouser = new User($db_host, $db_user, $db_password, $db_name);
	$ouser->open();

    if(isset($_GET['id'])){
		$opembelian->confirmPembelian($_GET['id']);

        $beli = $_GET['beli'];

        $ouser->getUserProfil($_GET['id_cust']);
        list($id, $email, $nama, $username, $password, $status, $wallet) = $ouser->getResult();

        if($beli == "topup"){
            $wallet = (int)$wallet;
            $harga = (int)$_GET['harga'];
            $sisaWallet = $wallet + $harga;
            $ouser->updateWallet($_GET['id_cust'], $sisaWallet);
        }
        else{
            $wallet = (int)$wallet;
            $harga = (int)$_GET['harga'];
            $sisaWallet = $wallet - $harga;
            $ouser->updateWallet($_GET['id_cust'], $sisaWallet);
        }
    }

    $opembelian->close();
    $ouser->close();

    echo("<script>
            alert('Successful!');
            window.location.href = 'adminpembelian.php';
            </script>");

?>