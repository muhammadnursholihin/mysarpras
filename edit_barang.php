<?php
session_start();
include 'koneksi.php';

if ($_SESSION['level'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Ambil data barang berdasarkan ID
$id = $_GET['id'];
$get_data = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$id'");
$d = mysqli_fetch_assoc($get_data);

if (isset($_POST['update'])) {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $kat     = mysqli_real_escape_string($conn, $_POST['kategori']);
    $jumlah  = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);
    $lokasi  = mysqli_real_escape_string($conn, $_POST['lokasi']);

    $update = mysqli_query($conn, "UPDATE barang SET 
              nama_barang='$nama', kategori='$kat', jumlah='$jumlah', kondisi='$kondisi', lokasi='$lokasi' 
              WHERE id_barang='$id'");

    if ($update) {
        echo "<script>alert('Data Berhasil Diperbarui!'); window.location='index.php?p=barang';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Barang - Sarpras</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900/50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-orange-500 p-4 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-wider"><i class="fas fa-edit mr-2"></i> Edit Data Barang</h3>
            <a href="index.php?p=barang" class="hover:text-red-200 transition"><i class="fas fa-times text-xl"></i></a>
        </div>

        <form method="POST" class="p-8 space-y-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Kode Barang (Permanen)</label>
                <input type="text" value="<?= $d['kode_barang'] ?>" class="w-full border-2 p-3 rounded-xl bg-slate-100 font-bold text-gray-500 cursor-not-allowed" readonly>
            </div>

            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Nama Barang</label>
                <input type="text" name="nama_barang" value="<?= $d['nama_barang'] ?>" class="w-full border-2 p-3 rounded-xl outline-none focus:border-orange-500 font-bold" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Kategori</label>
                    <input type="text" name="kategori" value="<?= $d['kategori'] ?>" class="w-full border-2 p-3 rounded-xl outline-none focus:border-orange-500 font-bold" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Jumlah</label>
                    <input type="number" name="jumlah" value="<?= $d['jumlah'] ?>" class="w-full border-2 p-3 rounded-xl outline-none focus:border-orange-500 font-bold" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Kondisi</label>
                    <select name="kondisi" class="w-full border-2 p-3 rounded-xl outline-none focus:border-orange-500 font-bold">
                        <option value="Baik" <?= ($d['kondisi']=='Baik')?'selected':'' ?>>BAIK</option>
                        <option value="Rusak Ringan" <?= ($d['kondisi']=='Rusak Ringan')?'selected':'' ?>>RUSAK RINGAN</option>
                        <option value="Rusak Berat" <?= ($d['kondisi']=='Rusak Berat')?'selected':'' ?>>RUSAK BERAT</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Lokasi</label>
                    <input type="text" name="lokasi" value="<?= $d['lokasi'] ?>" class="w-full border-2 p-3 rounded-xl outline-none focus:border-orange-500 font-bold">
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <a href="index.php?p=barang" class="flex-1 bg-slate-200 text-slate-700 py-3 rounded-xl font-bold text-center hover:bg-slate-300 transition">BATAL</a>
                <button type="submit" name="update" class="flex-1 bg-orange-500 text-white py-3 rounded-xl font-bold hover:bg-orange-600 shadow-lg shadow-orange-200 transition uppercase tracking-widest">Update Data</button>
            </div>
        </form>
    </div>

</body>
</html>