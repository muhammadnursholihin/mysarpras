<?php
include 'koneksi.php';
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit; }

// --- PROSES SIMPAN ---
if (isset($_POST['simpan'])) {
    $kode = $_POST['kode_barang'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']); 
    $kat  = $_POST['kat'];
    $jml  = $_POST['jml']; 
    $hb   = $_POST['hb']; 
    $hj   = $_POST['hj'];
    $kon  = $_POST['kon']; 
    $lok  = mysqli_real_escape_string($conn, $_POST['lok']);
    $asal = $_POST['asal_perolehan']; // Ambil data asal perolehan

    // Query dengan 9 Kolom
    $sql = "INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga_beli, harga_jual, kondisi, lokasi, asal_perolehan) 
            VALUES ('$kode', '$nama', '$kat', '$jml', '$hb', '$hj', '$kon', '$lok', '$asal')";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Aset Berhasil Disimpan!'); window.location='index.php?p=barang';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Input Aset - SMP Muh 1 Blora</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white w-full max-w-xl rounded-3xl shadow-xl border overflow-hidden font-sans">
        <div class="bg-indigo-900 p-6 text-white text-center">
            <h2 class="text-xl font-black uppercase tracking-widest text-yellow-400">Registrasi Inventaris</h2>
            <p class="text-[10px] opacity-70">DATA INDUK BARANG MILIK SEKOLAH</p>
        </div>
        
        <form method="POST" class="p-8 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">1. Kategori Induk</label>
                    <select name="kat" id="kat" onchange="updateBarang()" class="w-full border-2 p-3 rounded-2xl font-bold bg-white outline-none focus:border-indigo-600" required>
                        <option value="">-- Pilih --</option>
                        <option value="A">A. TANAH DAN BANGUNAN</option>
                        <option value="B">B. MEBEULAIR</option>
                        <option value="C">C. ELEKTRONIK</option>
                        <option value="D">D. MEKANIK</option>
                        <option value="E">E. ALAT RUMAH TANGGA</option>
                        <option value="F">F. PENDUKUNG KBM</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">2. Kode Inventaris</label>
                    <input type="text" name="kode_barang" id="kode_display" class="w-full bg-slate-50 border-2 p-3 rounded-2xl font-black text-indigo-700 outline-none" readonly placeholder="Otomatis...">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase">3. Nama Barang</label>
                <select name="nama_barang" id="nama_barang" onchange="generateFinalCode()" class="w-full border-2 p-3 rounded-2xl font-bold bg-white outline-none focus:border-indigo-600" required>
                    <option value="">-- Pilih Kategori Dulu --</option>
                </select>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border-2 border-dashed border-slate-200">
                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-2">4. Asal Perolehan Barang</label>
                <div class="flex gap-4">
                    <label class="flex-1 flex items-center justify-center p-3 bg-white border-2 rounded-xl cursor-pointer hover:border-indigo-500 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                        <input type="radio" name="asal_perolehan" value="Pembelian" class="mr-2" required checked> 
                        <span class="text-sm font-bold text-slate-700">🛒 PEMBELIAN</span>
                    </label>
                    <label class="flex-1 flex items-center justify-center p-3 bg-white border-2 rounded-xl cursor-pointer hover:border-emerald-500 transition has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="asal_perolehan" value="Hibah" class="mr-2"> 
                        <span class="text-sm font-bold text-slate-700">🎁 HIBAH</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input type="number" name="hb" placeholder="Harga Beli" class="w-full border-2 p-3 rounded-2xl font-bold outline-none" required>
                <input type="number" name="hj" placeholder="Estimasi Nilai" class="w-full border-2 p-3 rounded-2xl font-bold outline-none" required>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <input type="number" name="jml" placeholder="Jumlah" value="1" class="w-full border-2 p-3 rounded-2xl font-bold outline-none" required>
                <select name="kon" class="col-span-2 border-2 p-3 rounded-2xl font-bold bg-white outline-none">
                    <option value="Baik">Kondisi: BAIK</option>
                    <option value="Rusak Ringan">Kondisi: RUSAK RINGAN</option>
                    <option value="Rusak Berat">Kondisi: RUSAK BERAT</option>
                </select>
            </div>

            <input type="text" name="lok" placeholder="Lokasi (Contoh: Ruang Guru)" class="w-full border-2 p-3 rounded-2xl font-bold outline-none" required>

            <div class="flex gap-3 pt-4">
                <a href="index.php?p=barang" class="flex-1 bg-slate-200 text-center py-4 rounded-2xl font-bold text-slate-500 text-xs">BATAL</a>
                <button type="submit" name="simpan" class="flex-1 bg-indigo-700 text-white py-4 rounded-2xl font-bold shadow-lg text-xs hover:bg-black transition">SIMPAN DATA</button>
            </div>
        </form>
    </div>

    <script>
    const dataAset = {
        "A": { prefix: "A", items: ["TANAH", "BANGUNAN", "LAIN-LAIN"] },
        "B": { prefix: "B", items: ["MEJA SISWA", "KURSI SISWA", "MEJA KOMPUTER", "KURSI KOMPUTER", "LEMARI", "PAPAN TULIS", "LAIN-LAIN"] },
        "C": { prefix: "C", items: ["KOMPUTER (CPU)", "MONITOR", "PRINTER", "SCANNER", "TELEVISI", "SPEKAER", "LCD PROJECTOR"] },
        "D": { prefix: "D", items: ["MOBIL", "MOTOR", "MESIN RUMPUT"] },
        "E": { prefix: "E", items: ["TANGGA", "KOMPOR GAS", "ALAT KEBERSIHAN"] },
        "F": { prefix: "F", items: ["TENDA PRAMUKA", "GITAR", "ORGAN", "PIANIKA", "DRUM"] }
    };

    function updateBarang() {
        const kat = document.getElementById('kat').value;
        const selectBarang = document.getElementById('nama_barang');
        const displayKode = document.getElementById('kode_display');
        selectBarang.innerHTML = '<option value="">-- Pilih Barang --</option>';
        displayKode.value = "";
        if (kat in dataAset) {
            dataAset[kat].items.forEach((item, index) => {
                let opt = document.createElement('option');
                let noUrut = (index + 1).toString().padStart(2, '0');
                opt.value = item;
                opt.setAttribute('data-code', dataAset[kat].prefix + " " + noUrut);
                opt.innerHTML = dataAset[kat].prefix + " " + noUrut + " - " + item;
                selectBarang.appendChild(opt);
            });
        }
    }

    function generateFinalCode() {
        const selectBarang = document.getElementById('nama_barang');
        const selectedOption = selectBarang.options[selectBarang.selectedIndex];
        const displayKode = document.getElementById('kode_display');
        if(selectedOption.value !== "") {
            displayKode.value = selectedOption.getAttribute('data-code');
        }
    }
    </script>
</body>
</html>