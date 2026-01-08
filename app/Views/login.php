<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Proqua Clinic</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background-color: #212529;
            color: #ffffff;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 18px;
            text-align: center;
        }

        .login-header h4 {
            margin-bottom: 4px;
            font-weight: 700;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-dark {
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="card login-card">
        <div class="login-header">
            <h4>KLINIK KU</h4>
            <small>Sistem Informasi Manajemen Klinik</small>
        </div>

        <div class="card-body p-4">

            <form action="<?= base_url('/auth/loginProcess') ?>" method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                        autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                        required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark py-2">
                        Masuk Sistem
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (session()->getFlashdata('msg')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: '<?= session()->getFlashdata('msg') ?>',
                confirmButtonText: 'Coba Lagi',
                confirmButtonColor: '#212529'
            });
        </script>
    <?php endif; ?>

</body>

</html>