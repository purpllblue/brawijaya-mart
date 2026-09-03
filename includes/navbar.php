<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">

        <a class="navbar-brand brand-logo" href="index.php">
            <i class="bi bi-shop"></i>
            <span>Brawijaya Mart</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'index.php') ? 'active' : ''; ?>"
                       href="index.php">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'produk.php') ? 'active' : ''; ?>"
                       href="produk.php">
                        Produk
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>