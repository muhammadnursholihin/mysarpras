<?php 
include 'koneksi.php';
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit; }

$p = $_GET['p'] ?? 'dashboard';

// --- LOGIKA HAPUS (Hanya Admin) ---
if(isset($_GET['hapus']) && $_SESSION['level'] == 'admin'){
    $id = $_GET['hapus'];
    $del = mysqli_query($conn, "DELETE FROM barang WHERE id_barang='$id'");
    if($del) {
        echo "<script>alert('Data Berhasil Dihapus'); window.location='index.php?p=barang';</script>";
    }
}

// --- HITUNG STATISTIK ASET ---
$stat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah * harga_beli) as beli, SUM(jumlah * harga_jual) as jual FROM barang"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarpras SMP Muh 1 Blora - <?= ucfirst($p) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100 flex min-h-screen font-sans">

    <div class="w-64 bg-slate-900 text-white p-6 fixed h-full shadow-2xl">
        <div class="text-2xl font-black mb-10 text-center text-indigo-400 border-b border-slate-800 pb-4">
            SARPRAS
        </div>
        <nav class="space-y-2">
            <a href="index.php?p=dashboard" class="flex items-center p-3 rounded-2xl hover:bg-slate-800 transition <?= ($p=='dashboard')?'bg-indigo-600 shadow-lg':'' ?>">
                <i class="fas fa-th-large mr-3 w-5"></i> Dashboard
            </a>
            <a href="index.php?p=barang" class="flex items-center p-3 rounded-2xl hover:bg-slate-800 transition <?= ($p=='barang')?'bg-indigo-600 shadow-lg':'' ?>">
                <i class="fas fa-box mr-3 w-5"></i> Daftar Barang
            </a>
            <a href="tambah_barang.php" class="flex items-center p-3 rounded-2xl hover:bg-slate-800 transition">
                <i class="fas fa-plus-circle mr-3 w-5"></i> Tambah Aset
            </a>
            <a href="cetak.php" target="_blank" class="flex items-center p-3 rounded-2xl hover:bg-slate-800 transition">
                <i class="fas fa-print mr-3 w-5"></i> Cetak Laporan
            </a>
            
            <?php if($_SESSION['level'] == 'admin'): ?>
            <div class="pt-6 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Administrator</div>
            <a href="admin_user.php" class="flex items-center p-3 rounded-2xl hover:bg-slate-800 transition">
                <i class="fas fa-users-cog mr-3 w-5"></i> Kelola User
            </a>
            <?php endif; ?>

            <div class="pt-10">
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')" class="flex items-center p-3 rounded-2xl bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition">
                    <i class="fas fa-sign-out-alt mr-3 w-5"></i> Keluar
                </a>
            </div>
        </nav>
    </div>

    <div class="ml-64 w-full p-8">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight"><?= $p ?> Sistem</h1>
                <p class="text-sm text-slate-500">Selamat datang, <span class="font-bold text-indigo-600"><?= $_SESSION['nama'] ?></span></p>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Status Login</p>
                    <p class="text-xs font-bold text-slate-700"><?= ucfirst($_SESSION['level']) ?></p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-black">
                    <?= substr($_SESSION['nama'] ?? 'U', 0, 1) ?>
                </div>
            </div>
        </div>

        <?php if($p == 'dashboard'): ?>
            <div class="grid grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 border-indigo-500">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Modal Aset</p>
                    <h3 class="text-2xl font-black text-slate-800">Rp <?= number_format($stat['beli'] ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 border-emerald-500">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Estimasi Nilai Sekarang</p>
                    <h3 class="text-2xl font-black text-slate-800">Rp <?= number_format($stat['jual'] ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="bg-indigo-900 p-6 rounded-3xl shadow-lg">
                    <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-2">Total Item Barang</p>
                    <?php $jml_brg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM barang")); ?>
                    <h3 class="text-2xl font-black text-white"><?= $jml_brg['total'] ?> <span class="text-sm font-normal opacity-60 text-white">Jenis Barang</span></h3>
                </div>
            </div>

        <?php elseif($p == 'barang'): ?>
            <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-black text-slate-700 uppercase tracking-widest text-sm">Data Master Inventaris</h3>
                    <a href="tambah_barang.php" class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-black transition shadow-lg">
                        <i class="fas fa-plus mr-2"></i> TAMBAH DATA
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-slate-500 text-[10px] uppercase tracking-widest border-b bg-slate-50">
                                <th class="p-4">Nama Barang & Kode</th>
                                <th class="p-4">Asal Perolehan</th>
                                <th class="p-4 text-center">Kondisi</th>
                                <th class="p-4">Lokasi</th>
                                <th class="p-4 text-center">Stok</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php 
                            $q = mysqli_query($conn, "SELECT * FROM barang ORDER BY id_barang DESC");
                            while($d = mysqli_fetch_assoc($q)): 
                                // PENGAMAN: Jika kolom asal_perolehan tidak ada di DB, tampilkan 'Pembelian'
                                $asal = $d['asal_perolehan'] ?? 'Pembelian';
                            ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4">
                                    <div class="font-bold text-slate-700"><?= $d['nama_barang'] ?></div>
                                    <div class="text-[10px] font-mono text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded w-fit mt-1"><?= $d['kode_barang'] ?></div>
                                </td>
                                <td class="p-4">
                                    <?php if($asal == 'Hibah'): ?>
                                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                            🎁 <?= $asal ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                            🛒 <?= $asal ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg <?= ($d['kondisi']=='Baik')?'bg-green-100 text-green-600':'bg-red-100 text-red-600' ?>">
                                        <?= strtoupper($d['kondisi']) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-xs font-medium text-slate-600 italic"><?= $d['lokasi'] ?></td>
                                <td class="p-4 text-center font-black text-slate-700"><?= $d['jumlah'] ?></td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="edit_barang.php?id=<?= $d['id_barang'] ?>" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <?php if($_SESSION['level'] == 'admin'): ?>
                                        <a href="index.php?p=barang&hapus=<?= $d['id_barang'] ?>" onclick="return confirm('Hapus aset ini?')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm">
                                            <i class="fas fa-trash text-xs"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>