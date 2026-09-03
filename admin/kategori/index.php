<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

$data = mysqli_query($koneksi, "SELECT * FROM kategori");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Kategori</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<div class="container mt-5">

    <h2>Data Kategori</h2>

    <a href="tambah.php" class="btn btn-success mb-3">
        + Tambah Kategori
    </a>

    <a href="../dashboard.php" class="btn btn-secondary mb-3">
        Dashboard
    </a>

    <table class="table table-bordered">

        <thead class="table-success">

            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th width="180">Aksi</th>
            </tr>

        </thead>

        <tbody>

        <?php

        $no = 1;

        while ($row = mysqli_fetch_assoc($data)) {

        ?>

        <tr>

            <td>
                <?= $no++; ?>
            </td>

            <td>
                <?= htmlspecialchars($row['nama_kategori']); ?>
            </td>

            <td>

                <a
                    href="edit.php?id=<?= $row['id']; ?>"
                    class="btn btn-warning btn-sm">
                    Edit
                </a>

                <a
                    href="hapus.php?id=<?= $row['id']; ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return konfirmasiHapus(event, this.href)">
                    Hapus
                </a>

            </td>

        </tr>

        <?php } ?>

        </tbody>

    </table>

</div>


<script>

function konfirmasiHapus(event, url) {

    event.preventDefault();

    Swal.fire({

        title: 'Hapus kategori?',

        text: 'Data kategori yang dihapus tidak dapat dikembalikan.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText: 'Ya, Hapus',

        cancelButtonText: 'Batal',

        confirmButtonColor: '#dc3545',

        cancelButtonColor: '#6c757d'

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = url;

        }

    });

    return false;

}

</script>

</body>

</html>