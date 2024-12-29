<?php
session_start();

// initializing variables
$nik ="";
$nama ="";
$alamat ="";
$tgllahir ="";
$jeniskelamin ="";
$status ="";
$gaji ="";

$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'db_pegawai');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form

  $nik = mysqli_real_escape_string($db, $_POST['nik']);
  $nama = mysqli_real_escape_string($db, $_POST['nama']);
  $alamat = mysqli_real_escape_string($db, $_POST['alamat']);
  $tgllahir = mysqli_real_escape_string($db, $_POST['tgllahir']);
  $jeniskelamin = mysqli_real_escape_string($db, $_POST['jeniskelamin']);
  $status = mysqli_real_escape_string($db, $_POST['status']);
  $gaji = mysqli_real_escape_string($db, $_POST['gaji']);

  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM pegawai WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = $password_1;// kalo pass mau di encrypt the password before saving in the database: md5($password_1)

  	$query = "INSERT INTO pegawai (nik, nama, alamat, tgllahir, jeniskelamin, status, gaji, username, email, password) 
  			  VALUES('$nik', '$nama', '$alamat', '$tgllahir', '$jeniskelamin', '$status','$gaji', '$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: login.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = $password; // kalo di enkripsi gini: md5($password)
  	$query = "SELECT * FROM pegawai WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: http://127.0.0.1:5000');
  	}else {
  		array_push($errors, "Wrong username/password combination");

  	}
  }
}

?>