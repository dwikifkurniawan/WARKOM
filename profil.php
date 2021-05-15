<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/User.class.php");

// Membuat objek dari kelas user 
$ouser = new User($db_host, $db_user, $db_password, $db_name);
$ouser->open();

$data = null;
if(isset($_SESSION['id'])){
    $data .= '<a class="nav-link scrollto" href="profil.php">
    ' . $_SESSION['uname'] . '
    </a>';
}
else{
    $data .= '<a class="nav-link scrollto" href="login.php"><img src="templates/img/login.png" alt="" width="60%" height="60%"></a>';
    echo("<script>
					alert('Anda belum Login!');
					window.location.href = 'login.php';
					</script>");
}

$ouser->getUserProfil($_SESSION['id']);
list($id, $email, $nama, $username, $password, $status, $wallet) = $ouser->getResult();

$dataprofil = null;
$dataprofil .=
'<form action="updateprofil.php" method="POST">

<div class="form-group row">
    <label for="usname" class="col-sm-2 col-form-label">Username</label>
    <div class="col-sm-10">
        <input type="text" style="width: 60%; class="form-control" id="usname" name="uname" value="'.$username.'">
    </div>
</div>

<div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Nama</label>
    <div class="col-sm-10">
        <input type="text" style="width: 60%; class="form-control" id="name" name="fullname" value="'.$nama.'">
    </div>
</div>

<div class="form-group row">
    <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
        <input type="email" style="width: 60%; class="form-control" id="exampleInputEmail1" name="email" value="'.$email.'">
    </div>
</div>

<div class="form-group row">
    <label for="exampleInputPassword1" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
        <input type="password" style="width: 60%; class="form-control" id="exampleInputPassword1" name="pwd1">
    </div>
</div>

<br>

<div class="row">
    <label for="budget" class="col-sm-2">Budget Dimiliki</label>
    <div class="col-sm-10">
        <p style="text-align: left;">Rp. '.$wallet.'</p>
    </div>
</div>

<br>

<button type="submit" class="btn btn-primary" name="update">Simpan</button>

</form>
<br>
<br>';


if($_SESSION['status_admin'] == 1){
    echo("<script>
		window.location.href = 'admin.php';
		</script>");
}

// Menutup koneksi database
$ouser->close();

// Membaca template
$tpl = new Template("templates/profil.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_PROFIL", $dataprofil);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();