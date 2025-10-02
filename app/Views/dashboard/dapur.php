<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="text-center text-success mb-4">Dashboard Petugas Dapur</h1>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success text-center">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-lg border-success">
                <div class="card-body">
                    <h5 class="card-title">Selamat datang, <?= esc(session()->get('name')) ?>!</h5>
                    <p class="card-text">Anda bertugas mengajukan permintaan bahan baku yang dibutuhkan dapur.</p>

                    <div class="row mt-4">
                        <!-- C.2.a: Ajukan Permintaan -->
                        <div class="col-lg-6 col-md-6 mb-3">
                            <div class="card text-center bg-light shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <i class="bi bi-cart-plus-fill text-primary fs-2"></i>
                                    <h6 class="card-title mt-2">Ajukan Permintaan Bahan</h6>
                                    <p class="card-text small mb-auto">Cek stok bahan yang tersedia dan ajukan permintaan baru.</p>
                                    <a href="#" class="btn btn-sm btn-primary w-100 mt-3 disabled">Buat Permintaan (TODO)</a>
                                </div>
                            </div>
                        </div>

                        <!-- C.2.b: Status Permintaan -->
                        <div class="col-lg-6 col-md-6 mb-3">
                            <div class="card text-center bg-light shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <i class="bi bi-clock-history text-info fs-2"></i>
                                    <h6 class="card-title mt-2">Lihat Status Permintaan</h6>
                                    <p class="card-text small mb-auto">Pantau apakah permintaan yang diajukan sudah disetujui/ditolak Gudang.</p>
                                    <a href="#" class="btn btn-sm btn-info w-100 mt-3 disabled">Cek Status (TODO)</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <p class="text-center mt-4">
                 <a href="<?= base_url('logout') ?>" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
