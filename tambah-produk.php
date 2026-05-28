<?php 
	session_start();
	include 'db.php';
	if($_SESSION['status_login'] != true){
		echo '<script>window.location="login.php"</script>';
	}

    if(isset($_POST['submit'])){
        $kategori   = $_POST['kategori'];
        $nama       = $_POST['nama'];
        $deskripsi  = $_POST['deskripsi'];
        $lokasi     = $_POST['lokasi'];
        $tanggal    = $_POST['tanggal'];

        $filename = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        
        $type1 = explode('.', $filename);
        $type2 = strtolower(end($type1));

        $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

        if(!in_array($type2, $tipe_diizinkan)){
            echo '<script>alert("Format file tidak diizinkan")</script>';
        } else {
            $newname = 'produk'.time().'.'.$type2;
            move_uploaded_file($tmp_name, './produk/'.$newname);

            $insert = mysqli_query($conn, "INSERT INTO tb_product 
                (category_id, product_name, product_description, product_location, product_date, product_image, product_status) 
                VALUES 
                ('".$kategori."', '".$nama."', '".$deskripsi."', '".$lokasi."', '".$tanggal."', '".$newname."', 1)");

            if($insert){
                echo '<script>alert("Tambah data berhasil")</script>';
                echo '<script>window.location="kelola-barang.php"</script>';
            } else {
                echo 'gagal '.mysqli_error($conn);
            }
        }
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
	
	<header>
		<nav class="fixed top-7 left-1/2 -translate-x-1/2 w-[90%] max-w-7xl rounded-4xl bg-white/80 backdrop-blur-md shadow-lg px-5 pl-7 py-2">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-bold">
				<a href="dashboard-admin.php">LOST <span class="text-[#53B789]">&</span> FOUND</a>
			</h1>
			<div class="flex items-center gap-3">
				<a href="tambah-produk.php">
					<div class="flex items-center gap-3 text-md px-5 py-2 rounded-3xl cursor-pointer transition <?php echo ($current_page == 'tambah-produk.php') ? 'text-[#53B789] font-semibold' : 'text-black'; ?>">
						Tambah Barang
					</div>
				</a>
				<a href="kelola-barang.php">
					<div class="flex items-center gap-3 text-md px-5 py-2 rounded-3xl cursor-pointer transition <?php echo ($current_page == 'kelola-barang.php') ? 'text-[#53B789] font-semibold' : 'text-black'; ?>">
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

    <div class="flex-1 overflow-hidden pt-20">
        <div class="flex items-center justify-center px-6 py-10">
            <button onclick="window.history.back()" class="absolute font-semibold cursor-pointer top-35 left-35 flex items-center gap-2 text-black hover:text-gray-700 transition">
                <i data-lucide="circle-arrow-left" class="w-5 h-5"></i> Kembali
            </button>
            <div class="w-full max-w-5xl">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden grid md:grid-cols-2">

                        <div class="flex flex-col gap-3 p-8">
                            <div class="flex flex-col gap-1 justify-start mb-3">
                                <h3 class="text-4xl font-semibold">Tambah Barang</h3>
                                <p class="text-gray-500 font-medium text-md">Tambahkan barang temuan baru ke sistem.</p>
                            </div>

                            <div class="rounded-xl border-2 border-[#53B789] p-6 px-8 flex flex-col justify-center items-center w-full">
                                <div class="w-full h-70 bg-gray-100 rounded-2xl mb-5 flex items-center justify-center text-gray-400">
                                    <span class="text-sm">Preview gambar akan muncul di sini</span>
                                </div>
                                <input type="file" name="gambar" class="w-full border border-gray-300 rounded-xl px-4 py-1.5 cursor-pointer outline-none focus:ring-2 focus:ring-[#53B789]" required>
                            </div>
                        </div>

                        <div class="p-8 flex flex-col justify-between">
                            <div>
                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Status Barang</label>
                                    <select name="kategori" class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php 
                                            $kategori_list = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
                                            while($r = mysqli_fetch_array($kategori_list)){
                                        ?>
                                        <option value="<?php echo $r['category_id'] ?>"><?php echo $r['category_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Nama Barang</label>
                                    <input type="text" name="nama" placeholder="Masukkan nama barang" class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]" required>
                                </div>

                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Deskripsi</label>
                                    <textarea name="deskripsi" rows="2" placeholder="Masukkan deskripsi barang" class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Lokasi Ditemukan</label>
                                    <input type="text" name="lokasi" placeholder="Masukkan lokasi ditemukan" class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]">
                                </div>

                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Tanggal Ditemukan</label>
                                    <input type="date" name="tanggal" class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]">
                                </div>

                            </div>

                            <div class="flex gap-3">
                                <button type="submit" name="submit" class="mt-3 w-full bg-[#53B789] hover:bg-[#469c74] transition text-white font-semibold py-1.5 rounded-xl flex items-center justify-center gap-2 cursor-pointer">
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                    Tambah Barang
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<script src="https://unpkg.com/lucide@latest"></script>
	<script> lucide.createIcons();</script>
	<script>
		// Preview gambar saat file dipilih
		const inputGambar = document.querySelector('input[name="gambar"]');
		const previewContainer = inputGambar.closest('.flex-col').querySelector('.bg-gray-100');

		inputGambar.addEventListener('change', function(e) {
			const file = e.target.files[0];
			
			if (file) {
				const reader = new FileReader();
				
				reader.onload = function(e) {
					previewContainer.innerHTML = `
						<img src="${e.target.result}" class="w-full h-full object-cover rounded-2xl" alt="Preview">
					`;
				};
				
				reader.readAsDataURL(file);
			} else {
				// Reset ke placeholder kalau file dihapus
				previewContainer.innerHTML = `
					<span class="text-sm">Preview gambar akan muncul di sini</span>
				`;
			}
		});
	</script>
</body>
</html>