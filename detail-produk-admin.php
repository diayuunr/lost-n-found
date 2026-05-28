<?php 
	error_reporting(0);
	include 'db.php';
	$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 1");
	$a = mysqli_fetch_object($kontak);

	$produk = mysqli_query($conn, "SELECT * FROM tb_product WHERE product_id = '".$_GET['id']."' ");
	$p = mysqli_fetch_object($produk);
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
<body class="bg-gradient-to-b from-[#53B789]/20 to-[#FFFFFF]/20 h-screen px-30 overflow-hidden">
	<!-- header -->
	<header>
		<nav class="fixed top-7 left-1/2 -translate-x-1/2 w-[90%] max-w-7xl rounded-4xl bg-white/80 backdrop-blur-md shadow-lg px-5 pl-7 py-2">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-bold">
				<a href="dashboard-admin.php">LOST <span class="text-[#53B789]">&</span> FOUND</a>
			</h1>
			<div class="flex items-center gap-3">
				<a href="tambah-produk.php">
					<div class="flex items-center gap-3 text-md px-5 py-2 rounded-3xl cursor-pointer transition">
						Tambah Barang
					</div>
				</a>
				<a href="kelola-barang.php">
					<div class="flex items-center gap-3 text-md px-5 py-2 rounded-3xl cursor-pointer transition">
						Kelola Barang
					</div>
				</a>
				<a href="keluar.php">
					<div class="flex items-center gap-3 text-white bg-black hover:bg-gray-800 text-md px-5 py-2 rounded-3xl cursor-pointer transition">Logout</div>
				</a>
			</div>
		</div>
		</nav>
	</header>

	<!-- product detail -->
	<div class="relative flex items-center justify-center pt-35">
		<button onclick="window.history.back()" class="absolute font-semibold cursor-pointer top-35 left-0 flex items-center gap-2 text-black hover:text-gray-700 transition">
			<i data-lucide="circle-arrow-left" class="w-5 h-5"></i> Kembali
		</button>
		<div class="flex items-center justify-center">
		<?php 
			$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

			$produk = mysqli_query($conn, "SELECT p.*, c.category_name 
				FROM tb_product p 
				LEFT JOIN tb_category c ON p.category_id = c.category_id 
				WHERE p.product_id = $id 
				LIMIT 1");

			if(mysqli_num_rows($produk) > 0){
				$p = mysqli_fetch_object($produk);
		?>
			<div class="flex items-start gap-10 border border-gray-300 rounded-3xl bg-white w-full max-w-4xl">
				<div class="w-1/2 h-full">
					<img src="produk/<?php echo $p->product_image ?>" class="w-full h-full object-cover rounded-3xl">
				</div>
				<div class="py-6">
					<div class="w-20 items-center text-center <?php echo (strtolower($p->category_name) == 'diambil') ? 'bg-red-800/80' : 'bg-green-800/80' ?> text-white text-sm px-2 py-2 mb-5 rounded-lg font-semibold">
						<?php echo $p->category_name ?>
					</div>
					<h3 class="text-xl font-semibold"><?php echo $p->product_name ?></h3>
					<h4 class="mb-4"><?php echo $p->product_description ?></h4>
					<div class="flex flex-col">
						<p class="flex items-center font-semibold gap-2"><i data-lucide="map-pin" class="w-5 h-5"></i>LOKASI DITEMUKAN</p>
						<p class="pl-7 mb-4"><?php echo $p->product_location ?></p>
					</div>
					<div class="flex flex-col">
						<p class="flex items-center font-semibold gap-2"><i data-lucide="calendar-check" class="w-5 h-5"></i>TANGGAL DITEMUKAN</p>
						<p class="pl-7"><?php echo $p->product_date ?></p>
					</div>
					<div class="flex gap-3 mt-10 w-full items-center justify-center">
						<a href="edit-barang.php?id=<?php echo $p->product_id ?>" class="bg-[#53B789] flex gap-2 items-center justify-center text-center text-white px-4 py-1.5 rounded-xl hover:bg-[#469c74] transition w-full">
							<i data-lucide="pencil" class="w-4 h-4"></i> Edit
						</a>
						<a href="proses-hapus.php?idp=<?php echo $p->product_id ?>" class="bg-red-600 flex gap-2 items-center text-center justify-center text-white px-4 py-1.5 rounded-xl hover:bg-red-700 transition w-full" onclick="return confirm('Yakin ingin menghapus barang ini?')">
							<i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
						</a>
					</div>
				</div>
			</div>
		<?php }else{ ?>
			<p>Produk tidak ada</p>
		<?php } ?>
		</div>
	</div>

	<script src="https://unpkg.com/lucide@latest"></script>
	<script>
	lucide.createIcons();
	</script>
</body>
</html>