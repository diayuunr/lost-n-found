<?php 
    session_start();
    include 'db.php';

    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
        exit;
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    $produk = mysqli_query($conn, "SELECT p.*, c.category_name 
        FROM tb_product p 
        LEFT JOIN tb_category c ON p.category_id = c.category_id 
        WHERE p.product_id = $id 
        LIMIT 1");

    if(mysqli_num_rows($produk) == 0){
        echo '<script>window.location="kelola-barang.php"</script>';
        exit;
    }

    $p = mysqli_fetch_object($produk);

    if(isset($_POST['submit'])){

        $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
        $nama      = mysqli_real_escape_string($conn, $_POST['nama']);
        $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
        $lokasi    = mysqli_real_escape_string($conn, $_POST['lokasi']);
        $tanggal   = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $foto      = $_POST['foto'];

        $filename = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];

        if($filename != ''){
            $type_parts = explode('.', $filename);
            $type2 = strtolower(end($type_parts));

            $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

            if(!in_array($type2, $tipe_diizinkan)){
                echo '<script>alert("Format file tidak diizinkan")</script>';
            } else {
                $newname = 'produk' . time() . '.' . $type2;

                if(file_exists('./produk/' . $foto)){
                    unlink('./produk/' . $foto);
                }

                move_uploaded_file($tmp_name, './produk/' . $newname);
                $namagambar = $newname;
            }
        } else {
            $namagambar = $foto;
        }

        if(isset($namagambar)){
            $update = mysqli_query($conn, "UPDATE tb_product SET 
                category_id         = '$kategori',
                product_name        = '$nama',
                product_description = '$deskripsi',
                product_image       = '$namagambar',
                product_location    = '$lokasi',
                product_date        = '$tanggal'
                WHERE product_id    = $id");

            if($update){
                echo '<script>alert("Ubah data berhasil"); window.location="kelola-barang.php";</script>';
                exit;
            } else {
                echo '<script>alert("Gagal menyimpan: ' . mysqli_error($conn) . '")</script>';
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
<body class="bg-gradient-to-b from-[#53B789]/20 to-[#FFFFFF]/20 h-dvh flex flex-col overflow-hidden">

    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>

    <!-- header -->
    <header>
        <nav class="fixed top-7 left-1/2 -translate-x-1/2 w-[90%] max-w-7xl rounded-4xl bg-white/80 backdrop-blur-md shadow-lg px-5 pl-7 py-2 z-10">
            <div class="flex items-center justify-between">

                <h1 class="text-2xl font-bold">
                    <a href="dashboard-admin.php">
                        LOST <span class="text-[#53B789]">&</span> FOUND
                    </a>
                </h1>

                <div class="flex items-center gap-3">

                    <a href="tambah-produk.php">
                        <div class="flex items-center gap-3 text-md px-5 py-2 rounded-3xl cursor-pointer transition
                            <?php echo ($current_page == 'tambah-produk.php') ? 'text-[#53B789] font-semibold' : 'text-black'; ?>">
                            Tambah Barang
                        </div>
                    </a>

                    <a href="kelola-barang.php">
                        <div class="flex items-center gap-3 text-md px-5 py-2 rounded-3xl cursor-pointer transition
                            <?php echo ($current_page == 'kelola-barang.php') ? 'text-[#53B789] font-semibold' : 'text-black'; ?>">
                            Kelola Barang
                        </div>
                    </a>

                    <a href="keluar.php">
                        <div class="flex items-center gap-3 text-white bg-black hover:bg-gray-800 text-md px-5 py-2 rounded-3xl cursor-pointer transition">
                            Logout
                        </div>
                    </a>

                </div>
            </div>
        </nav>
    </header>

    <!-- content -->
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
                                <h3 class="text-4xl font-semibold">Kelola Barang</h3>
                                <p class="text-gray-500 font-medium text-md">Pantau dan kelola seluruh barang temuan.</p>
                            </div>

                            <!-- gambar -->
                            <div class="rounded-xl border-2 border-[#53B789] p-6 px-8 flex flex-col justify-center items-center w-full">
                                <img 
                                    src="produk/<?php echo $p->product_image ?>" 
                                    class="w-full h-70 object-cover rounded-2xl mb-5"
                                >

                                <input type="hidden" name="foto" value="<?php echo $p->product_image ?>">
                                <input 
                                    type="file" 
                                    name="gambar"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-1.5 cursor-pointer outline-none focus:ring-2 focus:ring-[#53B789]"
                                >

                            </div>
                        </div>

                        <!-- form fields -->
                        <div class="p-8 flex flex-col justify-between">
                            <div>
                                <!-- kategori -->
                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Status Barang</label>
                                    <select 
                                        name="kategori"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]"
                                        required
                                    >
                                        <?php 
                                            $kategori_list = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
                                            while($r = mysqli_fetch_array($kategori_list)){
                                        ?>
                                        <option 
                                            value="<?php echo $r['category_id'] ?>"
                                            <?php echo ($r['category_id'] == $p->category_id) ? 'selected' : ''; ?>
                                        >
                                            <?php echo $r['category_name'] ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- nama -->
                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Nama Barang</label>
                                    <input 
                                        type="text"
                                        name="nama"
                                        value="<?php echo htmlspecialchars($p->product_name) ?>"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]"
                                        required
                                    >
                                </div>

                                <!-- deskripsi -->
                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Deskripsi</label>
                                    <textarea 
                                        name="deskripsi"
                                        rows="2"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]"
                                    ><?php echo htmlspecialchars($p->product_description) ?></textarea>
                                </div>

                                <!-- lokasi -->
                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Lokasi Ditemukan</label>
                                    <input 
                                        type="text"
                                        name="lokasi"
                                        value="<?php echo htmlspecialchars($p->product_location) ?>"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]"
                                    >
                                </div>

                                <!-- tanggal -->
                                <div class="mb-3">
                                    <label class="block mb-2 font-semibold">Tanggal Ditemukan</label>
                                    <input 
                                        type="date"
                                        name="tanggal"
                                        value="<?php echo $p->product_date ?>"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-1.5 outline-none focus:ring-2 focus:ring-[#53B789]"
                                    >
                                </div>

                            </div>

                            <!-- button -->
                            <div class="flex gap-3">
                            <button 
                                type="submit"
                                name="submit"
                                class="mt-3 w-full bg-[#53B789] hover:bg-[#469c74] transition text-white font-semibold py-1.5 rounded-xl flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i data-lucide="save" class="w-5 h-5"></i>
                                Simpan Perubahan
                            </button>
                            <button 
                                href="proses-hapus.php?idp=<?php echo $p->product_id ?>" onclick="return confirm('Yakin ingin hapus ?')"
                                class="mt-3 w-full hover:bg-red-700 bg-red-600 text-white transition font-semibold py-1.5 rounded-xl flex items-center justify-center gap-2 cursor-pointer" onclick="window.location='kelola-barang.php'; return false;">
                                <i data-lucide="trash" class="w-5 h-5"></i>
                                Hapus Barang
                            </button>
                            </div>

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>

</body>
</html>