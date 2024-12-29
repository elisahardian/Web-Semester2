<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  	<title>REGISTRASI WEB</title>
  	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  	<div class="header">
  		<h2>Register</h2>
  	</div>
	
  	<form method="post" action="register.php">
  		<?php include('errors.php'); ?>
	
  		<div class="input-group">
  	  		<label>NIK</label>
  	  		<input type="text" name="nik" value="<?php echo $nik; ?>">
  		</div>
  		<div class="input-group">
  	  		<label>Nama</label>
  	  		<input type="text" name="nama" value="<?php echo $nama; ?>">
  		</div>
  		<div class="input-group">
  	  		<label>Alamat</label>
  	  		<input type="text" name="alamat" value="<?php echo $alamat; ?>">
  		</div>
		<div class="input-group">
  	  		<label>Tgl Lahir</label>
  	  		<input type="date" name="tgllahir" value="<?php echo $tgllahir; ?>">
  		</div><br>
  		<div class="radio-container">
  	  		<label>Jenis Kelamin</label>
			<input type="radio" name="jeniskelamin" value="Pria"> Pria
	  		<input type="radio" name="jeniskelamin" value="Perempuan"> Perempuan
  		</div><br>
  		<div class="radio-container">
  	  		<label>Status</label>
			<input type="radio" name="status" value="Menikah"> Menikah
	  		<input type="radio" name="status" value="Belum"> Belum		
	  		<input type="radio" name="status" value="Pisah"> Pisah
  		</div><br>
		<div class="input-group">
  	  		<label>Gaji</label>
  	  		<input type="text" name="gaji" value="<?php echo $gaji; ?>">
  		</div>
  	

  		<div class="input-group">
  	  		<label>Username</label>
  	  		<input type="text" name="username" value="<?php echo $username; ?>">
  		</div>
  		<div class="input-group">
  	  		<label>Email</label>
  	  		<input type="email" name="email" value="<?php echo $email; ?>">
  		</div>
  		<div class="input-group">
  	  		<label>Password</label>
  	  		<input type="password" name="password_1">
  		</div>
  		<div class="input-group">
  	  		<label>Confirm Password</label>
  	  		<input type="password" name="password_2">
  		</div>
  		<div class="input-group">
  	  		<button type="submit" class="btn" name="reg_user">Register</button>
  		</div>
  		<p>
  			Sudah punya akun? <a href="login.php">Login</a>
  		</p><br>
		<a href="http://localhost/web_pegawai/crud/dashboard.html" class="btn" role="button">Kembali</a>
  	</form>
</body>
</html>