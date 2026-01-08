<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KLINIK KU</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: white;
            width: 250px;
            flex-shrink: 0;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border-bottom: 1px solid #343a40;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #495057;
            color: white;
            padding-left: 25px;
        }

        .sidebar .active {
            background: #0d6efd;
            color: white;
        }

        .content-wrapper {
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <div class="sidebar d-flex flex-column p-3">
            <h4 class="mb-4 text-white text-center fw-bold">KLINIK KU</h4>

            <?php
            $role = session()->get('role');
            ?>

            <a href="/dashboard">Dashboard</a>

            <?php if (in_array($role, ['superadmin', 'admisi'])): ?>
                <a href="/pasien">Data Pasien</a>
            <?php endif; ?>

            <?php if (in_array($role, ['superadmin', 'admisi', 'perawat'])): ?>
                <a href="/pendaftaran">Pendaftaran</a>
                <a href="/kunjungan">Kunjungan</a>
            <?php endif; ?>

            <?php if (in_array($role, ['superadmin', 'perawat'])): ?>
                <a href="/asesmen">Asesmen Medis</a>
            <?php endif; ?>

            <div class="mt-auto">
                <hr class="text-white">
                <a href="/logout" class="text-danger fw-bold">Logout</a>
            </div>
        </div>

        <div class="content-wrapper">
            <nav class="navbar navbar-light bg-white shadow-sm px-4 py-3 mb-4">
                <span class="navbar-brand mb-0 h1 fw-bold">Sistem Informasi Manajemen Klinik</span>
                <span class="text-muted">Halo, <b><?= session()->get('nama') ?></b>
                    (<?= session()->get('role') ?>)</span>
            </nav>

            <div class="container-fluid px-4">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>