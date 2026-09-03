<?php

include "config/koneksi.php";

$queryProduk = mysqli_query($koneksi, "

    SELECT *
    FROM produk
    ORDER BY id DESC
    LIMIT 4

");

?>

<?php include 'includes/header.php'; ?>

<?php include 'includes/navbar.php'; ?>

<?php include 'includes/hero.php'; ?>


<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Produk Terbaru
            </h2>

            <p class="text-muted">
                Beberapa produk terbaru yang tersedia di Brawijaya Mart.
            </p>

        </div>


        <div class="row g-4">


            <?php while ($p = mysqli_fetch_assoc($queryProduk)) : ?>


                <?php

                // Ambil varian produk
                $produk_id = $p['id'];

                $queryVarian = mysqli_query(
                    $koneksi,

                    "SELECT *
                     FROM varian_produk
                     WHERE produk_id = '$produk_id'
                     ORDER BY harga ASC"
                );

                $varian = [];

                while ($v = mysqli_fetch_assoc($queryVarian)) {

                    $varian[] = $v;

                }

                ?>


                <div class="col-lg-3 col-md-6">

                    <div class="product-card">


                        <!-- Gambar -->

                        <?php if (!empty($p['gambar'])) : ?>

                            <img
                                src="uploads/<?= htmlspecialchars($p['gambar']); ?>"
                                class="img-fluid product-image"
                                alt="<?= htmlspecialchars($p['nama_produk']); ?>">

                        <?php else : ?>

                            <div class="product-image bg-light d-flex align-items-center justify-content-center">

                                Tidak ada gambar

                            </div>

                        <?php endif; ?>


                        <div class="product-body">


                            <!-- Nama Produk -->

                            <h5>

                                <?= htmlspecialchars(
                                    $p['nama_produk']
                                ); ?>

                            </h5>



                            <!-- Harga -->

                            <h4>

                                <?php if (count($varian) > 0) : ?>


                                    <?php

                                    $hargaTermurah =
                                        $varian[0]['harga'];

                                    $hargaTermahal =
                                        $varian[count($varian) - 1]['harga'];

                                    ?>


                                    Rp<?= number_format(
                                        $hargaTermurah,
                                        0,
                                        ',',
                                        '.'
                                    ); ?>

                                    -

                                    Rp<?= number_format(
                                        $hargaTermahal,
                                        0,
                                        ',',
                                        '.'
                                    ); ?>


                                <?php else : ?>


                                    Rp<?= number_format(
                                        $p['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>


                                <?php endif; ?>


                            </h4>



                            <!-- Tombol Detail -->

                            <a
                                href="detail_produk.php?id=<?= $p['id']; ?>"
                                class="btn btn-success w-100">

                                Lihat Detail

                            </a>


                        </div>

                    </div>

                </div>


            <?php endwhile; ?>


        </div>


        <div class="text-center mt-5">

            <a
                href="produk.php"
                class="btn btn-outline-success">

                Lihat Semua Produk

            </a>

        </div>


    </div>

</section>


<?php include 'includes/features.php'; ?>

<?php include 'includes/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>