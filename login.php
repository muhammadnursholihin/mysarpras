<?php
include 'koneksi.php';

// Jika sudah login, langsung lempar ke index
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $u = mysqli_real_escape_string($conn, $_POST['user']);
    $p = md5($_POST['pass']);
    
    $query = mysqli_query($conn, "SELECT * FROM pengguna WHERE username='$u' AND password='$p'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['id_user']  = $data['id_user'];
        $_SESSION['nama']     = $data['nama_lengkap'];
        $_SESSION['level']    = $data['level'];
        $_SESSION['username'] = $data['username'];
        
        header("Location: index.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SARPRAS SMP Muh 1 Blora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 text-white rounded-2xl shadow-lg mb-4">
                <i class="fas fa-university text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">SARPRAS DIGITAL</h1>
            <p class="text-slate-500 text-sm">SMP Muhammadiyah 1 Blora</p>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-200">
            <h2 class="text-xl font-bold text-slate-700 mb-6">Silakan Masuk</h2>

            <?php if(isset($error)): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-bold mb-4 border border-red-100 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Username</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="user" required
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all font-semibold text-slate-700"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="pass" required
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all font-semibold text-slate-700"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" name="login"
                        class="w-full bg-indigo-600 hover:bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-200 transition-all duration-300 transform active:scale-95 uppercase text-xs tracking-widest">
                        Masuk ke Sistem <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-slate-400 text-[10px] mt-8 uppercase font-bold tracking-widest">
            &copy; <?= date('Y') ?> IT Support SMP Muh 1 Blora
        </p>
    </div>

</body>
</html>