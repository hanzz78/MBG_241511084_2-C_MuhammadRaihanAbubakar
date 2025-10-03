<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Aplikasi Pemantauan Bahan Baku MBG' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding-top: 56px;
        }
        .main-content {
            margin-left: 250px; /* Lebar sidebar */
        }
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                height: auto;
                padding-top: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <?php if (session()->get('isLoggedIn')): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">MBG - <?= session()->get('role') === 'gudang' ? 'Gudang' : 'Dapur' ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">Dashboard</a>
                    </li>
                    <?php if (session()->get('role') === 'gudang'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= url_is('gudang/bahanbaku*') ? 'active' : '' ?>" href="<?= base_url('gudang/bahanbaku') ?>">Kelola Bahan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= url_is('gudang/permintaan*') ? 'active' : '' ?>" href="<?= base_url('gudang/permintaan') ?>">Permintaan Dapur</a>
                        </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') === 'dapur'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= url_is('dapur/permintaan*') ? 'active' : '' ?>" href="<?= base_url('dapur/permintaan') ?>">Permintaan</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= session()->get('user_name') ?> (<?= ucfirst(session()->get('role')) ?>)
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>" onclick="return confirm('Apakah Anda yakin ingin Logout?')">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        <?= $this->renderSection('content') ?>
    </main>
    <?php else: ?>
        <main>
            <?= $this->renderSection('content') ?>
        </main>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Dialog Konfirmasi untuk aksi Ubah/Hapus/Tambah (Aturan E.2)
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    const action = form.querySelector('button[type="submit"]').textContent.toLowerCase();
                    if (action.includes('tambah') || action.includes('ubah') || action.includes('hapus') || action.includes('simpan') || action.includes('proses')) {
                        if (!confirm('Apakah Anda yakin ingin melanjutkan aksi ' + action + '?')) {
                            event.preventDefault();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>