<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

// Ambil data kategori
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");

if (isset($_POST['simpan'])) {

    $kategori_id = $_POST['kategori_id'];
    $nama        = trim($_POST['nama_produk']);
    $deskripsi   = trim($_POST['deskripsi']);

    // Cek apakah menggunakan varian
    $pakai_varian = isset($_POST['pakai_varian']) ? 1 : 0;

    // Upload gambar
    $namaFile = $_FILES['gambar']['name'];
    $tmpFile  = $_FILES['gambar']['tmp_name'];

    if ($namaFile != "") {

        $namaBaru = time() . "_" . $namaFile;

        move_uploaded_file(
            $tmpFile,
            "../../uploads/" . $namaBaru
        );

    } else {
        $namaBaru = NULL;
    }


    /*
    |--------------------------------------------------------------------------
    | PRODUK TANPA VARIAN
    |--------------------------------------------------------------------------
    */

    if ($pakai_varian == 0) {

        $harga = $_POST['harga'];
        $stok  = $_POST['stok'];

        $query = mysqli_query(
            $koneksi,
            "INSERT INTO produk
            (kategori_id, nama_produk, harga, stok, gambar, deskripsi)
            VALUES
            ('$kategori_id', '$nama', '$harga', '$stok', '$namaBaru', '$deskripsi')"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PRODUK DENGAN VARIAN
    |--------------------------------------------------------------------------
    */

    else {

        // Untuk produk dengan varian,
        // harga dan stok utama dikosongkan
        $query = mysqli_query(
            $koneksi,
            "INSERT INTO produk
            (kategori_id, nama_produk, harga, stok, gambar, deskripsi)
            VALUES
            ('$kategori_id', '$nama', 0, '', '$namaBaru', '$deskripsi')"
        );

        if ($query) {

            // Ambil ID produk yang baru saja dibuat
            $produk_id = mysqli_insert_id($koneksi);

            // Simpan semua varian
            if (isset($_POST['nama_varian'])) {

                foreach ($_POST['nama_varian'] as $i => $nama_varian) {

                    $nama_varian = trim($nama_varian);
                    $harga_varian = $_POST['harga_varian'][$i];
                    $stok_varian  = trim($_POST['stok_varian'][$i]);

                    if ($nama_varian != "") {

                        mysqli_query(
                            $koneksi,
                            "INSERT INTO varian_produk
                            (produk_id, nama_varian, harga, stok)
                            VALUES
                            ('$produk_id', '$nama_varian', '$harga_varian', '$stok_varian')"
                        );
                    }
                }
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SWEET ALERT
    |--------------------------------------------------------------------------
    */

    if ($query) {
        ?>

        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <title>Tambah Produk</title>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>

        <body>

        <script>

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Produk berhasil ditambahkan',
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

    <title>Tambah Produk</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <h2>Tambah Produk</h2>

    <form method="POST" enctype="multipart/form-data">

        <!-- Nama Produk -->

        <div class="mb-3">

            <label class="form-label">
                Nama Produk
            </label>

            <input
                type="text"
                name="nama_produk"
                class="form-control"
                required
            >

        </div>


        <!-- Kategori -->

        <div class="mb-3">

            <label class="form-label">
                Kategori
            </label>

            <select
                name="kategori_id"
                class="form-select"
                required
            >

                <option value="">
                    -- Pilih Kategori --
                </option>

                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                    <option value="<?= $k['id']; ?>">

                        <?= htmlspecialchars($k['nama_kategori']); ?>

                    </option>

                <?php } ?>

            </select>

        </div>


        <!-- Pilihan Varian -->

        <div class="mb-3">

            <label class="form-label">
                Produk memiliki varian?
            </label>

            <div>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="pakai_varian"
                        id="pakai_varian"
                    >

                    <label
                        class="form-check-label"
                        for="pakai_varian"
                    >
                        Ya, produk memiliki varian
                    </label>

                </div>

            </div>

        </div>


        <!-- Harga & Stok Tanpa Varian -->

        <div id="tanpaVarian">

            <div class="mb-3">

                <label class="form-label">
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    class="form-control"
                    id="harga"
                >

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Stok
                </label>

                <input
                    type="text"
                    name="stok"
                    class="form-control"
                    id="stok"
                    placeholder="Contoh: Tersedia"
                >

            </div>

        </div>


        <!-- Varian -->

        <div
            id="denganVarian"
            style="display: none;"
        >

            <h5 class="mb-3">
                Varian Produk
            </h5>

            <div id="daftarVarian">

                <div class="row mb-2 varian-item">

                    <div class="col-md-4">

                        <input
                            type="text"
                            name="nama_varian[]"
                            class="form-control"
                            placeholder="Contoh: 5 kg"
                        >

                    </div>

                    <div class="col-md-4">

                        <input
                            type="number"
                            name="harga_varian[]"
                            class="form-control"
                            placeholder="Harga"
                        >

                    </div>

                    <div class="col-md-3">

                        <input
                            type="text"
                            name="stok_varian[]"
                            class="form-control"
                            placeholder="Contoh: Tersedia"
                        >

                    </div>

                    <div class="col-md-1">

                        <button
                            type="button"
                            class="btn btn-danger"
                            onclick="hapusVarian(this)"
                        >
                            ×
                        </button>

                    </div>

                </div>

            </div>


            <button
                type="button"
                class="btn btn-outline-success btn-sm mb-3"
                onclick="tambahVarian()"
            >
                + Tambah Varian
            </button>

        </div>


        <!-- Deskripsi -->

        <div class="mb-3">

            <label class="form-label">
                Deskripsi
            </label>

            <textarea
                name="deskripsi"
                class="form-control"
                rows="4"
            ></textarea>

        </div>


        <!-- Gambar -->

        <div class="mb-3">

            <label class="form-label">
                Gambar
            </label>

            <input
                type="file"
                name="gambar"
                class="form-control"
                accept="image/*"
            >

        </div>


        <!-- Tombol -->

        <button
            type="submit"
            name="simpan"
            class="btn btn-success"
        >
            Simpan
        </button>

        <a
            href="index.php"
            class="btn btn-secondary"
        >
            Kembali
        </a>

    </form>

</div>


<script>

const checkboxVarian = document.getElementById('pakai_varian');

const tanpaVarian = document.getElementById('tanpaVarian');

const denganVarian = document.getElementById('denganVarian');

const harga = document.getElementById('harga');

const stok = document.getElementById('stok');


checkboxVarian.addEventListener('change', function () {

    if (this.checked) {

        tanpaVarian.style.display = 'none';

        denganVarian.style.display = 'block';

        harga.required = false;

        stok.required = false;

    } else {

        tanpaVarian.style.display = 'block';

        denganVarian.style.display = 'none';

        harga.required = true;

        stok.required = true;

    }

});


function tambahVarian() {

    const daftar = document.getElementById('daftarVarian');

    const div = document.createElement('div');

    div.className = 'row mb-2 varian-item';

    div.innerHTML = `

        <div class="col-md-4">

            <input
                type="text"
                name="nama_varian[]"
                class="form-control"
                placeholder="Contoh: 10 kg"
            >

        </div>

        <div class="col-md-4">

            <input
                type="number"
                name="harga_varian[]"
                class="form-control"
                placeholder="Harga"
            >

        </div>

        <div class="col-md-3">

            <input
                type="text"
                name="stok_varian[]"
                class="form-control"
                placeholder="Contoh: Tersedia"
            >

        </div>

        <div class="col-md-1">

            <button
                type="button"
                class="btn btn-danger"
                onclick="hapusVarian(this)"
            >
                ×
            </button>

        </div>

    `;

    daftar.appendChild(div);
}


function hapusVarian(button) {

    const item = button.closest('.varian-item');

    item.remove();

}

</script>

</body>

</html>