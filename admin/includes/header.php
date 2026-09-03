<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin - Brawijaya Mart</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        /* ==============================
           BODY
        ============================== */

        body {
            overflow-x: hidden;
            margin: 0;
        }


        /* ==============================
           SIDEBAR
        ============================== */

        .sidebar {

            position: fixed;

            top: 0;
            left: 0;

            width: 250px;
            height: 100vh;

            background: #198754;

            z-index: 1050;

            transition: transform 0.3s ease;

            overflow-y: auto;
        }


        /* SIDEBAR TERTUTUP */

        .sidebar.hidden {

            transform: translateX(-100%);

        }


        /* MENU SIDEBAR */

        .sidebar a {

            color: white;

            text-decoration: none;

            display: block;

            padding: 12px 20px;

        }


        .sidebar a:hover {

            background: #157347;

        }


        /* ==============================
           KONTEN DASHBOARD
        ============================== */

        .main-content {

            margin-left: 0;

            width: 100%;

            min-height: 100vh;

            transition:
                margin-left 0.3s ease,
                width 0.3s ease;

        }


        /* SAAT SIDEBAR TERBUKA */

        .main-content.sidebar-open {

            margin-left: 250px;

            width: calc(100% - 250px);

        }


        /* ==============================
           TOMBOL HAMBURGER
        ============================== */

        .menu-toggle {

            position: fixed;

            top: 15px;
            left: 15px;

            z-index: 1100;

            width: 45px;
            height: 45px;

            border: none;

            border-radius: 8px;

            background: #198754;

            color: white;

            font-size: 25px;

            display: flex;

            align-items: center;

            justify-content: center;

            cursor: pointer;

            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.15);

        }


        .menu-toggle:hover {

            background: #157347;

        }


        /* ==============================
           OVERLAY
        ============================== */

        .sidebar-overlay {

            display: none;

            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, 0.4);

            z-index: 1040;

        }


        .sidebar-overlay.show {

            display: block;

        }


        /* ==============================
           MOBILE
        ============================== */

        @media (max-width: 768px) {

            .sidebar {

                width: 250px;

            }

            .main-content.sidebar-open {

                margin-left: 0;

                width: 100%;

            }

        }

    </style>

</head>

<body>