<?php
session_start();
if ($_SESSION['level'] != 'admin') {
    echo "<script>alert('Hanya Admin yang boleh mengakses halaman ini!'); window.location='index.php';</script>";
    exit;
}
include 'koneksi.php';
?>
<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id'");

if($query){
    header("location:index.php?pesan=hapus");
} else {
    echo "Gagal menghapus data.";
}
?>