<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

$id = $_GET['id'];

// Ambil nama gambar produk
$data = mysqli_query(
    $koneksi,
    "SELECT gambar FROM produk WHERE id='$id'"
);

$produk = mysqli_fetch_assoc($data);

if (!$produk) {
    echo "<script>
            alert('Produk tidak ditemukan');
            window.location='index.php';
          </script>";
    exit;
}

// Hapus data produk
$query = mysqli_query(
    $koneksi,
    "DELETE FROM produk WHERE id='$id'"
);

if ($query) {

    // Hapus file gambar jika ada
    if (
        !empty($produk['gambar']) &&
        file_exists("../../uploads/" . $produk['gambar'])
    ) {
        unlink("../../uploads/" . $produk['gambar']);
    }

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Hapus Produk</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<script>

Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Produk berhasil dihapus',
    confirmButtonText: 'OK',
    confirmButtonColor: '#198754'
}).then(() => {

    window.location.href = 'index.php';

});

</script>

</body>
</html>

<?php

}

?>