<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/User.class.php");
include("includes/Produk.class.php");

// Membuat objek dari kelas Produk 
$oproduk = new Produk($db_host, $db_user, $db_password, $db_name);
$oproduk->open();

$data = null;
if(isset($_SESSION['id'])){
    $data .= '<a class="nav-link scrollto" href="profil.php">
    ' . $_SESSION['uname'] . '
    </a>';
}
else{
    $data .= '<a class="nav-link scrollto" href="login.php"><img src="templates/img/login.png" alt="" width="80%" height="80%"></a>';
}


if($_SESSION['status_admin'] == 1){
    echo("<script>
		window.location.href = 'admin.php';
		</script>");
}

$oproduk->getProdukDetail($_GET['id']);
list($id, $nama, $harga, $rilis, $detail, $vendor, $gambar, $diskon, $kategori) = $oproduk->getResult();

$produkdet = null;
$produkdet .=
'<div class="col">
    <div class="box2">
        <img src="data:image;base64,'.base64_encode($gambar).'" class="det" alt="">
    </div>
</div>

<div class="col">
    <div class="desk">
        <h3 style="color: #F0ECE2; font-weight: 600;">'.$nama.'</h3>
        <p style="color: #BBE1FA; text-align: left;">'.$vendor.'</p>
        <div class="price" style="color: white; font-size: 20px;">Rp '.$harga.',-</div>
        <a href="inspembelian.php?id_produk=' .$id. '&harga=' .$harga. '&layanan=2" class="btn-buy2">Tambahkan ke Wishlist</a>
        <a href="inspembelian.php?id_produk=' .$id. '&harga=' .$harga. '&layanan=1" class="btn-buy2">Masukkan ke keranjang</a>
    </div>
</div>';

$detailproduk = null;
$detailproduk .=
'<p>'.$detail.'</p>';



$oproduk->getProdukFeatured();
$prodf = null;
while (list($id, $nama, $harga, $rilis, $detail, $vendor, $gambar, $diskon, $kategori) = $oproduk->getResult()){
    $prodf .= 
    '<div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
        <a href="detail.php?id='.$id.'">
        <div class="box">
            <img src="data:image;base64,'.base64_encode($gambar).'" class="img-fluid crop" alt="">
            <p>'.$nama.'</p>
            <h3>'.$vendor.'</h3>
            <div class="price"><sup>Rp</sup>'.$harga.',-</div>
            <a href="inspembelian.php?id_produk=' .$id. '&harga=' .$harga. '&layanan=1" class="btn-buy">Add to cart</a>
        </div>
        </a>
    </div>';
}

// Menutup koneksi database
$oproduk->close();

// Membaca template
$tpl = new Template("templates/detail.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_PRODUKF", $prodf);
$tpl->replace("DATA_PRODUKDET", $produkdet);
$tpl->replace("DATA_DETAIL", $detailproduk);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();