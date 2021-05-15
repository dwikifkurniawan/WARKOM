<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Kategori.class.php");

// Membuat objek dari kelas kategori
$okategori = new Kategori($db_host, $db_user, $db_password, $db_name);
$okategori->open();

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

$okategori->getKategori();
$tabel = null;
while (list($id, $nama) = $okategori->getResult()) {
    $tabel .= "<tr>
    <td>" . $id . "</td>
    <td>" . $nama . "</td>
    <td>
    <button class='btn btn-danger'><a href='index.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Hapus</a></button>
    </td>
    </tr>";
}

// Menutup koneksi database
$okategori->close();

// Membaca template
$tpl = new Template("templates/kategoriadmin.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_TABEL", $tabel);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();