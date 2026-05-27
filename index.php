<?php 
	include 'db.php';
	$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 1");
	$a = mysqli_fetch_object($kontak);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lost & Found</title>
	<link rel="stylesheet" href="public/output.css">
	<link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-b from-[#53B789]/20 to-[#FFFFFF]/20 h-screen overflow-hidden">
	<!-- header -->
	<header>
		<nav class="fixed top-7 left-1/2 -translate-x-1/2 w-[90%] max-w-7xl rounded-4xl bg-white/80 backdrop-blur-md shadow-lg px-5 pl-7 py-2">
  		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-bold">
				<a href="index.php">LOST <span class="text-[#53B789]">&</span> FOUND</a>
			</h1>
			<div class="flex items-center gap-3 bg-black hover:bg-gray-800 text-white text-md px-5 py-2 rounded-3xl cursor-pointer">
				<a href="login.php">Login</a>
			</div>
		</div>
		</nav>
	</header>

	<!-- content -->
	 <div class="flex items-center justify-between h-full px-15 pl-25 pt-20">
		<div class="container">
			<h3 class="text-7xl font-semibold mb-5 max-w-lg">Reconnect with What You Lost.</h3>
			<p class="text-gray-700 text-xl mb-10">Making lost item recovery faster, simpler, and more reliable</p>
			<a href="produk.php" class="inline-flex items-center gap-2 shadow-lg shadow-black border border-black bg-[#53B789] text-white px-5 py-2 rounded-lg hover:bg-[#469c74] transition">
				Search Items<i data-lucide="circle-arrow-right"></i></a>
		</div>
		<div class="hidden md:block">
			<img src="img/hero.png" alt="Hero Image" class="w-full h-auto">
		</div>
	</div>

	<script src="https://unpkg.com/lucide@latest"></script>
	<script>
	lucide.createIcons();
	</script>
</body>
</html>