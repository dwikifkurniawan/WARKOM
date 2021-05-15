<?php 

class Kategori extends DB{
	
	// Mengambil data kategori
	function getKategori(){
		// Query mysql select data ke Kategori
		$query = "SELECT * FROM kategori";

		// Mengeksekusi query
		return $this->execute($query);
	}
}

?>