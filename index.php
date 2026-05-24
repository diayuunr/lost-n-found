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
<body>
	<!-- header -->
	<header>
		<nav class="fixed top-5 left-1/2 -translate-x-1/2 w-[90%] max-w-6xl rounded-4xl bg-white/80 backdrop-blur-md shadow-lg px-10 py-2">
  		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-bold">
				<a href="index.php">LOST <span class="text-[#53B789]">&</span> FOUND</a>
			</h1>
			<div class="flex items-center gap-5">
			<div class="flex items-center rounded-3xl border border-gray-300 p-2">
			<form action="produk.php" class="flex items-center gap-3">
				<input 
				type="text" 
				name="search" 
				placeholder="Cari barang..."
				value="<?php echo $_GET['search'] ?? '' ?>" 
				class="px-2 py-1 outline-none focus:ring-2 focus:ring-[#53B789] w-64"
				>

				<input 
				type="hidden" 
				name="kat" 
				value="<?php echo $_GET['kat'] ?? '' ?>"
				>

				<input 
				type="submit" 
				name="cari" 
				value="Cari"
				class="bg-[#53B789] text-white px-2 py-1 rounded-xl hover:bg-[#469c74] transition cursor-pointer"
				>

			</form>
			</div>
			<div>
				<a href="login.php">Login</a>
			</div>
			</div>
		</div>
		</nav>
	</header>

	<!-- category -->
	<div class="section">
		<div class="container">
			<h3>Kategori</h3>
			<div class="box">
				<?php 
					$kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
					if(mysqli_num_rows($kategori) > 0){
						while($k = mysqli_fetch_array($kategori)){
				?>
					<a href="produk.php?kat=<?php echo $k['category_id'] ?>">
						<div class="col-5">
							<img src="img/icon-kategori.png" width="50px" style="margin-bottom:5px;">
							<p><?php echo $k['category_name'] ?></p>
						</div>
					</a>
				<?php }}else{ ?>
					<p>Kategori tidak ada</p>
				<?php } ?>
			</div>
		</div>
	</div>

	<!-- new product -->
	<div class="section">
		<div class="container">
			<h3>Produk Terbaru</h3>
			<div class="box">
				<?php 
					$produk = mysqli_query($conn, "SELECT * FROM tb_product WHERE product_status = 1 ORDER BY product_id DESC LIMIT 8");
					if(mysqli_num_rows($produk) > 0){
						while($p = mysqli_fetch_array($produk)){
				?>	
					<a href="detail-produk.php?id=<?php echo $p['product_id'] ?>">
						<div class="col-4">
							<img src="produk/<?php echo $p['product_image'] ?>">
							<p class="nama"><?php echo substr($p['product_name'], 0, 30) ?></p>
							<p class="harga">Rp. <?php echo number_format($p['product_price']) ?></p>
						</div>
					</a>
				<?php }}else{ ?>
					<p>Produk tidak ada</p>
				<?php } ?>
			</div>
		</div>
	</div>

	<!-- footer -->
	<div class="footer">
		<div class="container">
			<h4>Alamat</h4>
			<p><?php echo $a->admin_address ?></p>

			<h4>Email</h4>
			<p><?php echo $a->admin_email ?></p>

			<h4>No. Hp</h4>
			<p><?php echo $a->admin_telp ?></p>
			<small>Copyright &copy; 2020 - Lost & Found.</small>
		</div>
	</div>
</body>
</html>