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
'<form action="topupwallet.php" method="POST">
<br>
<div class="form-group row">
    <label for="nominal" class="form-label">Pilih Nominal Top Up</label>
    <input type="number" style="width: 60%; class="form-control" id="nominal" name="nominal" placeholder="Min Top Up Rp.5000" required>
</div>

<br>

<div class="form-group row">
    <label for="metode" class="form-label">Pilih Metode</label>
    <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100">
        <div class="box" style="transform: none;">
            <img src="templates/img/ovo.png" class="img-fluid" alt="">
            <br>
            <input type="radio" class="btn-check" name="options-outlined" id="success-outlined" autocomplete="off" required>
            <label class="btn btn-outline-dark" for="success-outlined">Pilih</label>
        </div>
    </div>

    <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100">
        <div class="box" style="transform: none;">
            <img src="templates/img/bni.png" class="img-fluid" alt="">
            <br>
            <input type="radio" class="btn-check" name="options-outlined" id="danger-outlined" autocomplete="off" required>
            <label class="btn btn-outline-dark" for="danger-outlined">Pilih</label>
        </div>
    </div>
</div>

<br>

<div class="form-group row">
    <label for="norek" class="form-label">Masukan No HP/No Rekening</label>
    <input type="text" style="width: 60%; class="form-control" id="norek" name="norek" required>
</div>

<br>

<button type="submit" class="btn btn-primary" name="topup">Submit</button>

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
$tpl = new Template("templates/topup.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_PROFIL", $dataprofil);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();