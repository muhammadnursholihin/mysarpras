<?php
include 'koneksi.php';
if (!isset($_SESSION['username'])) { exit; }

// Fungsi untuk konversi tanggal ke Bahasa Indonesia
function tanggal_indonesia($tanggal) {
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

$tgl_sekarang = tanggal_indonesia(date('Y-m-d'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Inventaris - SMP Muh 1 Blora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { 
            .no-print { display: none; } 
            body { padding: 0; }
        }
        body { font-family: 'Times New Roman', Times, serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-size: 12px; }
        td { font-size: 11px; }
    </style>
</head>
<body class="p-10 bg-white" onload="window.print()">

    <div class="text-center border-b-4 border-double border-black pb-4 mb-6">
        <h2 class="text-xl font-bold uppercase">Laporan Inventaris Sarana & Prasarana</h2>
        <h3 class="text-lg font-bold uppercase">SMP Muhammadiyah 1 Blora</h3>
        <p class="text-sm italic">Jl. KHA Dahlan No.9, Kauman Blora, Jawa Tengah</p>
    </div>

    <div class="mb-4 flex justify-between items-end">
        <div>
            <p class="text-sm">Dicetak pada: <b><?= $tgl_sekarang ?></b></p>
            <p class="text-sm">Oleh: <b><?= $_SESSION['nama'] ?></b></p>
        </div>
        <button onclick="window.print()" class="no-print bg-indigo-600 text-white px-4 py-2 rounded shadow text-xs font-bold">
            <i class="fas fa-print mr-2"></i> CETAK ULANG
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th style="width: 100px;">Kode Barang</th>
                <th>Nama Barang</th>
                <th style="width: 100px; text-align: center;">Asal Perolehan</th> <th style="width: 80px; text-align: center;">Kondisi</th>
                <th style="width: 50px; text-align: center;">Jumlah</th>
                <th style="text-align: right;">Harga Satuan</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $total_aset = 0;
            $q = mysqli_query($conn, "SELECT * FROM barang ORDER BY kategori ASC, kode_barang ASC");
            
            while($d = mysqli_fetch_assoc($q)): 
                $asal = $d['asal_perolehan'] ?? 'Pembelian'; // Pengaman jika data kosong
                $subtotal = $d['jumlah'] * $d['harga_beli'];
                $total_aset += $subtotal;
            ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td style="font-family: monospace;"><?= $d['kode_barang'] ?></td>
                <td><?= $d['nama_barang'] ?></td>
                <td style="text-align: center; font-weight: bold;"><?= strtoupper($asal) ?></td>
                <td style="text-align: center;"><?= $d['kondisi'] ?></td>
                <td style="text-align: center;"><?= $d['jumlah'] ?></td>
                <td style="text-align: right;">Rp <?= number_format($d['harga_beli'], 0, ',', '.') ?></td>
                <td style="text-align: right;">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="7" style="text-align: right;">TOTAL NILAI SELURUH ASET</td>
                <td style="text-align: right;">Rp <?= number_format($total_aset, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-12 grid grid-cols-2 text-center">
        <div>
            <p>Mengetahui,</p>
            <p class="mb-20 font-bold uppercase underline">Kepala Sekolah</p>
            <p class="font-bold">Muhammad Nur Sholihin, S.Pd</p>
            <p class="text-xs">NBM. .......................</p>
        </div>
        <div>
            <p>Blora, <?= $tgl_sekarang ?></p>
            <p class="mb-20 font-bold uppercase underline">Kepala Bagian Sarpras</p>
            <p class="font-bold">Amalia Laili Mukhasanah, S.Pd</p>
            <p class="text-xs">NBM. .......................</p>
        </div>
    </div>

</body>
</html>