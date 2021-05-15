<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/User.class.php");
include("includes/Produk.class.php");
include("includes/Pembelian.class.php");

// Membuat objek dari kelas 
$oproduk = new Produk($db_host, $db_user, $db_password, $db_name);
$oproduk->open();

$opembelian = new Pembelian($db_host, $db_user, $db_password, $db_name);
$opembelian->open();

$data = null;
if(isset($_SESSION['id'])){
    $data .= '<a class="nav-link scrollto" href="profil.php">
    ' . $_SESSION['uname'] . '
    </a>';
}
else{
    $data .= '<a class="nav-link scrollto" href="login.php"><img src="templates/img/login.png" alt="" width="80%" height="80%"></a>';
    echo("<script>
					alert('Anda belum Login!');
					window.location.href = 'login.php';
					</script>");
}


if($_SESSION['status_admin'] == 1){
    echo("<script>
		window.location.href = 'admin.php';
		</script>");
}

$opembelian->getPembelianKeranjang($_SESSION['id']);
$prodf = null;

$arrayProduk = array(array());
$arrIdPembelian = array();
$itr = 0;

while(list($id_pembelian, $status, $total, $cart, $wish, $id_cus, $id_produk) = $opembelian->getResult()){
    array_push($arrIdPembelian, $id_pembelian);

    $oproduk->getProdukDetail($id_produk);
    list($id, $nama, $harga, $rilis, $detail, $vendor, $gambar, $diskon, $kategori) = $oproduk->getResult();

    $produk = array('id' => $id, 'nama' => $nama, 'harga' => $harga, 'rilis' => $rilis, 'detail' => $detail, 'vendor' => $vendor, 
    'gambar' => $gambar, 'diskon' => $diskon, 'kategori' => $kategori, 'id_pembelian' => $id_pembelian, 'wish' => $wish);
    array_push($arrayProduk, $produk);
    $itr++;
}

array_shift($arrayProduk);


$oproduk->getProdukDetail($_GET['id']);
list($id, $nama, $harga, $rilis, $detail, $vendor, $gambar, $diskon, $kategori) = $oproduk->getResult();

$totalharga = 0;
$prodKeranjang = null;

for($i = 0; $i < $itr; $i++){
    $totalharga = $totalharga + $arrayProduk[$i]['harga'];
    $prodKeranjang .= 
    '<div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100" style="margin-left: 20px; float: left;">
        <div class="box" style="transform: none; box-shadow: none; padding: 10px; margin-bottom:20px; style=" float: left;">
            <img src="data:image;base64,'.base64_encode($arrayProduk[$i]['gambar']).'" class="img-fluid crop" alt="">
            <p>'.$arrayProduk[$i]['nama'].'</p>
            <h3>'.$arrayProduk[$i]['vendor'].'</h3>
            <div class="price"><sup>Rp</sup>'.$arrayProduk[$i]['harga'].',-</div>
        </div>
        
    </div>';  
}

$itr_tag = 3;
$arrayTagihan = array('Sub total', 'Diskon', 'Ongkir');
$arrayRp = array($totalharga, 0, 0);

$tagihan = null;
$tagihan .=
'<div class="card-body">
    DATA_LISTBELANJA
    <hr>
    DATA_TOTALBELANJA
</div>';


$listbelanja = null;
for($i = 0; $i < $itr_tag; $i++){
    $listbelanja .=
    '<div class="container">
        <div class="row">
            <div class="col">
                <p style="text-align: left;">'.$arrayTagihan[$i].'</p>
            </div>
            <div class="col">
                <p style="text-align: right;">Rp. '.$arrayRp[$i].'</p>
            </div>
        </div>
    </div>';
}

$totalbelanja = null;
$totalbelanja .= 
'<div class="row" style="padding: 10px;">
    <div class="col">
        <p style="text-align: left;">Total Tagihan</p>
    </div>
    <div class="col">
        <p style="text-align: right;">Rp. '.$totalharga.'</p>
    </div>
</div>';

$bayar = null;
$bayar .=
'
<a href="beli.php?id=' .urlencode(serialize($arrIdPembelian)). '&total='.$totalharga.'" class="btn-buy" style="background-color: #fff; display:block; margin: 0 40%; text-align: center;">Bayar Sekarang</a>
';
// <a href="beli.php?arr=' .serialize($arrIdPembelian). '" class="btn-buy" style="background-color: #fff; display:block; margin: 0 40%; text-align: center;">Bayar Sekarang</a>

// Menutup koneksi database
$oproduk->close();
$opembelian->close();

// Membaca template
$tpl = new Template("templates/checkout.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_PRODUKDET", $prodKeranjang);
$tpl->replace("DATA_TAGIHAN", $tagihan);
$tpl->replace("DATA_LISTBELANJA", $listbelanja);
$tpl->replace("DATA_TOTALBELANJA", $totalbelanja);
$tpl->replace("DATA_LOGIN", $data);
$tpl->replace("DATA_BAYAR", $bayar);

// Menampilkan ke layar
$tpl->write();