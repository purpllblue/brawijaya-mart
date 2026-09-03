<?php

include "config/koneksi.php";

if (!isset($_GET['id'])) {

    header("Location: produk.php");
    exit;

}

$id = (int) $_GET['id'];


// =====================================================
// AMBIL DATA PRODUK
// =====================================================

$query = mysqli_query($koneksi, "

    SELECT
        produk.*,
        kategori.nama_kategori

    FROM produk

    JOIN kategori
        ON produk.kategori_id = kategori.id

    WHERE produk.id = $id

");

$produk = mysqli_fetch_assoc($query);


if (!$produk) {

    echo "Produk tidak ditemukan.";
    exit;

}


// =====================================================
// AMBIL DATA VARIAN
// =====================================================

$query_varian = mysqli_query(
    $koneksi,

    "SELECT *
     FROM varian_produk
     WHERE produk_id = $id
     ORDER BY harga ASC"
);

$varian = [];

while ($v = mysqli_fetch_assoc($query_varian)) {

    $varian[] = $v;

}

?>

<?php include 'includes/header.php'; ?>

<?php include 'includes/navbar.php'; ?>


<section class="py-5">

    <div class="container">

        <div class="row g-5">


            <!-- =================================================
                 GAMBAR PRODUK
            ================================================= -->

            <div class="col-lg-5">

                <?php if (!empty($produk['gambar'])) : ?>

                    <img
                        src="uploads/<?= htmlspecialchars($produk['gambar']); ?>"
                        class="img-fluid detail-image"
                        alt="<?= htmlspecialchars($produk['nama_produk']); ?>">

                <?php endif; ?>

            </div>



            <!-- =================================================
                 INFORMASI PRODUK
            ================================================= -->

            <div class="col-lg-7">


                <span class="badge bg-success mb-3">

                    Produk Berkualitas

                </span>


                <h2 class="fw-bold mb-3">

                    <?= htmlspecialchars($produk['nama_produk']); ?>

                </h2>



                <!-- =================================================
                     HARGA
                ================================================= -->

                <?php if (count($varian) > 0) : ?>

                    <!-- Produk memiliki varian -->

                    <h3
                        class="text-success fw-bold mb-4"
                        id="hargaProduk">

                        Rp<?= number_format(
                            $varian[0]['harga'],
                            0,
                            ',',
                            '.'
                        ); ?>

                    </h3>

                <?php else : ?>

                    <!-- Produk tidak memiliki varian -->

                    <h3
                        class="text-success fw-bold mb-4">

                        Rp<?= number_format(
                            $produk['harga'],
                            0,
                            ',',
                            '.'
                        ); ?>

                    </h3>

                <?php endif; ?>



                <!-- =================================================
                     DESKRIPSI
                ================================================= -->

                <p class="text-muted">

                    <?= nl2br(
                        htmlspecialchars($produk['deskripsi'])
                    ); ?>

                </p>


                <hr>



                <!-- =================================================
                     KATEGORI
                ================================================= -->

                <div class="mb-3">

                    <strong>Kategori :</strong>

                    <?= htmlspecialchars(
                        $produk['nama_kategori']
                    ); ?>

                </div>



                <!-- =================================================
                     VARIAN
                ================================================= -->

                <?php if (count($varian) > 0) : ?>

                    <div class="mb-4">

                        <strong class="d-block mb-2">

                            Pilih Varian :

                        </strong>


                        <div class="d-flex flex-wrap gap-2">


                            <?php foreach ($varian as $index => $v) : ?>

                                <button
                                    type="button"
                                    class="btn
                                    <?= $index == 0
                                        ? 'btn-success'
                                        : 'btn-outline-success'; ?>
                                    varian-btn"

                                    data-nama="<?= htmlspecialchars(
                                        $v['nama_varian'],
                                        ENT_QUOTES
                                    ); ?>"

                                    data-harga="<?= $v['harga']; ?>"

                                    data-stok="<?= htmlspecialchars(
                                        $v['stok'],
                                        ENT_QUOTES
                                    ); ?>"

                                    onclick="pilihVarian(this)">

                                    <?= htmlspecialchars(
                                        $v['nama_varian']
                                    ); ?>

                                </button>

                            <?php endforeach; ?>


                        </div>

                    </div>



                    <!-- =================================================
                         STOK VARIAN
                    ================================================= -->

                    <div class="mb-4">

                        <strong>Stok :</strong>

                        <span id="stokProduk">

                            <?= htmlspecialchars(
                                $varian[0]['stok']
                            ); ?>

                        </span>

                    </div>


                <?php else : ?>


                    <!-- =================================================
                         STOK PRODUK TANPA VARIAN
                    ================================================= -->

                    <div class="mb-4">

                        <strong>Stok :</strong>

                        <?= htmlspecialchars(
                            $produk['stok']
                        ); ?>

                    </div>


                <?php endif; ?>



                <!-- =================================================
                     WHATSAPP
                ================================================= -->

                <?php

                if (count($varian) > 0) {

                    $namaVarianAwal = $varian[0]['nama_varian'];

                    $hargaAwal = $varian[0]['harga'];

                    $pesan = "Halo, saya ingin memesan "
                           . $produk['nama_produk']
                           . " varian "
                           . $namaVarianAwal
                           . " dengan harga Rp"
                           . number_format(
                               $hargaAwal,
                               0,
                               ',',
                               '.'
                           );

                } else {

                    $pesan = "Halo, saya ingin memesan "
                           . $produk['nama_produk']
                           . " dengan harga Rp"
                           . number_format(
                               $produk['harga'],
                               0,
                               ',',
                               '.'
                           );

                }

                ?>

                <a
                    href="https://wa.me/6287871131204?text=<?= urlencode($pesan); ?>"
                    id="tombolWhatsApp"
                    class="btn btn-success btn-lg"
                    target="_blank">

                    <i class="bi bi-whatsapp"></i>

                    Pesan via WhatsApp

                </a>


            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     JAVASCRIPT VARIAN
===================================================== -->

<?php if (count($varian) > 0) : ?>

<script>

function pilihVarian(button) {

    // ============================================
    // Hapus status aktif dari semua tombol
    // ============================================

    document.querySelectorAll('.varian-btn').forEach(function(btn) {

        btn.classList.remove('btn-success');

        btn.classList.add('btn-outline-success');

    });


    // ============================================
    // Aktifkan tombol yang dipilih
    // ============================================

    button.classList.remove('btn-outline-success');

    button.classList.add('btn-success');


    // ============================================
    // Ambil data varian
    // ============================================

    const nama = button.dataset.nama;

    const harga = parseInt(button.dataset.harga);

    const stok = button.dataset.stok;


    // ============================================
    // Ubah harga
    // ============================================

    document.getElementById('hargaProduk').innerText =
        'Rp' + harga.toLocaleString('id-ID');


    // ============================================
    // Ubah stok
    // ============================================

    document.getElementById('stokProduk').innerText = stok;


    // ============================================
    // Ubah link WhatsApp
    // ============================================

    const namaProduk =
        <?= json_encode($produk['nama_produk']); ?>;


    const pesan =
        'Halo, saya ingin memesan '
        + namaProduk
        + ' varian '
        + nama
        + ' dengan harga Rp'
        + harga.toLocaleString('id-ID');


    const linkWhatsApp =
        'https://wa.me/6287871131204?text='
        + encodeURIComponent(pesan);


    document.getElementById('tombolWhatsApp').href =
        linkWhatsApp;

}

</script>

<?php endif; ?>



<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>


</body>
</html>