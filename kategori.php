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

if(isset($_GET['id_kat'])){
    $id_kat = $_GET['id_kat'];
}

$sort = null;
$sort .=
'<h3 style="color: #BBE1FA;">Urutkan</h3>
<div class="row">
  <div class="col-lg-2">
    <a href="kategori.php?id_kat=' . $id_kat . '&sort=1" class="btn btn-outline-light urutkan">Terbaru</a>
  </div>

  <div class="col-lg-2">
    <a href="kategori.php?id_kat=' . $id_kat . '&sort=2" class="btn btn-outline-light urutkan">Termahal</a>
  </div>
  
  <div class="col-lg-2">
    <a href="kategori.php?id_kat=' . $id_kat . '&sort=3" class="btn btn-outline-light urutkan">Termurah</a>
  </div>
</div>';

$oproduk->getProdukKategori($id_kat);

$hasilSort = $_GET['sort'];
if($hasilSort == 1){
    $oproduk->getProdukKategoriSort1($id_kat);
}
elseif($hasilSort == 2){
    $oproduk->getProdukKategoriSort2($id_kat);
}
elseif($hasilSort == 3){
    $oproduk->getProdukKategoriSort3($id_kat);
}


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
$tpl = new Template("templates/kategori.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_SORT", $sort);
$tpl->replace("DATA_PRODUKF", $prodf);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();