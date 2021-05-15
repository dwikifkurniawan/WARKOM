<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/User.class.php");
include("includes/Produk.class.php");
include("includes/Pembelian.class.php");

// Membuat objek dari kelas
$ouser = new User($db_host, $db_user, $db_password, $db_name);
$oproduk = new Produk($db_host, $db_user, $db_password, $db_name);
$opembelian = new Pembelian($db_host, $db_user, $db_password, $db_name);
$ouser->open();
$oproduk->open();
$opembelian->open();

$data = null;
if(isset($_SESSION['id'])){
    $data .= '<a class="nav-link scrollto" href="#">
    ' . $_SESSION['uname'] . '
    </a>';
}
else{
    $data .= '<a class="nav-link scrollto" href="login.php"><img src="templates/img/login.png" alt="" width="80%" height="80%"></a>';
}


if($_SESSION['status_admin'] == 1){
    // $admin .= '<li><a class="nav-link scrollto active" href="admin.php">Admin</a></li>';
}

$opembelian->getPembelian();
$tabel = null;
while (list($id, $status, $harga, $keranjang, $wishlist, $id_cust, $id_barang) = $opembelian->getResult()) {
    $ouser->getUserName($id_cust);
    list($namaUser) = $ouser->getResult();

    $oproduk->getProdukName($id_barang);
    list($namaBarang) = $oproduk->getResult();
    if(empty($namaBarang)){
        $namaBarang = "Top Up Wallet";
    }
    if($status == "Menunggu Konfirmasi"){
        $beli;
        if($namaBarang == "Top Up Wallet"){
            $beli = "topup";
        }
        else {
            $beli = "beli";
        }

        $tabel .= "<tr>
        <td>" . $id . "</td>
        <td>" . $namaUser . "</td>
        <td>" . $namaBarang . "</td>
        <td>Rp. " . $harga . "</td>
        <td>" . $status . "</td>
        <td>
        <button class='btn btn-success'><a href='konfirmpembelian.php?id=" . $id . "&beli=".$beli."&harga=".$harga."&id_cust=".$id_cust."' style='color: white; font-weight: bold;'>Konfirmasi</a></button>
        <button class='btn btn-danger'><a href='deletepembelian.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Delete</a></button>
        </td>
        </tr>";
    }
    else if($status == "Belum Beli"){
        $tabel .= "";
    }
    else {
        $tabel .= "<tr>
        <td>" . $id . "</td>
        <td>" . $namaUser . "</td>
        <td>" . $namaBarang . "</td>
        <td>Rp. " . $harga . "</td>
        <td>" . $status . "</td>
        <td> 
        <button class='btn btn-danger'><a href='deletepembelian.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Delete</a></button>
        </td>
        </tr>";
    }
}

// Menutup koneksi database
$ouser->close();
$oproduk->close();
$opembelian->close();

// Membaca template
$tpl = new Template("templates/adminpembelian.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_TABEL", $tabel);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();