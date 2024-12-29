<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  	<title>LOGIN WEB</title>
  	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<div class="header">
  		<h2>Login</h2>
		<h4>Khusus Admin</h4>
	</div>
	 
	<form method="post" action="login.php">
  		<?php include('errors.php'); ?>
  		<div class="input-group">
  			<label>Username</label>
			<input type="text" name="username" >
  		</div>
  		<div class="input-group">
  			<label>Password</label>
			<input type="password" name="password">
  		</div>
  		<div class="input-group">
  			<button type="submit" class="btn" name="login_user">Login</button>
		</div>
  		<p>
  			Belum punya akun? <a href="register.php">Daftar</a>
  		</p><br>
		<a href="http://localhost/web_penjualan/crud/dashboard.html" class="btn" role="button">Kembali</a>
  	</form>

  	
</body>
</html>
