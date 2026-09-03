<?php

include "config/koneksi.php";

$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';

$kategoriDipilih = isset($_GET['kategori'])
    ? (int)$_GET['kategori']
    : 0;

$urutan = isset($_GET['urutan']) ? $_GET['urutan'] : 'terbaru';


// =====================================================
// AMBIL SEMUA KATEGORI
// =====================================================

$kategori = mysqli_query(
    $koneksi,
    "SELECT *
     FROM kategori
     ORDER BY nama_kategori ASC"
);


// =====================================================
// QUERY PRODUK
// =====================================================

$sql = "
    SELECT
        produk.*,
        kategori.nama_kategori
    FROM produk
    JOIN kategori
        ON produk.kategori_id = kategori.id
    WHERE produk.nama_produk LIKE '%$keyword%'
";


// Filter kategori
if ($kategoriDipilih != 0) {

    $sql .= "
        AND produk.kategori_id = $kategoriDipilih
    ";

}


// Urutkan produk
if ($urutan == 'az') {

    $sql .= " ORDER BY produk.nama_produk ASC";

} elseif ($urutan == 'za') {

    $sql .= " ORDER BY produk.nama_produk DESC";

} else {

    $sql .= " ORDER BY produk.id DESC";

}

$query = mysqli_query($koneksi, $sql);

?>

<?php include 'includes/header.php'; ?>

<?php include 'includes/navbar.php'; ?>


<!-- =====================================================
     SECTION PRODUK
===================================================== -->

<section class="produk py-5">

    <div class="container">


        <!-- =================================================
             JUDUL
        ================================================= -->

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Produk Kami
            </h2>

            <p class="text-muted">

                Temukan berbagai kebutuhan pokok dengan
                kualitas terbaik dan harga terjangkau di
                Brawijaya Mart. Kami menyediakan kebutuhan
                rumah tangga untuk memenuhi kebutuhan
                sehari-hari Anda.

            </p>

        </div>



        <!-- =================================================
             SEARCH
        ================================================= -->

        <div class="row justify-content-center mb-4">

            <div class="col-lg-6">

                <form method="GET">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="bi bi-search"></i>

                        </span>


                        <input
                            type="text"
                            name="cari"
                            class="form-control"
                            placeholder="Cari produk..."
                            value="<?= htmlspecialchars($keyword); ?>"
                        >


                        <button
                            class="btn btn-success"
                            type="submit">

                            Cari

                        </button>

                    </div>

                </form>

            </div>

        </div>



        <!-- =================================================
             FILTER KATEGORI
        ================================================= -->

        <div class="text-center mb-5">


            <!-- Semua -->
            <a
                href="produk.php?cari=<?= urlencode($keyword); ?>"
                class="btn
                <?= $kategoriDipilih == 0
                    ? 'btn-success'
                    : 'btn-outline-success'; ?>
                rounded-pill px-4 me-2 mb-2">

                Semua

            </a>



            <!-- Daftar kategori -->
            <?php while ($k = mysqli_fetch_assoc($kategori)) : ?>

                <a
                    href="produk.php?kategori=<?= $k['id']; ?>&cari=<?= urlencode($keyword); ?>"
                    class="btn
                    <?= $kategoriDipilih == $k['id']
                        ? 'btn-success'
                        : 'btn-outline-success'; ?>
                    rounded-pill px-4 me-2 mb-2">

                    <?= htmlspecialchars($k['nama_kategori']); ?>

                </a>

            <?php endwhile; ?>


        </div>
        <div class="row justify-content-end mb-4">

    <div class="col-md-3">

        <form method="GET">

            <input
                type="hidden"
                name="cari"
                value="<?= htmlspecialchars($keyword); ?>"
            >

            <?php if ($kategoriDipilih != 0) : ?>

                <input
                    type="hidden"
                    name="kategori"
                    value="<?= $kategoriDipilih; ?>"
                >

            <?php endif; ?>


            <label class="form-label fw-semibold">
                Urutkan:
            </label>

            <select
                name="urutan"
                class="form-select"
                onchange="this.form.submit()"
            >

                <option
                    value="terbaru"
                    <?= $urutan == 'terbaru' ? 'selected' : ''; ?>
                >
                    Terbaru
                </option>

                <option
                    value="az"
                    <?= $urutan == 'az' ? 'selected' : ''; ?>
                >
                    Nama A–Z
                </option>

                <option
                    value="za"
                    <?= $urutan == 'za' ? 'selected' : ''; ?>
                >
                    Nama Z–A
                </option>

            </select>

        </form>

    </div>

</div>



        <!-- =================================================
             DAFTAR PRODUK
        ================================================= -->

        <div class="row g-4">


            <?php if (mysqli_num_rows($query) > 0) : ?>


                <?php while ($p = mysqli_fetch_assoc($query)) : ?>


                    <?php

                    // =================================================
                    // AMBIL VARIAN PRODUK
                    // =================================================

                    $produk_id = $p['id'];

                    $query_varian = mysqli_query(
                        $koneksi,
                        "SELECT *
                         FROM varian_produk
                         WHERE produk_id = '$produk_id'
                         ORDER BY harga ASC"
                    );


                    // Simpan semua varian ke array
                    $varian = [];

                    while ($v = mysqli_fetch_assoc($query_varian)) {

                        $varian[] = $v;

                    }

                    ?>


                    <!-- =================================================
                         CARD PRODUK
                    ================================================= -->

                    <div class="col-lg-3 col-md-4 col-sm-6">

                        <div class="product-card">


                            <!-- GAMBAR -->
                            <?php if (!empty($p['gambar'])) : ?>

                                <img
                                    src="uploads/<?= htmlspecialchars($p['gambar']); ?>"
                                    class="img-fluid product-image"
                                    alt="<?= htmlspecialchars($p['nama_produk']); ?>"
                                >

                            <?php else : ?>

                                <div
                                    class="product-image d-flex
                                           align-items-center
                                           justify-content-center
                                           bg-light">

                                    <i class="bi bi-image fs-1 text-muted"></i>

                                </div>

                            <?php endif; ?>



                            <!-- BODY PRODUK -->
                            <div class="product-body">


                                <!-- Nama produk -->
                                <h5>

                                    <?= htmlspecialchars($p['nama_produk']); ?>

                                </h5>



                                <!-- =================================================
                                     JIKA ADA VARIAN
                                ================================================= -->

                                <?php if (count($varian) > 0) : ?>

    <?php
    $hargaTermurah = $varian[0]['harga'];
    $hargaTermahal = $varian[count($varian) - 1]['harga'];
    ?>

    <h4>
        Rp<?= number_format($hargaTermurah, 0, ',', '.'); ?>
        -
        Rp<?= number_format($hargaTermahal, 0, ',', '.'); ?>
    </h4>

    <small class="text-muted">
        <?= count($varian); ?> pilihan tersedia
    </small>

<?php else : ?>


                                    <!-- =================================================
                                         JIKA TIDAK ADA VARIAN
                                    ================================================= -->

                                    <h4>

                                        Rp<?= number_format(
                                            $p['harga'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                    </h4>


                                <?php endif; ?>



                                <!-- =================================================
                                     TOMBOL DETAIL
                                ================================================= -->

                                <a
                                    href="detail_produk.php?id=<?= $p['id']; ?>"
                                    class="btn btn-success w-100 mt-2">

                                    Lihat Detail

                                </a>


                            </div>

                        </div>

                    </div>


                <?php endwhile; ?>


            <?php else : ?>


                <!-- =================================================
                     PRODUK TIDAK DITEMUKAN
                ================================================= -->

                <div class="col-12">

                    <div
                        class="alert alert-warning
                               text-center py-4">


                        <i class="bi bi-search fs-1"></i>


                        <h5 class="mt-3">

                            Produk tidak ditemukan

                        </h5>


                        <p class="mb-0">

                            Maaf, produk yang Anda cari
                            belum tersedia.

                        </p>


                    </div>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>



<!-- =====================================================
     BOOTSTRAP JS
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>


</body>
</html>