<?php

session_start();
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/User.class.php");
include("includes/Produk.class.php");
include("includes/Pembelian.class.php");

// Membuat objek dari kelas Produk dan Pembelian
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

$sort = null;
$sort .=
'<h3 style="color: #BBE1FA;">Urutkan</h3>
<div class="row">
  <div class="col-lg-2">
    <a href="wishlist.php?sort=1" class="btn btn-outline-light urutkan">Rekomendasi</a>
  </div>

  <div class="col-lg-2">
    <a href="wishlist.php?sort=2" class="btn btn-outline-light urutkan">Diskon</a>
  </div>
  
  <div class="col-lg-2">
    <a href="wishlist.php" class="btn btn-outline-danger urutkan">Hapus Urutan</a>
  </div>
</div>';


$opembelian->getPembelianWishlist($_SESSION['id']);
$prodf = null;

$arrayProduk = array(array());
$itr = 0;

while(list($id_pembelian, $status, $total, $cart, $wish, $id_cus, $id_produk) = $opembelian->getResult()){
    $oproduk->getProdukDetail($id_produk);
    list($id, $nama, $harga, $rilis, $detail, $vendor, $gambar, $diskon, $kategori) = $oproduk->getResult();

    $produk = array('id' => $id, 'nama' => $nama, 'harga' => $harga, 'rilis' => $rilis, 'detail' => $detail, 'vendor' => $vendor, 
    'gambar' => $gambar, 'diskon' => $diskon, 'kategori' => $kategori, 'id_pembelian' => $id_pembelian, 'cart' => $cart);
    array_push($arrayProduk, $produk);
    $itr++;
}

array_shift($arrayProduk);

if(isset($_GET['sort'])){
    if($_GET['sort'] == 1){ // berdasarkan rekomendasi prediksi
      
      for($i = 0; $i < $itr; $i++){

        $prediksi;

        // mencari perbedaan waktu dari tanggal rilis dengan sekarang dalam bulan
        $tglRilis = strtotime($arrayProduk[$i]['rilis']);
        $tglSekarang = strtotime(date('Y-m-d'));
        $diff = $tglSekarang - $tglRilis;
        $gapRilis = floor($diff / 60 / 60 / 24 / 30);


        // konversi id kategori sesuai dengan model machine learning
        $id_kategori;
        if($arrayProduk[$i]['kategori'] == 1801){
          $id_kategori = 3;
        }
        else if($arrayProduk[$i]['kategori'] == 1802){
          $id_kategori = 2;
        }
        else if($arrayProduk[$i]['kategori'] == 1803){
          $id_kategori = 1;
        }
        else if($arrayProduk[$i]['kategori'] == 1804){
          $id_kategori = 4;
        }

        // implementasi dari model machine learning
        if($arrayProduk[$i]['harga'] <= 1865000){
          if($gapRilis <= 26.5){
            if($id_kategori <= 3.5){
              if($gapRilis <= 2.5){
                $prediksi = 0;
              }
              else{
                if($gapRilis <= 24){
                  if($arrayProduk[$i]['harga'] <= 1734000){
                    if($gapRilis <= 11.5){
                      if($arrayProduk[$i]['harga'] <= 841500){
                        $prediksi = 1;
                      }
                      else {
                        if($arrayProduk[$i]['harga'] <= 1372000){
                          $prediksi = 0;
                        }
                        else {
                          $prediksi = 1;
                        }
                      }
                    }
                    else {
                      $prediksi = 1;
                    }
                  }
                  else {
                    if($gapRilis <= 19){
                      $prediksi = 0;
                    }
                    else {
                      $prediksi = 1;
                    }
                  }
                }
                else {
                  $prediksi = 0;
                }
              }
            } 
            else {
              $prediksi = 0;
            }
          } 
          else {
            if($id_kategori <= 3.5){
              $prediksi = 1;
            }
            else {
              if($arrayProduk[$i]['harga'] <= 535000){
                $prediksi = 1;
              }
              else {
                $prediksi = 0;
              }
            }
          }
        }
        else {
          if($gapRilis <= 18.5){
            if($gapRilis <= 16.5){
              $prediksi = 0;
            }
            else {
              $prediksi = 1;
            }
          }
          else {
            $prediksi = 0;
          }
        }
        

        // jika diprediksi diskon
        if($prediksi == 1){
          $prodf .= 
          '<div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
              <a href="detail.php?id='.$arrayProduk[$i]['id'].'">
              <div class="box">
                  <span class="featured">akan diskon</span>
                  <img src="data:image;base64,'.base64_encode($arrayProduk[$i]['gambar']).'" class="img-fluid crop" alt="">
                  <p>'.$arrayProduk[$i]['nama'].'</p>
                  <h3>'.$arrayProduk[$i]['vendor'].'</h3>
                  <div class="price"><sup>Rp</sup>'.$arrayProduk[$i]['harga'].',-</div>
                  <a href="inspembelian.php?id_produk=' .$arrayProduk[$i]['id']. '&harga=' .$arrayProduk[$i]['harga']. '&layanan=1" class="btn-buy">Add to cart</a>
                  <a href="deletepembelian.php?id_hapus='.$arrayProduk[$i]['id_pembelian'].'&statusk='.$arrayProduk[$i]['cart'].'&statusw=0" ><img src="templates/img/deletemini.png" class="btn-buy" style="margin-top:10px;" alt=""></a>
              </div>
              </a>
          </div>';
        }
      }
    }   
    else if($_GET['sort'] == 2){  // berdasarkan diskon
      function sortByDiscount($a, $b) {
        return $a['diskon'] - $b['diskon'];
      }

      usort($arrayProduk, sortByDiscount);

      for($i = 0; $i < $itr; $i++){
        if($arrayProduk[$i]['diskon'] > 0){
          $prodf .= 
          '<div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
              <a href="detail.php?id='.$arrayProduk[$i]['id'].'">
              <div class="box">
                  <span class="featured">Diskon '.$arrayProduk[$i]['diskon'].'%</span>
                  <img src="data:image;base64,'.base64_encode($arrayProduk[$i]['gambar']).'" class="img-fluid crop" alt="">
                  <p>'.$arrayProduk[$i]['nama'].'</p>
                  <h3>'.$arrayProduk[$i]['vendor'].'</h3>
                  <div class="price"><sup>Rp</sup>'.$arrayProduk[$i]['harga'].',-</div>
                  <a href="inspembelian.php?id_produk=' .$arrayProduk[$i]['id']. '&harga=' .$arrayProduk[$i]['harga']. '&layanan=1" class="btn-buy">Add to cart</a>
                  <a href="deletepembelian.php?id_hapus='.$arrayProduk[$i]['id_pembelian'].'&statusk='.$arrayProduk[$i]['cart'].'&statusw=0" ><img src="templates/img/deletemini.png" class="btn-buy" style="margin-top:10px;" alt=""></a>
              </div>
              </a>
          </div>';
        }
      }
    } 
}
else {
    for($i = 0; $i < $itr; $i++){
      $prodf .= 
      '<div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
            <a href="detail.php?id='.$arrayProduk[$i]['id'].'">
            <div class="box">
                <img src="data:image;base64,'.base64_encode($arrayProduk[$i]['gambar']).'" class="img-fluid crop" alt="">
                <p>'.$arrayProduk[$i]['nama'].'</p>
                <h3>'.$arrayProduk[$i]['vendor'].'</h3>
                <div class="price"><sup>Rp</sup>'.$arrayProduk[$i]['harga'].',-</div>
                <a href="inspembelian.php?id_produk=' .$arrayProduk[$i]['id']. '&harga=' .$arrayProduk[$i]['harga']. '&layanan=1" class="btn-buy">Add to cart</a>
                <a href="deletepembelian.php?id_hapus='.$arrayProduk[$i]['id_pembelian'].'&statusk='.$arrayProduk[$i]['cart'].'&statusw=0" ><img src="templates/img/deletemini.png" class="btn-buy" style="margin-top:10px;" alt=""></a>
            </div>
            </a>
        </div>';
    }
}



// Menutup koneksi database
$oproduk->close();
$opembelian->close();

// Membaca template
$tpl = new Template("templates/wishlist.html");

// Mengganti kode Data dengan data yang sudah diproses
$tpl->replace("DATA_PRODUKF", $prodf);
$tpl->replace("DATA_SORT", $sort);
$tpl->replace("DATA_LOGIN", $data);

// Menampilkan ke layar
$tpl->write();