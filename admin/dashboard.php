<?php

session_start();

if (!isset($_SESSION['login'])) {

    header("Location: login.php");

    exit;

}

include "../config/koneksi.php";


// =====================================================
// TOTAL KATEGORI
// =====================================================

$resultKategori = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM kategori"
);

$totalKategori =
    mysqli_fetch_assoc($resultKategori)['total'];


// =====================================================
// TOTAL PRODUK
// =====================================================

$resultProduk = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM produk"
);

$totalProduk =
    mysqli_fetch_assoc($resultProduk)['total'];


// =====================================================
// PRODUK TERBARU
// =====================================================

$queryTerbaru = mysqli_query($koneksi, "

    SELECT
        produk.*,
        kategori.nama_kategori

    FROM produk

    JOIN kategori
        ON produk.kategori_id = kategori.id

    ORDER BY produk.id DESC

    LIMIT 5

");


include "includes/header.php";

?>


<div class="container-fluid">


        <!-- =================================================
             SIDEBAR
        ================================================= -->

        <?php include "includes/sidebar.php"; ?>


        <!-- =================================================
             CONTENT
        ================================================= -->

        <div class="main-content p-4">


            <!-- =================================================
                 JUDUL
            ================================================= -->

            <h2>
                Dashboard
            </h2>

            <hr>


            <!-- =================================================
                 TOTAL DATA
            ================================================= -->

            <div class="row">


                <!-- TOTAL KATEGORI -->

                <div class="col-md-4 mb-3">

                    <div class="card shadow border-0">

                        <div class="card-body text-center">

                            <h5 class="text-success">

                                Total Kategori

                            </h5>


                            <h2 class="fw-bold">

                                <?= $totalKategori; ?>

                            </h2>

                        </div>

                    </div>

                </div>


                <!-- TOTAL PRODUK -->

                <div class="col-md-4 mb-3">

                    <div class="card shadow border-0">

                        <div class="card-body text-center">

                            <h5 class="text-primary">

                                Total Produk

                            </h5>


                            <h2 class="fw-bold">

                                <?= $totalProduk; ?>

                            </h2>

                        </div>

                    </div>

                </div>


            </div>


            <!-- =================================================
                 WELCOME
            ================================================= -->

            <div class="card shadow border-0 mt-4">

                <div class="card-body">

                    <h4>

                        Selamat Datang,

                        <?= htmlspecialchars(
                            $_SESSION['nama']
                        ); ?>!

                    </h4>


                    <p class="text-muted mb-0">

                        Selamat datang di Dashboard
                        Brawijaya Mart.

                        Gunakan menu untuk mengelola kategori dan produk
                        yang ditampilkan pada website.

                    </p>

                </div>

            </div>


            <!-- =================================================
                 PREVIEW WEBSITE
            ================================================= -->

            <div class="card shadow border-0 mt-4">


                <!-- HEADER -->

                <div class="card-header bg-success text-white">

                    <h5 class="mb-0">

                        Preview Website

                    </h5>

                </div>


                <!-- BODY -->

                <div class="card-body">


                    <!-- PILIHAN TAMPILAN -->

                    <div class="text-center mb-4">

                        <button
                            type="button"
                            id="btnPC"
                            class="btn btn-success me-2"
                            onclick="ubahPreview('pc')">

                            PC

                        </button>


                        <button
                            type="button"
                            id="btnHP"
                            class="btn btn-outline-success"
                            onclick="ubahPreview('hp')">

                            Mobile

                        </button>

                    </div>


                    <!-- AREA PREVIEW -->

                    <div class="d-flex justify-content-center">

                        <div
                            id="previewWebsite"
                            style="
                                width: 100%;
                                height: 600px;
                                border: 1px solid #ddd;
                                border-radius: 10px;
                                overflow: hidden;
                                transition: all 0.3s ease;
                                background: #f8f9fa;
                            ">


                            <iframe
                                src="../index.php"
                                id="iframeWebsite"
                                style="
                                    width: 100%;
                                    height: 100%;
                                    border: none;
                                ">
                            </iframe>


                        </div>

                    </div>


                </div>

            </div>


            <!-- =================================================
                 PRODUK TERBARU
            ================================================= -->

            <div class="card shadow border-0 mt-4">


                <div class="card-header bg-success text-white">

                    <h5 class="mb-0">

                        Produk Terbaru

                    </h5>

                </div>


                <div class="card-body">


                    <div class="table-responsive">


                        <table class="table table-hover align-middle">


                            <thead>

                                <tr>

                                    <th>No</th>

                                    <th>Produk</th>

                                    <th>Kategori</th>

                                    <th>Harga</th>

                                    <th>Stok</th>

                                </tr>

                            </thead>


                            <tbody>


                            <?php

                            $no = 1;


                            while (
                                $p =
                                mysqli_fetch_assoc(
                                    $queryTerbaru
                                )
                            ) :


                                // =========================================
                                // AMBIL VARIAN PRODUK
                                // =========================================

                                $produk_id =
                                    $p['id'];


                                $queryVarian =
                                    mysqli_query(
                                        $koneksi,
                                        "SELECT *
                                         FROM varian_produk
                                         WHERE produk_id = '$produk_id'
                                         ORDER BY harga ASC"
                                    );


                                $varian = [];


                                while (
                                    $v =
                                    mysqli_fetch_assoc(
                                        $queryVarian
                                    )
                                ) {

                                    $varian[] = $v;

                                }

                            ?>


                                <tr>


                                    <!-- NO -->

                                    <td>

                                        <?= $no++; ?>

                                    </td>


                                    <!-- PRODUK -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $p['nama_produk']
                                        ); ?>

                                    </td>


                                    <!-- KATEGORI -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $p['nama_kategori']
                                        ); ?>

                                    </td>


                                    <!-- HARGA -->

                                    <td>


                                        <?php
                                        if (count($varian) > 0) :
                                        ?>


                                            <?php

                                            $hargaTermurah =
                                                $varian[0]['harga'];


                                            $hargaTermahal =
                                                $varian[
                                                    count($varian) - 1
                                                ]['harga'];

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


                                        <?php
                                        else :
                                        ?>


                                            Rp<?= number_format(
                                                $p['harga'],
                                                0,
                                                ',',
                                                '.'
                                            ); ?>


                                        <?php
                                        endif;
                                        ?>


                                    </td>


                                    <!-- STOK -->

                                    <td>


                                        <?php
                                        if (count($varian) > 0) :
                                        ?>


                                            <?php
                                            foreach (
                                                $varian as $v
                                            ) :
                                            ?>


                                                <div class="mb-1">

                                                    <?= htmlspecialchars(
                                                        $v['nama_varian']
                                                    ); ?>:

                                                    <?= htmlspecialchars(
                                                        $v['stok']
                                                    ); ?>

                                                </div>


                                            <?php
                                            endforeach;
                                            ?>


                                        <?php
                                        else :
                                        ?>


                                            <?= htmlspecialchars(
                                                $p['stok']
                                            ); ?>


                                        <?php
                                        endif;
                                        ?>


                                    </td>


                                </tr>


                            <?php
                            endwhile;
                            ?>


                            </tbody>

                        </table>


                    </div>

                </div>

            </div>


        </div>
        <!-- END CONTENT -->

</div>
<!-- END CONTAINER -->


<!-- =====================================================
     JAVASCRIPT PREVIEW PC / HP
===================================================== -->

<script>

function ubahPreview(tampilan) {


    const preview =
        document.getElementById(
            "previewWebsite"
        );


    const tombolPC =
        document.getElementById(
            "btnPC"
        );


    const tombolHP =
        document.getElementById(
            "btnHP"
        );


    if (tampilan === "hp") {


        // Ukuran layar HP

        preview.style.width =
            "375px";


        preview.style.height =
            "667px";


        // HP aktif

        tombolHP.classList.remove(
            "btn-outline-success"
        );

        tombolHP.classList.add(
            "btn-success"
        );


        // PC tidak aktif

        tombolPC.classList.remove(
            "btn-success"
        );

        tombolPC.classList.add(
            "btn-outline-success"
        );


    } else {


        // Ukuran PC

        preview.style.width =
            "100%";


        preview.style.height =
            "600px";


        // PC aktif

        tombolPC.classList.remove(
            "btn-outline-success"
        );

        tombolPC.classList.add(
            "btn-success"
        );


        // HP tidak aktif

        tombolHP.classList.remove(
            "btn-success"
        );

        tombolHP.classList.add(
            "btn-outline-success"
        );

    }

}

</script>


<!-- =====================================================
     BOOTSTRAP JAVASCRIPT
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>