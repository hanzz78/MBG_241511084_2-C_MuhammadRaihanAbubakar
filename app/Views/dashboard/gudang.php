<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="alert alert-success text-center shadow-sm">
        <h4 class="alert-heading"><i class="bi bi-box-seam-fill"></i> Selamat Datang, Petugas Gudang!</h4>
        <p class="mb-0">Akses Penuh untuk Manajemen Stok Bahan Baku.</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-info mt-3"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="row mt-4">
        <!-- Card 1: Pengelolaan Stok -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title text-success"><i class="bi bi-archive-fill"></i> Kelola Stok Bahan Baku</h5>
                    <p class="card-text">Input bahan masuk, pantau status kedaluwarsa, dan kelola jumlah stok.</p>
                    <a href="<?= base_url('bahanbaku') ?>" class="btn btn-success mt-2"><i class="bi bi-list-check"></i> Lihat & Monitor Stok</a>
                    <a href="<?= base_url('bahanbaku/tambah') ?>" class="btn btn-outline-success mt-2 ms-2"><i class="bi bi-plus-circle"></i> Tambah Bahan Baru</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Kelola Permintaan Dapur -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg h-100 border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="bi bi-box-arrow-in-right"></i> Kelola Permintaan Bahan</h5>
                    <p class="card-text">Setujui atau tolak permintaan bahan dari Petugas Dapur. Fitur ini akan dikerjakan selanjutnya.</p>
                    <a href="#" class="btn btn-primary mt-2 disabled"><i class="bi bi-hourglass-split"></i> Lihat Permintaan (WIP)</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tombol Logout Tambahan di Konten Utama Dashboard -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-lg shadow-sm">
                <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
