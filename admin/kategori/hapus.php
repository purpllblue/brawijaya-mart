<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

$id = $_GET['id'];

$query = mysqli_query(
    $koneksi,
    "DELETE FROM kategori WHERE id='$id'"
);

if ($query) {
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Hapus Kategori</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<script>

Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Kategori berhasil dihapus',
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