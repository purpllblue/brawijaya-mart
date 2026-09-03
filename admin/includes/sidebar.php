<!-- TOMBOL MENU -->
<button
    type="button"
    class="menu-toggle"
    id="menuToggle"
    onclick="toggleSidebar()"
    aria-label="Buka menu"
>
    ☰
</button>

<!-- OVERLAY -->
<div
    class="sidebar-overlay"
    id="sidebarOverlay"
    onclick="toggleSidebar()"
></div>

<!-- SIDEBAR -->
<div class="sidebar hidden" id="sidebar">

    <h4 class="text-white text-center py-4 mb-0">
        Admin
    </h4>

    <a href="/katalog/admin/dashboard.php">
        Dashboard
    </a>

    <a href="/katalog/admin/kategori/index.php">
        Kategori
    </a>

    <a href="/katalog/admin/produk/index.php">
        Produk
    </a>

    <a
        href="#"
        data-bs-toggle="modal"
        data-bs-target="#logoutModal"
    >
        Logout
    </a>

</div>


<!-- MODAL LOGOUT -->
<div
    class="modal fade"
    id="logoutModal"
    tabindex="-1"
    aria-labelledby="logoutModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">
                    Konfirmasi Logout
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>

            <div class="modal-body">

                <p>
                    Yakin ingin keluar dari Dashboard?
                </p>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="confirmLogout"
                    >

                    <label
                        class="form-check-label"
                        for="confirmLogout"
                    >
                        Saya yakin ingin logout
                    </label>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <a
                    href="/katalog/admin/logout.php"
                    class="btn btn-danger disabled"
                    id="btnLogout"
                >
                    Logout
                </a>

            </div>

        </div>
    </div>
</div>


<script>

function toggleSidebar() {

    const sidebar =
        document.getElementById("sidebar");

    const overlay =
        document.getElementById("sidebarOverlay");

    const menuToggle =
        document.getElementById("menuToggle");

    const mainContent =
        document.querySelector(".main-content");


    if (sidebar.classList.contains("hidden")) {

        // BUKA SIDEBAR
        sidebar.classList.remove("hidden");

        overlay.classList.add("show");

        mainContent.classList.remove("full");

        menuToggle.innerHTML = "✕";

    } else {

        // TUTUP SIDEBAR
        sidebar.classList.add("hidden");

        overlay.classList.remove("show");

        mainContent.classList.add("full");

        menuToggle.innerHTML = "☰";
    }
}


// CHECKBOX LOGOUT

document
    .getElementById("confirmLogout")
    .addEventListener("change", function () {

        const btnLogout =
            document.getElementById("btnLogout");

        if (this.checked) {

            btnLogout.classList.remove("disabled");

        } else {

            btnLogout.classList.add("disabled");

        }

    });


// RESET CHECKBOX

document
    .getElementById("logoutModal")
    .addEventListener("hidden.bs.modal", function () {

        document.getElementById("confirmLogout").checked = false;

        document
            .getElementById("btnLogout")
            .classList.add("disabled");

    });

</script>