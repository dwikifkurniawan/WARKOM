<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/User.class.php");

// Membuat objek dari kelas User
$ouser = new User($db_host, $db_user, $db_password, $db_name);
$ouser->open();

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

$ouser->getUser();
$tabel = null;
while (list($id, $email, $nama, $username, $password, $status) = $ouser->getResult()) {
    if($status == 1){
        $status = "Admin";
        $tabel .= "<tr>
        <td>" . $id . "</td>
        <td>" . $email . "</td>
        <td>" . $nama . "</td>
        <td>" . $username . "</td>
        <td>" . $status . "</td>
        <td>
        <button class='btn btn-danger'><a href='deleteuser.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Delete</a></button>
        </td>
        </tr>";
    }
    else {
        $status = "Customer";
        $tabel .= "<tr>
        <td>" . $id . "</td>
        <td>" . $email . "</td>
        <td>" . $nama . "</td>
        <td>" . $username . "</td>
        <td>" . $status . "</td>
        <td>
        <button class='btn btn-success'><a href='updateadmin.php?id=".$id."' style='color: white; font-weight: bold;'>Jadikan Admin</a></button>
        <button class='btn btn-danger'><a href='deleteuser.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Delete</a></button>
        </td>
        </tr>";
    }
    
}

// Menutup koneksi database
$ouser->close();

// Membaca template
$tpl = new Template("templates/user.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_TABEL", $tabel);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();