<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Session Info:</strong>
            <pre><?= print_r($session_data, true) ?></pre>
        </div>
        <h3>Dashboard Utama</h3>
        <p class="lead">Selamat datang di aplikasi Sistem Manajemen Klinik.</p>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-info">
                    <h5>User Login</h5>
                    <p>
                        <?= session()->get('nama') ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <h5>Role Akses</h5>
                    <p>
                        <?= session()->get('role') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>