<?php
include 'koneksi.php';
if ($_SESSION['level'] != 'admin') { header("Location: index.php"); exit; }

// --- LOGIKA PROSES ---
if (isset($_POST['save'])) {
    $n = $_POST['nama']; $u = $_POST['user']; $l = $_POST['level'];
    $p = !empty($_POST['pass']) ? md5($_POST['pass']) : $_POST['old_pass'];
    
    if(!empty($_POST['id_user'])){ // Edit
        $id = $_POST['id_user'];
        mysqli_query($conn, "UPDATE pengguna SET nama_lengkap='$n', username='$u', password='$p', level='$l' WHERE id_user='$id'");
    } else { // Tambah Baru
        mysqli_query($conn, "INSERT INTO pengguna (username, password, nama_lengkap, level) VALUES ('$u', '$p', '$n', '$l')");
    }
    header("Location: admin_user.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pengguna WHERE id_user='$id'");
    header("Location: admin_user.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100 p-10 font-sans">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-black text-slate-800 uppercase italic">Manajemen Pegawai</h1>
            <div class="flex gap-3">
                <a href="index.php" class="bg-slate-200 px-5 py-2 rounded-xl font-bold text-sm">Kembali</a>
                <button onclick="openModal()" class="bg-indigo-600 text-white px-5 py-2 rounded-xl font-bold text-sm shadow-lg">+ Tambah Pegawai</button>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest">
                    <tr>
                        <th class="p-5">Nama Lengkap</th>
                        <th class="p-5">Username</th>
                        <th class="p-5">Akses</th>
                        <th class="p-5 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    <?php $q = mysqli_query($conn, "SELECT * FROM pengguna");
                    while($d = mysqli_fetch_assoc($q)): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="p-5 font-bold text-slate-700"><?= $d['nama_lengkap'] ?></td>
                        <td class="p-5 font-mono text-indigo-600"><?= $d['username'] ?></td>
                        <td class="p-5"><span class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black uppercase"><?= $d['level'] ?></span></td>
                        <td class="p-5 text-center space-x-4">
                            <button onclick='editUser(<?= json_encode($d) ?>)' class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                            <a href="admin_user.php?hapus=<?= $d['id_user'] ?>" class="text-red-400 hover:text-red-600" onclick="return confirm('Hapus user ini?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalUser" class="fixed inset-0 bg-slate-900/60 hidden flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl">
            <h2 id="modalTitle" class="text-xl font-black mb-6 uppercase text-center text-slate-800">Tambah Pegawai</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="id_user" id="id_user">
                <input type="hidden" name="old_pass" id="old_pass">
                
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="w-full border-2 p-3 rounded-2xl outline-none focus:border-indigo-500 font-bold" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">Username</label>
                    <input type="text" name="user" id="user" class="w-full border-2 p-3 rounded-2xl outline-none focus:border-indigo-500 font-bold" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">Password (Kosongi jika tidak diubah)</label>
                    <input type="password" name="pass" class="w-full border-2 p-3 rounded-2xl outline-none focus:border-indigo-500 font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">Hak Akses</label>
                    <select name="level" id="level" class="w-full border-2 p-3 rounded-2xl font-bold bg-slate-50">
                        <option value="pegawai">Pegawai (Input Data)</option>
                        <option value="admin">Admin (Full Kontrol)</option>
                    </select>
                </div>

                <div class="flex gap-2 pt-6">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-slate-100 py-4 rounded-2xl font-bold text-slate-500 uppercase text-xs">Batal</button>
                    <button type="submit" name="save" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-bold shadow-lg uppercase text-xs">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalUser').classList.remove('hidden');
            document.getElementById('id_user').value = '';
            document.getElementById('modalTitle').innerText = 'Tambah Pegawai';
        }
        function closeModal() {
            document.getElementById('modalUser').classList.add('hidden');
        }
        function editUser(data) {
            openModal();
            document.getElementById('modalTitle').innerText = 'Edit Pegawai';
            document.getElementById('id_user').value = data.id_user;
            document.getElementById('nama').value = data.nama_lengkap;
            document.getElementById('user').value = data.username;
            document.getElementById('level').value = data.level;
            document.getElementById('old_pass').value = data.password;
        }
    </script>
</body>
</html>