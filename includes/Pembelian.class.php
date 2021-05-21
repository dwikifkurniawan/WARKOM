<?php 

class Pembelian extends DB{
	
	// Mengambil data pembelian
	function getPembelian(){
		// Query mysql select data ke Pembelian
		$query = "SELECT * FROM pembelian";

		// Mengeksekusi query
		return $this->execute($query);
	}

	// Mengambil data wishlist
	function getPembelianWishlist($id_cust){
		// Query mysql select data ke Pembelian
		$wishlist = 1;
		$query = "SELECT * FROM pembelian WHERE status_wishlist=$wishlist AND pembelian_id_customer=$id_cust";

		// Mengeksekusi query
		return $this->execute($query);
	}

	// Mengambil data keranjang
	function getPembelianKeranjang($id_cust){
		// Query mysql select data ke Pembelian
		$keranjang = 1;
		$query = "SELECT * FROM pembelian WHERE status_keranjang=$keranjang AND pembelian_id_customer=$id_cust AND status_pembelian='Belum Beli'";

		// Mengeksekusi query
		return $this->execute($query);
	}

	// Mengambil data pembelian yang menunggu konfirmasi
	function getPembelianKonfirm($id_cust){
		// Query mysql select data ke Pembelian
		$keranjang = 0;
		$query = "SELECT * FROM pembelian WHERE status_keranjang=$keranjang AND pembelian_id_customer=$id_cust AND status_pembelian='Menunggu Konfirmasi' AND pembelian_id_produk <> 2000";

		// Mengeksekusi query
		return $this->execute($query);
	}
	
	function melakukanPembelian($id_beli){
		$status_pembelian = "Menunggu Konfirmasi";
		$keranjang = 0;

		// Query mysql update data ke Pembelian
		$query = "UPDATE pembelian SET status_pembelian = '$status_pembelian', status_keranjang= '$keranjang' WHERE id_pembelian = '$id_beli'";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function confirmPembelian($id_cust){
		$status_pembelian = "Terkonfirmasi";

		// Query mysql update data ke Pembelian
		$query = "UPDATE pembelian SET status_pembelian = '$status_pembelian' WHERE id_pembelian = '$id_cust'";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function insertPembelian($id_prod, $id_cust, $layanan, $harga){
		//untuk attribut
        $total = $harga;
        if($layanan == 1){
			$status_pembelian = "Belum Beli";
            $keranjang = 1;
        }
        else if($layanan == 2){
			$status_pembelian = "Belum Beli";
            $wishlist = 1;
        }
		else {
			$status_pembelian = "Menunggu Konfirmasi";
			$keranjang = 0;
			$wishlist = 0;
		}
		$id_produk = $id_prod;
        $id_user = $id_cust;
        
        
        // Query mysql insert data ke Pembelian
        $query = "INSERT INTO pembelian (status_pembelian, total_harga_pembelian, status_keranjang, status_wishlist, pembelian_id_customer, pembelian_id_produk) 
        VALUES ('$status_pembelian', '$total', '$keranjang', '$wishlist', '$id_user', '$id_produk')";
        
        // Mengeksekusi query
        $input = $this->execute($query);
        if($input){
            echo("<script>
            alert('Berhasil!');
            window.location.href = 'index.php';
            </script>");
        }else{
			echo("<script>
            alert('Gagal menambahkan!');
            window.location.href = 'index.php';
            </script>");
            // die('Failed to insert: '. $this->error($query));
        }
	
	}

	// Menghapus data pembelian
	function deletePembelian($id_hapus, $statusK, $statusW){

		if($statusK == 0 && $statusW == 0){
			// Query mysql delete data
			$query = "DELETE FROM pembelian WHERE id_pembelian = '$id_hapus'";

			// Mengeksekusi query
			return $this->execute($query);
		}

		else {
			if($statusW == 0){
				// Query
				$query = "UPDATE pembelian SET status_wishlist = 0 WHERE id_pembelian = '$id_hapus'";

				// Mengeksekusi query
				return $this->execute($query);
			}
			if($statusK == 0){
				// Query 
				$query = "UPDATE pembelian SET status_keranjang = 0 WHERE id_pembelian = '$id_hapus'";

				// Mengeksekusi query
				return $this->execute($query);
			}
		}
	}

	// Menghapus data pembelian
	function deletePembelianAdmin($id_hapus){

		// Query mysql delete data
		$query = "DELETE FROM pembelian WHERE id_pembelian = '$id_hapus'";

		// Mengeksekusi query
		return $this->execute($query);
	}

}

?>