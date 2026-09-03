<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

$query = mysqli_query($koneksi, "
    SELECT
        produk.*,
        kategori.nama_kategori
    FROM produk
    JOIN kategori
        ON produk.kategori_id = kategori.id
    ORDER BY produk.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-3">Data Produk</h2>

    <a href="tambah.php" class="btn btn-success mb-3">
        + Tambah Produk
    </a>

    <a href="../dashboard.php" class="btn btn-secondary mb-3">
        Dashboard
    </a>

    <div class="table-responsive">

        <table class="table table-bordered table-striped align-middle">

            <thead class="table-success">

                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Varian</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th width="180">Aksi</th>
                </tr>

            </thead>

            <tbody>

            <?php
            $no = 1;

            while ($row = mysqli_fetch_assoc($query)) {

                // Ambil semua varian produk ini
                $produk_id = $row['id'];

                $query_varian = mysqli_query(
                    $koneksi,
                    "SELECT *
                     FROM varian_produk
                     WHERE produk_id = '$produk_id'
                     ORDER BY id ASC"
                );

                $varian = [];

                while ($v = mysqli_fetch_assoc($query_varian)) {
                    $varian[] = $v;
                }
            ?>

                <tr>

                    <!-- No -->
                    <td>
                        <?= $no++; ?>
                    </td>

                    <!-- Gambar -->
                    <td>

                        <?php if (!empty($row['gambar'])) { ?>

                            <img
                                src="../../uploads/<?= htmlspecialchars($row['gambar']); ?>"
                                width="80"
                                class="img-thumbnail"
                            >

                        <?php } else { ?>

                            <span class="text-muted">
                                Tidak ada
                            </span>

                        <?php } ?>

                    </td>

                    <!-- Nama Produk -->
                    <td>
                        <?= htmlspecialchars($row['nama_produk']); ?>
                    </td>

                    <!-- Kategori -->
                    <td>
                        <?= htmlspecialchars($row['nama_kategori']); ?>
                    </td>

                    <!-- Varian -->
                    <td>

                        <?php if (count($varian) > 0) { ?>

                            <?php foreach ($varian as $v) { ?>

                                <div class="mb-2">
                                    <?= htmlspecialchars($v['nama_varian']); ?>
                                </div>

                            <?php } ?>

                        <?php } else { ?>

                            <span class="text-muted">
                                -
                            </span>

                        <?php } ?>

                    </td>

                    <!-- Harga -->
                    <td>

                        <?php if (count($varian) > 0) { ?>

                            <?php foreach ($varian as $v) { ?>

                                <div class="mb-2">
                                    Rp<?= number_format($v['harga'], 0, ',', '.'); ?>
                                </div>

                            <?php } ?>

                        <?php } else { ?>

                            Rp<?= number_format($row['harga'], 0, ',', '.'); ?>

                        <?php } ?>

                    </td>

                    <!-- Stok -->
                    <td>

                        <?php if (count($varian) > 0) { ?>

                            <?php foreach ($varian as $v) { ?>

                                <div class="mb-2">
                                    <?= htmlspecialchars($v['stok']); ?>
                                </div>

                            <?php } ?>

                        <?php } else { ?>

                            <?= htmlspecialchars($row['stok']); ?>

                        <?php } ?>

                    </td>

                    <!-- Aksi -->
                    <td>

                        <a
                            href="edit.php?id=<?= $row['id']; ?>"
                            class="btn btn-warning btn-sm"
                        >
                            Edit
                        </a>

                        <a
                            href="hapus.php?id=<?= $row['id']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return konfirmasiHapus(event, this.href)"
                        >
                            Hapus
                        </a>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>


<script>

function konfirmasiHapus(event, url) {

    event.preventDefault();

    Swal.fire({

        title: 'Hapus produk?',

        text: 'Data produk yang dihapus tidak dapat dikembalikan.',

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