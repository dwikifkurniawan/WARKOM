<?php 

class User extends DB{
	
	// Mengambil data
	function getUser(){
		// Query mysql select data ke user
		$query = "SELECT * FROM user";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getUserProfil($id){
		// Query mysql select data ke user
		$query = "SELECT * FROM user WHERE id=$id";

		// Mengeksekusi query
		return $this->execute($query);
	}

	function getUserName($id){
		// Query mysql select data ke user
		$query = "SELECT username FROM user WHERE id=$id";

		// Mengeksekusi query
		return $this->execute($query);
	}
	
	function insertUser(){
		//untuk attribut
		$email = $_POST['email'];
		$nama = $_POST['fullname'];
		$uname = $_POST['uname'];
		$password = $_POST['pwd1'];
        $password_md5 = md5($password);
		$status_admin = 0;
		
		// Query mysql insert data ke user
		$query = "INSERT INTO user (email, nama, username, password, status_admin) VALUES 
		('$email', '$nama', '$uname', '$password_md5', '$status_admin')";
		
		// Mengeksekusi query
		$input = $this->execute($query);

        if($input){
            echo("<script>
            alert('Register successful!');
            window.location.href = 'login.php';
            </script>");
        }else{
            echo("<script>
            alert('Username atau email telah terdaftar');
            window.location.href = 'register.php';
            </script>");
        }
	}

	function updateUser($id){
		//untuk attribut
		$email = $_POST['email'];
		$nama = $_POST['fullname'];
		$uname = $_POST['uname'];
		if(!empty($_POST['pwd1'])){
			$password = $_POST['pwd1'];
			$password_md5 = md5($password);

			// Query mysql update data ke user
			$query = "UPDATE user SET email = '$email', nama = '$nama', username = '$uname', password = '$password_md5' WHERE id = '$id'";
		}
		else {
			// Query mysql update data ke user
			$query = "UPDATE user SET email = '$email', nama = '$nama', username = '$uname' WHERE id = '$id'";
		}
		
		// Mengeksekusi query
		$input = $this->execute($query);

        if($input){
            echo("<script>
            alert('Update successful!');
            window.location.href = 'profil.php';
            </script>");
        }else{
            die('Failed to insert: '. mysqli_error($conn));
        }
	}

	function updateWallet($id, $harga){
		// Query mysql update data ke user
		$query = "UPDATE user SET wallet = '$harga' WHERE id = '$id'";
		
		// Mengeksekusi query
		return $this->execute($query);
	}

	function updateAdmin($id){
		$status = 1;

		// Query mysql update data ke user
		$query = "UPDATE user SET status_admin = '$status' WHERE id = '$id'";
		
		// Mengeksekusi query
		$input = $this->execute($query);

		if($input){
            echo("<script>
            alert('Update successful!');
            window.location.href = 'user.php';
            </script>");
        }else{
            die('Failed to insert: '. mysqli_error($conn));
        }
	}

	// memeriksa data login
	function checkUser(){
		session_start();
		$username = $_POST['uname'];
		$password = $_POST['pwd'];

		$password_md5 = md5($password);

		if($username != '' && $password != ''){

			$query = "SELECT * FROM user WHERE username='$username' AND password='$password_md5'";
			$input = $this->execute($query);
			$data = mysqli_fetch_assoc($input);

			if(mysqli_num_rows($input) < 1){
				echo("<script>
					alert('Maaf username atau password salah');
					window.location.href = 'login.php';
					</script>");
			}else{
				$_SESSION['uname'] = $data['username'];
				$_SESSION['email'] = $data['email'];
				$_SESSION['id'] = $data['id'];
				$_SESSION['status_admin'] = $data['status_admin'];

				setcookie("message", "", time()+60);
				if($_SESSION['status_admin'] == 1){
					echo("<script>
					alert('Selamat Datang Admin!');
					window.location.href = 'index.php';
					</script>");
				}else{
					echo("<script>
					alert('Login berhasil');
					window.location.href = 'index.php';
					</script>");
				}
			}

		}else{
			echo("<script>
			alert('email atau password kosong');
			window.location.href = 'login.php';
			</script>");
		}
	}

	// menghapus data user
	function deleteUser($id_hapus){

		// Query mysql delete data
		$query = "DELETE FROM user WHERE id = '$id_hapus'";

		// Mengeksekusi query
		return $this->execute($query);
	}

}

?>