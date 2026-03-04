<?php 
include 'koneksi.php';
if(!isset($_SESSION['username'])) header("Location: login.php");

// Hitung total aset
$res = mysqli_query($conn, "SELECT SUM(jumlah * harga_beli) as modal, SUM(jumlah * harga_jual) as aset_skrg FROM barang");
$stat = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Sarpras</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    <div class="w-64 bg-slate-900 text-white min-h-screen p-5">
        <h2 class="text-2xl font-bold mb-10 italic">SARPRAS</h2>
        <nav class="space-y-4">
            <a href="dashboard.php" class="block p-3 bg-indigo-600 rounded-xl"><i class="fas fa-home mr-2"></i> Dashboard</a>
            <a href="tambah_barang.php" class="block p-3 hover:bg-slate-800 rounded-xl"><i class="fas fa-plus mr-2"></i> Tambah Aset</a>
            <?php if($_SESSION['level'] == 'admin'): ?>
            <a href="admin_user.php" class="block p-3 hover:bg-slate-800 rounded-xl"><i class="fas fa-users-cog mr-2"></i> Kelola Pegawai</a>
            <?php endif; ?>
            <a href="logout.php" class="block p-3 text-red-400 mt-10"><i class="fas fa-power-off mr-2"></i> Keluar</a>
        </nav>
    </div>

    <div class="flex-1 p-10">
        <div class="grid grid-cols-2 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow border-l-8 border-gray-400">
                <p class="text-gray-400 text-xs font-bold uppercase">Total Nilai Pengadaan</p>
                <h2 class="text-2xl font-black text-gray-700">Rp <?= number_format($stat['modal'] ?? 0, 0, ',', '.') ?></h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow border-l-8 border-indigo-600">
                <p class="text-indigo-400 text-xs font-bold uppercase">Estimasi Nilai Aset Saat Ini</p>
                <h2 class="text-2xl font-black text-indigo-600">Rp <?= number_format($stat['aset_skrg'] ?? 0, 0, ',', '.') ?></h2>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-5 bg-slate-800 text-white flex justify-between">
                <span class="font-bold">Daftar Barang</span>
                <button onclick="window.print()" class="text-xs bg-slate-600 px-3 py-1 rounded">Cetak Laporan</button>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase">
                    <tr>
                        <th class="p-4">Nama Barang</th>
                        <th class="p-4">Harga Beli</th>
                        <th class="p-4">Harga Jual</th>
                        <th class="p-4">Stok</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php 
                    $q = mysqli_query($conn, "SELECT * FROM barang");
                    while($d = mysqli_fetch_assoc($q)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-bold"><?= $d['nama_barang'] ?></td>
                        <td class="p-4">Rp <?= number_format($d['harga_beli'], 0, ',', '.') ?></td>
                        <td class="p-4 text-indigo-600 font-bold">Rp <?= number_format($d['harga_jual'], 0, ',', '.') ?></td>
                        <td class="p-4 font-bold"><?= $d['jumlah'] ?></td>
                        <td class="p-4 space-x-2">
                            <a href="edit_barang.php?id=<?= $d['id_barang'] ?>" class="text-blue-500"><i class="fas fa-edit"></i></a>
                            <?php if($_SESSION['level'] == 'admin'): ?>
                            <a href="hapus.php?id=<?= $d['id_barang'] ?>" class="text-red-500" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>