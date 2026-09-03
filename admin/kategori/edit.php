<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

$id = $_GET['id'];

$data = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id='$id'");
$kategori = mysqli_fetch_assoc($data);

if (!$kategori) {
    echo "<script>
            alert('Kategori tidak ditemukan');
            window.location='index.php';
          </script>";
    exit;
}

if (isset($_POST['update'])) {

    $nama = trim($_POST['nama_kategori']);

    $query = mysqli_query(
        $koneksi,
        "UPDATE kategori
         SET nama_kategori='$nama'
         WHERE id='$id'"
    );

    if ($query) {
        ?>

        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Edit Kategori</title>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>

        <body>

        <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Kategori berhasil diubah',
            confirmButtonText: 'OK',
            confirmButtonColor: '#198754'
        }).then(() => {
            window.location.href = 'index.php';
        });
        </script>

        </body>
        </html>

        <?php
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Edit Kategori</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <h2>Edit Kategori</h2>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label">
                Nama Kategori
            </label>

            <input
                type="text"
                name="nama_kategori"
                class="form-control"
                value="<?= htmlspecialchars($kategori['nama_kategori']); ?>"
                required
            >

        </div>

        <button
            type="submit"
            name="update"
            class="btn btn-warning">
            Update
        </button>

        <a
            href="index.php"
            class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

</body>
</html>