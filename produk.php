<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Produk.class.php");

// Membuat objek dari kelas produk
$oproduk = new Produk($db_host, $db_user, $db_password, $db_name);
$oproduk->open();

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

$oproduk->getProduk();
$tabel = null;
while (list($id, $nama, $harga, $rilis, $detail, $vendor, $gambar, $diskon, $kategori) = $oproduk->getResult()) {
    if($kategori == 1801){
        $kategori = "Mouse";
    }
    else if($kategori == 1802){
        $kategori = "Keyboard";
    }
    else if($kategori == 1803){
        $kategori = "Headset";
    }
    else if($kategori == 1804){
        $kategori = "Mousepad";
    }

    $tabel .= "<tr>
    <td>" . $id . "</td>
    <td>" . $nama . "</td>
    <td>Rp. " . $harga . "</td>
    <td>" . $rilis . "</td>
    <td>" . $vendor . "</td>
    <td>" . $diskon . "%</td>
    <td>" . $kategori . "</td>
    <td>
    <button class='btn btn-success'><a href='updateproduk.php?id_prod=".$id."' style='color: white; font-weight: bold;'>Update</a></button>
    <button class='btn btn-danger'><a href='deletebarang.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Delete</a></button>
    </td>
    </tr>";
}

// Menutup koneksi database
$oproduk->close();

// Membaca template
$tpl = new Template("templates/produk.html");

// Mengganti kode DATA dengan data yang sudah diproses
$tpl->replace("DATA_TABEL", $tabel);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();