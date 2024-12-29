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
		<h4>Khusus Admin</h4>
  	</div>
	
  	<form method="post" action="register.php">
  		<?php include('errors.php'); ?>
	
  		<div class="input-group">
  	  		<label>NIP</label>
  	  		<input type="text" name="nip" value="<?php echo $nip; ?>">
  		</div>
  		<div class="input-group">
  	  		<label>Nama</label>
  	  		<input type="text" name="nama" value="<?php echo $nama; ?>">
  		</div>
  		<div class="input-group">
  	  		<label>Username</label>
  	  		<input type="text" name="username" value="<?php echo $username; ?>">
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
		<a href="http://localhost/web_penjualan/crud/dashboard.html" class="btn" role="button">Kembali</a>
  	</form>
</body>
</html>