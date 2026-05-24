<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" href="public/output.css">
	<link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body id="bg-login" class="min-h-screen flex items-center justify-center bg-gradient-to-b from-[#53B789]/20 to-[#FFFFFF]/20">
	<div class="border border-[#53B789] rounded-2xl p-8 bg-white/80 backdrop-blur-md shadow-lg w-full max-w-md">
		<h2 class="font-semibold text-3xl">Login</h2>
		<p class="text-gray-500 mb-6">Access is restricted to authorized staff only</p>
		<div>
		<form action="" method="POST" class="flex-col flex items-center justify-center gap-4">
			<div class="flex flex-col gap-1 w-full">
			<h2 class="font-semibold text-md justify-start">Username</h2>
			<input type="text" name="user" placeholder="Enter your username" class="flex input-control border border-[#53B789] px-4 py-1 rounded-lg">
			</div>
			<div class="flex flex-col gap-1 w-full mb-3">
			<h2 class="font-semibold text-md justify-start">Password</h2>
			<input type="password" name="pass" placeholder="Enter your password" class="flex input-control border border-[#53B789] px-4 py-1 rounded-lg">
			</div>
			<input type="submit" name="submit" value="Login" class="w-full flex bg-[#53B789] text-white px-4 py-1.5 rounded-lg hover:bg-[#469c74] transition cursor-pointer">
		</form>
		</div>
		<?php 
			if(isset($_POST['submit'])){
				session_start();
				include 'db.php';

				$user = mysqli_real_escape_string($conn, $_POST['user']);
				$pass = mysqli_real_escape_string($conn, $_POST['pass']);

				$cek = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '".$user."' AND password = '".MD5($pass)."'");
				if(mysqli_num_rows($cek) > 0){
					$d = mysqli_fetch_object($cek);
					$_SESSION['status_login'] = true;
					$_SESSION['a_global'] = $d;
					$_SESSION['id'] = $d->admin_id;
					echo '<script>window.location="dashboard.php"</script>';
				}else{
					echo '<script>alert("Username atau password Anda salah!")</script>';
				}

			}
		?>
	</div>
</body>
</html>