<?php 

class Produk extends DB{
	
	// Mengambil data produk
	function getProduk(){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk";

		// Mengeksekusi query
		return $this->execute($query);
	}
	
	function getProdukName($id){
		// Query mysql select data ke produk
		$query = "SELECT nama_produk FROM produk WHERE id_produk=$id";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getProdukKategori($id_kat){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk WHERE kategori_id_kategori=$id_kat";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getProdukKategoriSort1($id_kat){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk WHERE kategori_id_kategori=$id_kat ORDER BY tgl_rilis DESC";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getProdukKategoriSort2($id_kat){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk WHERE kategori_id_kategori=$id_kat ORDER BY harga_produk";

		// Mengeksekusi query
		return $this->execute($query);
	}
	function getProdukKategoriSort3($id_kat){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk WHERE kategori_id_kategori=$id_kat ORDER BY harga_produk DESC";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getProdukSearch($hasilSearch){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk WHERE nama_produk LIKE '%$hasilSearch%' OR vendor LIKE '%$hasilSearch%'";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getProdukSearchKategori($hasilSearch, $id_kat){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk WHERE (nama_produk LIKE '%$hasilSearch%' OR vendor LIKE '%$hasilSearch%') AND kategori_id_kategori=$id_kat";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getProdukFeatured(){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk LIMIT 4";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getProdukNew(){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk ORDER BY tgl_rilis DESC LIMIT 4";

		// Mengeksekusi query
		return $this->execute($query);
	}


	function getProdukDetail($id){
		// Query mysql select data ke produk
		$query = "SELECT * FROM produk WHERE id_produk=$id";

		// Mengeksekusi query
		return $this->execute($query);
	}
	

	// Menginsert data produk
	function insertProduk(){
		//untuk attribut
		if(isset($_POST['insertproduk'])){
			if(isset($_FILES['gambar'])){
				$nama = $_POST['nama'];
				$harga = $_POST['harga'];
				$tgl = $_POST['tgl'];
				$detail_produk = $_POST['detail_produk'];
				$vendor = $_POST['vendor'];
				$gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
				$diskon = $_POST['diskon'];
				$kategori = $_POST['kategori'];
				
				// Query mysql insert data ke produk
				$query = "INSERT INTO produk (nama_produk, harga_produk, tgl_rilis, detail_produk, vendor, gambar, diskon, kategori_id_kategori) VALUES 
				('$nama', '$harga', '$tgl', '$detail_produk', '$vendor', '$gambar', '$diskon', '$kategori')";
				
				// Mengeksekusi query
				$input = $this->execute($query);
				if($input){
					echo("<script>
					alert('Berhasil!');
					window.location.href = 'produk.php';
					</script>");
				}else{
					echo("<script>
					alert('Gagal!');
					window.location.href = 'produk.php';
					</script>");
					// die('Failed to insert: '. $this->error($query));
				}
			}
		}
	
	}

	// mengupdate data produk
	function updateProduk($id){
		//untuk attribut
		if(isset($_POST['update'])){
			if(isset($_FILES['gambar'])){
				$nama = $_POST['nama'];
				$harga = $_POST['harga'];
				$tgl = $_POST['tgl'];
				$detail_produk = $_POST['detail_produk'];
				$vendor = $_POST['vendor'];
				$gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
				$diskon = $_POST['diskon'];
				$kategori = $_POST['kategori'];
				
				// Query mysql update data ke produk
				$query = "UPDATE produk SET nama_produk = '$nama', harga_produk = '$harga', tgl_rilis = '$tgl', 
				detail_produk = '$detail_produk', vendor = '$vendor', gambar = '$gambar', 
				diskon = '$diskon', kategori_id_kategori = '$kategori' WHERE id_produk = '$id'";
				
				// Mengeksekusi query
				$input = $this->execute($query);
				
				if($input){
					echo("<script>
					alert('Update successful!');
					window.location.href = 'produk.php';
					</script>");
				}else{
					echo("<script>
					alert('Update failed!');
					window.location.href = 'produk.php';
					</script>");
					// die('Failed to insert: '. $this->error($query));
				}
			}
		}
	
	}

	// menghapus data 
	function deleteBarang($id_hapus){

		// Query mysql delete data
		$query = "DELETE FROM produk WHERE id_produk = '$id_hapus'";

		// Mengeksekusi query
		return $this->execute($query);
	}
}

?>