<?php 
	session_start();
	include 'db.php';
	if($_SESSION['status_login'] != true){
		echo '<script>window.location="login.php"</script>';
	}
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
<body class="bg-gradient-to-b from-[#53B789]/20 to-[#FFFFFF]/20 h-screen overflow-hidden px-30 ">
	<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
	<!-- header -->
	<header>
		<nav class="fixed top-7 left-1/2 -translate-x-1/2 w-[90%] max-w-7xl rounded-4xl bg-white/80 backdrop-blur-md shadow-lg px-5 pl-7 py-2">
		<div class="flex items-center justify-between">

			<h1 class="text-2xl font-bold">
				<a href="dashboard-admin.php">
					LOST <span class="text-[#53B789]">&</span> FOUND
				</a>
			</h1>

			<div class="flex items-center gap-3">

				<!-- Tambah Barang -->
				<a href="tambah-produk.php">
					<div class="flex items-center gap-3 text-md px-5 py-2 rounded-3xl cursor-pointer transition
						<?php echo ($current_page == 'tambah-produk.php') 
						? 'text-[#53B789] font-semibold' 
						: 'text-black'; ?>">
						
						Tambah Barang
					</div>
				</a>

				<!-- Kelola Barang -->
				<a href="kelola-barang.php">
					<div class="flex items-center gap-3 text-md px-5 py-2 rounded-3xl cursor-pointer transition
						<?php echo ($current_page == 'kelola-barang.php') 
						? 'text-[#53B789] font-semibold' 
						: 'text-black'; ?>">
						
						Kelola Barang
					</div>
				</a>

				<!-- Logout -->
				<a href="keluar.php">
					<div class="flex items-center gap-3 text-white bg-black hover:bg-gray-800 text-md px-5 py-2 rounded-3xl cursor-pointer transition">
						Logout
					</div>
				</a>

			</div>
		</div>
		</nav>
	</header>

	<!-- category -->
	<div class="flex items-center justify-between h-[20%] pt-40 pb-15">
	<div class="flex flex-col gap-1 justify-start ml-3">
		<h3 class="text-4xl font-semibold">Kelola Barang</h3>
		<p class="text-gray-500 font-medium text-md">Pantau dan kelola seluruh barang temuan.</p>
	</div>
	<div class="flex justify-end gap-3">
		<div class="flex items-center gap-3 border border-black rounded-2xl p-1 bg-white">
			<!-- Semua -->
			<a href="kelola-barang.php">
				<div class="<?php echo (!isset($_GET['kat']) || $_GET['kat'] == '') 
					? 'bg-black text-white' 
					: 'text-black hover:bg-gray-100'; ?> 
					px-4 py-1.5 rounded-xl transition">
					
					<p>Semua</p>
				</div>
			</a>

			<?php 
				$kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id ASC");
				if(mysqli_num_rows($kategori) > 0){
					while($k = mysqli_fetch_array($kategori)){
			?>

			<a href="kelola-barang.php?kat=<?php echo $k['category_id'] ?>">
				<div class="<?php echo (isset($_GET['kat']) && $_GET['kat'] == $k['category_id']) 
					? 'bg-black text-white' 
					: 'text-black hover:bg-gray-100'; ?> 
					px-4 py-1.5 rounded-xl transition">

					<p><?php echo $k['category_name'] ?></p>
				</div>
			</a>

			<?php }}else{ ?>
				<p>Kategori tidak ada</p>
			<?php } ?>

		</div>
		<div class="flex items-center rounded-3xl border border-gray-300 p-2 px-3 bg-white">
			<form action="kelola-barang.php" class="flex items-center gap-3">
				<input 
				type="text" 
				name="search" 
				placeholder="Cari barang..."
				value="<?php echo $_GET['search'] ?? '' ?>" 
				class="px-2 py-1 outline-none focus:outline-none focus:ring-0 focus:ring-offset-0 w-64"
				>

				<input 
				type="hidden" 
				name="kat" 
				value="<?php echo $_GET['kat'] ?? '' ?>"
				>

				<button 
				type="submit" 
				name="cari" 
				class="bg-[#53B789] text-white px-3 py-1.5 rounded-xl hover:bg-[#469c74] transition cursor-pointer"
				><i data-lucide="search" class="w-5 h-5"></i></button>
			</form>
		</div>
	</div>
	</div>

	<!-- new product -->
	<div>
		<div class="flex flex-wrap gap-3 justify-center">
		<?php 
			$where = "";

			if(isset($_GET['search']) && $_GET['search'] != ''){
				$search = mysqli_real_escape_string($conn, $_GET['search']);
				$where .= " AND p.product_name LIKE '%$search%'";
			}

			if(isset($_GET['kat']) && $_GET['kat'] != ''){
				$kat = (int)$_GET['kat'];
				$where .= " AND p.category_id = '$kat'";
			}

			// Pagination setup
			$per_page = 4;
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$offset = ($page - 1) * $per_page;

			// Hitung total produk
			$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_product p WHERE p.product_status = 1 $where");
			$total_row = mysqli_fetch_assoc($total_query);
			$total_produk = $total_row['total'];
			$total_page = ceil($total_produk / $per_page);

			// Query produk dengan LIMIT + JOIN kategori
			$produk = mysqli_query($conn, "SELECT p.*, c.category_name 
				FROM tb_product p 
				LEFT JOIN tb_category c ON p.category_id = c.category_id 
				WHERE p.product_status = 1 $where 
				ORDER BY p.product_id DESC 
				LIMIT $per_page OFFSET $offset");

			if(mysqli_num_rows($produk) > 0){
				while($p = mysqli_fetch_array($produk)){
		?>
				<a href="detail-produk.php?id=<?php echo $p['product_id'] ?>">
					<div class="relative w-75 h-auto flex-col border border-gray-300 rounded-xl bg-white hover:shadow-lg transition">
						<!-- Badge Kategori -->
						<div class="absolute top-3 left-3 <?php echo (strtolower($p['category_name']) == 'diambil') ? 'bg-red-800/80' : 'bg-green-800/80' ?> text-white text-xs px-2 py-1 rounded-lg font-semibold">
							<?php echo $p['category_name'] ?>
						</div>
						<img src="produk/<?php echo $p['product_image'] ?>" class="w-full h-60 object-cover rounded-t-xl">
						<div class="p-3 gap-2 flex flex-col mt-auto">
						<div class="flex flex-col">
							<p class="font-semibold"><?php echo substr($p['product_name'], 0, 30) ?></p>
							<p class="font-normal text-xs text-gray-500 mb-3"><?php echo substr($p['product_description'], 0, 50) ?></p>
						</div>
						<div class="gap-1 flex flex-col">
							<p class="font-normal text-xs text-gray-500 flex items-center gap-2">
							<i data-lucide="map-pin" class="w-4 h-4"></i> <?php echo substr($p['product_location'], 0, 50) ?>
							</p>
							<p class="font-normal text-xs text-gray-500 flex items-center gap-2">
							<i data-lucide="calendar-check" class="w-4 h-4"></i> <?php echo substr($p['product_date'], 0, 50) ?>
							</p>
						</div>
						<div class="gap-2 flex justify-end">
							<a href="edit-barang.php?id=<?php echo $p['product_id'] ?>"><i data-lucide="pencil" class="w-5 h-5 hover:text-blue-500"></i></a>
							<a class="text-red-500" href="proses-hapus.php?idp=<?php echo $p['product_id'] ?>" onclick="return confirm('Yakin ingin hapus ?')"><i data-lucide="trash-2" class="w-5 h-5 hover:text-red-700"></i></a>
						</div>
						</div>
					</div>
				</a>
			<?php }}else{ ?>
				<p>Produk tidak ada</p>
			<?php } ?>
		</div>

		<!-- Pagination -->
		<?php if($total_page > 1){ ?>
		<div class="flex justify-center gap-2 mt-8">

			<!-- Tombol Prev -->
			<?php if($page > 1){ ?>
			<a href="?page=<?php echo $page-1 ?>&search=<?php echo $_GET['search'] ?? '' ?>&kat=<?php echo $_GET['kat'] ?? '' ?>">
				<div class="px-4 py-2 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 transition">
					&laquo;
				</div>
			</a>
			<?php } ?>

			<!-- Nomor Halaman -->
			<?php for($i = 1; $i <= $total_page; $i++){ ?>
			<a href="?page=<?php echo $i ?>&search=<?php echo $_GET['search'] ?? '' ?>&kat=<?php echo $_GET['kat'] ?? '' ?>">
				<div class="px-4 py-2 rounded-xl border transition
					<?php echo ($i == $page) ? 'bg-black text-white border-black' : 'bg-white border-gray-300 hover:bg-gray-100' ?>">
					<?php echo $i ?>
				</div>
			</a>
			<?php } ?>

			<!-- Tombol Next -->
			<?php if($page < $total_page){ ?>
			<a href="?page=<?php echo $page+1 ?>&search=<?php echo $_GET['search'] ?? '' ?>&kat=<?php echo $_GET['kat'] ?? '' ?>">
				<div class="px-4 py-2 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 transition">
					&raquo;
				</div>
			</a>
			<?php } ?>

		</div>
		<?php } ?>
	</div>

	<script src="https://unpkg.com/lucide@latest"></script>
	<script>
	lucide.createIcons();
	</script>
</body>
</html>