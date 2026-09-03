<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

$id = $_GET['id'];

// Ambil data produk
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM produk WHERE id='$id'"
);

$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    header("Location: index.php");
    exit;
}

// Ambil kategori
$kategori = mysqli_query(
    $koneksi,
    "SELECT * FROM kategori"
);

// Ambil varian produk
$varian = mysqli_query(
    $koneksi,
    "SELECT * FROM varian_produk WHERE produk_id='$id'"
);

// Cek apakah produk memiliki varian
$jumlahVarian = mysqli_num_rows($varian);
$pakai_varian_awal = $jumlahVarian > 0;


// Proses update
if (isset($_POST['update'])) {

    $kategori_id = $_POST['kategori_id'];
    $nama        = trim($_POST['nama_produk']);
    $deskripsi   = trim($_POST['deskripsi']);

    $pakai_varian = isset($_POST['pakai_varian']) ? 1 : 0;

    $gambarLama = $produk['gambar'];


    // =====================================================
    // UPLOAD GAMBAR BARU
    // =====================================================

    if ($_FILES['gambar']['name'] != "") {

        $namaBaru = time() . "_" . $_FILES['gambar']['name'];

        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            "../../uploads/" . $namaBaru
        );

        // Hapus gambar lama
        if (
            $gambarLama != "" &&
            file_exists("../../uploads/" . $gambarLama)
        ) {
            unlink("../../uploads/" . $gambarLama);
        }

    } else {

        $namaBaru = $gambarLama;

    }


    // =====================================================
    // JIKA TIDAK MENGGUNAKAN VARIAN
    // =====================================================

    if ($pakai_varian == 0) {

        $harga = $_POST['harga'];
        $stok  = $_POST['stok'];

        $update = mysqli_query(
            $koneksi,
            "UPDATE produk SET
                kategori_id='$kategori_id',
                nama_produk='$nama',
                harga='$harga',
                stok='$stok',
                gambar='$namaBaru',
                deskripsi='$deskripsi'
            WHERE id='$id'"
        );


        // Hapus semua varian lama
        mysqli_query(
            $koneksi,
            "DELETE FROM varian_produk
             WHERE produk_id='$id'"
        );

    }


    // =====================================================
    // JIKA MENGGUNAKAN VARIAN
    // =====================================================

    else {

        // Produk utama tidak menggunakan harga/stok
        $update = mysqli_query(
            $koneksi,
            "UPDATE produk SET
                kategori_id='$kategori_id',
                nama_produk='$nama',
                harga=0,
                stok='',
                gambar='$namaBaru',
                deskripsi='$deskripsi'
            WHERE id='$id'"
        );


        if ($update) {

            // ID varian lama yang masih digunakan
            $varian_lama = [];

            if (isset($_POST['varian_id'])) {

                foreach ($_POST['varian_id'] as $varian_id) {

                    $varian_lama[] = $varian_id;

                    $nama_varian  = trim(
                        $_POST['nama_varian'][$varian_id]
                    );

                    $harga_varian = $_POST['harga_varian'][$varian_id];

                    $stok_varian = trim(
                        $_POST['stok_varian'][$varian_id]
                    );


                    mysqli_query(
                        $koneksi,
                        "UPDATE varian_produk SET
                            nama_varian='$nama_varian',
                            harga='$harga_varian',
                            stok='$stok_varian'
                        WHERE id='$varian_id'
                        AND produk_id='$id'"
                    );
                }
            }


            // =====================================================
            // TAMBAH VARIAN BARU
            // =====================================================

            if (isset($_POST['nama_varian_baru'])) {

                foreach ($_POST['nama_varian_baru'] as $i => $nama_varian) {

                    $nama_varian = trim($nama_varian);

                    $harga_varian =
                        $_POST['harga_varian_baru'][$i];

                    $stok_varian =
                        trim($_POST['stok_varian_baru'][$i]);


                    if ($nama_varian != "") {

                        mysqli_query(
                            $koneksi,
                            "INSERT INTO varian_produk
                            (
                                produk_id,
                                nama_varian,
                                harga,
                                stok
                            )
                            VALUES
                            (
                                '$id',
                                '$nama_varian',
                                '$harga_varian',
                                '$stok_varian'
                            )"
                        );
                    }
                }
            }


            // =====================================================
            // HAPUS VARIAN YANG DICENTANG
            // =====================================================

            if (isset($_POST['hapus_varian'])) {

                foreach ($_POST['hapus_varian'] as $hapus_id) {

                    mysqli_query(
                        $koneksi,
                        "DELETE FROM varian_produk
                         WHERE id='$hapus_id'
                         AND produk_id='$id'"
                    );
                }
            }
        }
    }


    // =====================================================
    // SWEET ALERT
    // =====================================================

    if ($update) {
        ?>

        <!DOCTYPE html>
        <html lang="id">

        <head>

            <meta charset="UTF-8">

            <title>Edit Produk</title>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        </head>

        <body>

        <script>

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Produk berhasil diperbarui',
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

    <title>Edit Produk</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <h2>Edit Produk</h2>

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
                value="<?= htmlspecialchars($produk['nama_produk']); ?>"
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

                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                    <option
                        value="<?= $k['id']; ?>"
                        <?= ($produk['kategori_id'] == $k['id'])
                            ? 'selected'
                            : ''; ?>
                    >

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

            <div class="form-check">

                <input
                    class="form-check-input"
                    type="checkbox"
                    name="pakai_varian"
                    id="pakai_varian"
                    <?= $pakai_varian_awal ? 'checked' : ''; ?>
                >

                <label
                    class="form-check-label"
                    for="pakai_varian"
                >
                    Ya, produk memiliki varian
                </label>

            </div>

        </div>


        <!-- ========================================== -->
        <!-- HARGA DAN STOK TANPA VARIAN -->
        <!-- ========================================== -->

        <div
            id="tanpaVarian"
            style="<?= $pakai_varian_awal
                ? 'display:none;'
                : 'display:block;'; ?>"
        >

            <div class="mb-3">

                <label class="form-label">
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    id="harga"
                    class="form-control"
                    value="<?= $produk['harga']; ?>"
                    <?= $pakai_varian_awal ? '' : 'required'; ?>
                >

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Stok
                </label>

                <input
                    type="text"
                    name="stok"
                    id="stok"
                    class="form-control"
                    value="<?= htmlspecialchars($produk['stok']); ?>"
                    placeholder="Contoh: Tersedia"
                    <?= $pakai_varian_awal ? '' : 'required'; ?>
                >

            </div>

        </div>


        <!-- ========================================== -->
        <!-- VARIAN -->
        <!-- ========================================== -->

        <div
            id="denganVarian"
            style="<?= $pakai_varian_awal
                ? 'display:block;'
                : 'display:none;'; ?>"
        >

            <h5 class="mb-3">
                Varian Produk
            </h5>


            <div id="daftarVarian">

                <?php

                // Ambil ulang data varian
                $varian = mysqli_query(
                    $koneksi,
                    "SELECT * FROM varian_produk
                     WHERE produk_id='$id'"
                );

                while ($v = mysqli_fetch_assoc($varian)) {

                ?>

                <div class="row mb-2 varian-item">

                    <div class="col-md-4">

                        <input
                            type="hidden"
                            name="varian_id[]"
                            value="<?= $v['id']; ?>"
                        >

                        <input
                            type="text"
                            name="nama_varian[<?= $v['id']; ?>]"
                            class="form-control"
                            value="<?= htmlspecialchars($v['nama_varian']); ?>"
                        >

                    </div>


                    <div class="col-md-4">

                        <input
                            type="number"
                            name="harga_varian[<?= $v['id']; ?>]"
                            class="form-control"
                            value="<?= $v['harga']; ?>"
                        >

                    </div>


                    <div class="col-md-3">

                        <input
                            type="text"
                            name="stok_varian[<?= $v['id']; ?>]"
                            class="form-control"
                            value="<?= htmlspecialchars($v['stok']); ?>"
                            placeholder="Contoh: Tersedia"
                        >

                    </div>


                    <div class="col-md-1">

                        <button
                            type="button"
                            class="btn btn-danger"
                            onclick="tandaiHapus(this)"
                        >
                            ×
                        </button>

                        <input
                            type="hidden"
                            name="hapus_varian[]"
                            value=""
                            disabled
                        >

                    </div>

                </div>

                <?php } ?>

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
            ><?= htmlspecialchars($produk['deskripsi']); ?></textarea>

        </div>


        <!-- Gambar Saat Ini -->

        <div class="mb-3">

            <label class="form-label">
                Gambar Saat Ini
            </label>

            <br>

            <?php if ($produk['gambar']) { ?>

                <img
                    src="../../uploads/<?= htmlspecialchars($produk['gambar']); ?>"
                    width="150"
                >

            <?php } ?>

        </div>


        <!-- Ganti Gambar -->

        <div class="mb-3">

            <label class="form-label">
                Ganti Gambar
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
            name="update"
            class="btn btn-warning"
        >
            Update
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

const checkboxVarian =
    document.getElementById('pakai_varian');

const tanpaVarian =
    document.getElementById('tanpaVarian');

const denganVarian =
    document.getElementById('denganVarian');

const harga =
    document.getElementById('harga');

const stok =
    document.getElementById('stok');


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

    const daftar =
        document.getElementById('daftarVarian');

    const div =
        document.createElement('div');

    div.className =
        'row mb-2 varian-item';

    div.innerHTML = `

        <div class="col-md-4">

            <input
                type="text"
                name="nama_varian_baru[]"
                class="form-control"
                placeholder="Contoh: 10 kg"
            >

        </div>

        <div class="col-md-4">

            <input
                type="number"
                name="harga_varian_baru[]"
                class="form-control"
                placeholder="Harga"
            >

        </div>

        <div class="col-md-3">

            <input
                type="text"
                name="stok_varian_baru[]"
                class="form-control"
                placeholder="Contoh: Tersedia"
            >

        </div>

        <div class="col-md-1">

            <button
                type="button"
                class="btn btn-danger"
                onclick="this.closest('.varian-item').remove()"
            >
                ×
            </button>

        </div>

    `;

    daftar.appendChild(div);

}


function tandaiHapus(button) {

    const item =
        button.closest('.varian-item');

    const hidden =
        item.querySelector(
            'input[name="hapus_varian[]"]'
        );

    hidden.value =
        item.querySelector(
            'input[name="varian_id[]"]'
        ).value;

    hidden.disabled = false;

    item.style.display = 'none';

}

</script>

</body>

</html>