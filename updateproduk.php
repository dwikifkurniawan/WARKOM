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

$oproduk->getProdukDetail($_GET['id_prod']);
list($id, $nama, $harga, $rilis, $detail, $vendor, $gambar, $diskon, $kategori) = $oproduk->getResult();

$form = null;
$form .=
'<form action="updatetbproduk.php?id='.$id.'" method="POST" enctype="multipart/form-data">
<!-- <h2 style="color: #BBE1FA;"></h2> -->
<div class="mb-3" style="width: 40%;">
    <label for="exampleInputEmail1" class="form-label" style="color: #BBE1FA;">Nama Produk</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nama" value="'.$nama.'" required>
</div>
<div class="mb-3" style="width: 40%;">
    <label for="exampleInputEmail1" class="form-label" style="color: #BBE1FA;">Harga (Rp)</label>
    <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="harga"  value="'.$harga.'" required>
</div>
<div class="mb-3" style="width: 40%;">
    <label for="exampleInputEmail1" class="form-label" style="color: #BBE1FA;">Tanggal rilis</label>
    <input type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="tgl"  value="'.$rilis.'" required>
</div>
<div class="mb-3" style="width: 40%;">
    <label for="exampleInputEmail1" class="form-label" style="color: #BBE1FA;">Details</label>
    <textarea class="form-control" id="exampleInputEmail1" rows="5" name="detail_produk" required>'.$detail.'</textarea>
</div>
<div class="mb-3" style="width: 40%;">
    <label for="exampleInputEmail1" class="form-label" style="color: #BBE1FA;">Nama Vendor</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="vendor"  value="'.$vendor.'" required>
</div>
<div class="mb-3" style="width: 40%;">
    <label for="exampleInputEmail1" class="form-label" style="color: #BBE1FA;">Gambar</label>
    <input type="file" class="form-control" name="gambar"  value="'.base64_encode($gambar).'" required>
</div>
<div class="mb-3" style="width: 40%;">
    <label for="exampleInputPassword1" class="form-label" style="color: #BBE1FA;">Diskon (%)</label>
    <input type="number" class="form-control" id="exampleFormControlInput1" name="diskon" value="'.$diskon.'" required> 
</div>
<div class="mb-3" style="width: 40%;">
  <label for="exampleInputPassword1" class="form-label" style="color: #BBE1FA;">Kategori</label>
  <select class="form-control" id="exampleFormControlInput1" name="kategori"  value="'.$kategori.'">
    <option value="1801">Mouse</option>
    <option value="1802">Keyboard</option>
    <option value="1803">Headset</option>
    <option value="1804">Mousepad</option>
  </select>
</div>
<button type="submit" class="btn btn-primary" name="update">Update Produk</button>
</form>';

// Menutup koneksi database
$oproduk->close();

// Membaca template
$tpl = new Template("templates/updateproduk.html");

// Mengganti kode DATA dengan data yang sudah diproses
$tpl->replace("DATA_FORM", $form);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();