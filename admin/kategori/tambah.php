<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

$berhasil = false;

if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama_kategori']);

    if ($nama != "") {

        $query = mysqli_query(
            $koneksi,
            "INSERT INTO kategori (nama_kategori) VALUES ('$nama')"
        );

        if ($query) {
            $berhasil = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<div class="container mt-5">

    <h2>Tambah Kategori</h2>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label">
                Nama Kategori
            </label>

            <input
                type="text"
                name="nama_kategori"
                class="form-control"
                required>

        </div>

        <button
            type="submit"
            name="simpan"
            class="btn btn-success">
            Simpan
        </button>

        <a
            href="index.php"
            class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>


<?php if ($berhasil): ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Kategori berhasil ditambahkan',
    showConfirmButton: true,
    confirmButtonText: 'OK',
    confirmButtonColor: '#198754'
}).then((result) => {

    if (result.isConfirmed) {
        window.location.href = 'index.php';
    }

});
</script>

<?php endif; ?>


</body>
</html>