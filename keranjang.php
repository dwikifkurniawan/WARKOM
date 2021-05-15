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
    '<div class="box" style="transform: none; box-shadow: none; padding: 10px; margin-bottom:20px; style=" float: left;">
        <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100" style=" float: left;">
            <img src="data:image;base64,'.base64_encode($arrayProduk[$i]['gambar']).'" class="img-fluid crop" alt="">
        </div>

        <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100" style="float: left; margin-top: 20px;">
            <p>'.$arrayProduk[$i]['nama'].'</p>
            <h3>'.$arrayProduk[$i]['vendor'].'</h3>
            <div class="price"><sup>Rp</sup>'.$arrayProduk[$i]['harga'].',-</div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100" style="float: left; margin-top: 50px;">
            <a href="deletepembelian.php?id_hapus='.$arrayProduk[$i]['id_pembelian'].'&statusk=0&statusw='.$arrayProduk[$i]['wish'].'" ><img src="templates/img/deletemini.png" class="btn-buy" style="margin-top:10px;" alt=""></a>
        </div>
    </div>
    ';  
}

$belanjaan = null;
$belanjaan .=
'<div class="box" style="transform: none; box-shadow: none; padding: 10px;">

    <h3 style="color: #2B3D58">Ringkasan Belanja</h3>
    <br>
    DATA_LISTBELANJA
    <hr>
    DATA_TOTALBELANJA

</div>';

$listbelanja = null;
for($i = 0; $i < $itr; $i++){
    $listbelanja .=
    '<div class="container">
        <div class="row">
            <div class="col">
                <p style="text-align: left; color: #3D4C55;">'.$arrayProduk[$i]['nama'].'</p>
            </div>
            <div class="col">
                <p style="text-align: right; color: #3D4C55;">Rp. '.$arrayProduk[$i]['harga'].'</p>
            </div>
        </div>
    </div>';
}

$totalbelanja = null;
$totalbelanja .= 
'<div class="row" style="padding: 10px;">
    <div class="col">
        <p style="text-align: left; color: #3D4C55;">Total Belanjaan</p>
    </div>
    <div class="col">
        <p style="text-align: right; color: #3D4C55;">Rp. '.$totalharga.'</p>
    </div>
</div>
<div class="row" style="padding: 10px; margin: auto;">
    <a href="checkout.php" class="btn-buy">Beli</a>
</div>';

/* <A href="example.html?arr=<?PHP echo serialize($arr); ?>">test</A> */


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
$tpl = new Template("templates/keranjang.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_PRODUKF", $prodf);
$tpl->replace("DATA_PRODUKDET", $prodKeranjang);
$tpl->replace("DATA_RINGKASAN", $belanjaan);
$tpl->replace("DATA_LISTBELANJA", $listbelanja);
$tpl->replace("DATA_TOTALBELANJA", $totalbelanja);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();