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

$hasilSearch = $_POST['search'];
if(isset($_GET['search'])){
    $hasilSearch = $_GET['search'];
}

$id_kat = $_GET['kat'];

$kat = null;
$kat .= 
'<ul>
    <li><a href="search.php?kat=1801&search='.$hasilSearch.'">Mouse</a></li>
    <li><a href="search.php?kat=1802&search='.$hasilSearch.'">Keyboard</a></li>
    <li><a href="search.php?kat=1803&search='.$hasilSearch.'">Headset</a></li>
    <li><a href="search.php?kat=1804&search='.$hasilSearch.'">Mousepad</a></li>
</ul>';

if(isset($_POST['search'])){
    $oproduk->getProdukSearch($hasilSearch);
}

if(isset($id_kat)){
    $oproduk->getProdukSearchKategori($_GET['search'], $id_kat);
}

$counter = 0;
$prodf = null;
while (list($id, $nama, $harga, $rilis, $detail, $vendor, $gambar, $diskon, $kategori) = $oproduk->getResult()){
    $counter++;
    $prodf .= 
    '<div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100">
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
if($counter == 0){
    $prodf .= 
    '<h1 style=" text-align: center; color: #F0ECE2;">Barang Tidak Ditemukan</h1>';
}


// Menutup koneksi database
$oproduk->close();

// Membaca template
$tpl = new Template("templates/search.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_KAT", $kat);
$tpl->replace("DATA_PRODUKF", $prodf);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();