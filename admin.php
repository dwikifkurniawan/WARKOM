<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");


$data = null;
if(isset($_SESSION['id'])){
    $data .= '<a class="nav-link scrollto" href="#">
    ' . $_SESSION['uname'] . '
    </a>';
}
else{
    $data .= '<a class="nav-link scrollto" href="login.php"><img src="templates/img/login.png" alt="" width="80%" height="80%"></a>';
}

// Membaca template
$tpl = new Template("templates/admin.html");

// Mengganti kode Data dengan data yang sudah diproses

$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();