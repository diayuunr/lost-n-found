<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" href="public/output.css">
	<link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body id="bg-login" class="relative h-screen flex items-center justify-center bg-gradient-to-b from-[#53B789]/20 to-[#FFFFFF]/20">
	<button onclick="window.history.back()" class="text-lg absolute font-semibold cursor-pointer top-40 left-0 px-70 flex items-center gap-2 text-black hover:text-gray-700 transition">
		<i data-lucide="circle-arrow-left" class="w-5 h-5"></i> Kembali
	</button>
	<div class="border border-[#53B789] rounded-2xl p-8 bg-white/80 backdrop-blur-md shadow-lg w-full max-w-md">
		<h2 class="font-semibold text-3xl">Login</h2>
		<p class="text-gray-500 mb-6">Access is restricted to authorized staff only</p>
		<div>
		<form action="" method="POST" class="flex-col flex items-center justify-center gap-4">
			<div class="flex flex-col gap-1 w-full">
			<h2 class="font-semibold text-md justify-start">Username</h2>
			<input type="text" name="user" placeholder="Enter your username" class="flex input-control border border-[#53B789] px-4 py-1.5 rounded-lg outline-none">
			</div>
			<div class="flex flex-col gap-1 w-full mb-3">
			<h2 class="font-semibold text-md justify-start">Password</h2>
			<div class="relative flex input-control border border-[#53B789] px-4 py-1.5 rounded-lg">
			<input type="password" id="password" name="pass" placeholder="Enter your password" class="flex input-control w-full outline-none">
			<button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"><i data-lucide="eye" class="w-5 h-5"></i></button>
			</div>
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

	<script src="https://unpkg.com/lucide@latest"></script>

	<script>
	lucide.createIcons();

	const passwordInput = document.getElementById('password');
	const toggleButton = document.getElementById('togglePassword');

	let isVisible = false;

	toggleButton.addEventListener('click', () => {
		isVisible = !isVisible;

		passwordInput.type = isVisible ? 'text' : 'password';

		toggleButton.innerHTML = isVisible
		? '<i data-lucide="eye-off" class="w-5 h-5"></i>'
		: '<i data-lucide="eye" class="w-5 h-5"></i>';

		lucide.createIcons();
	});
	</script>
</body>
</html>