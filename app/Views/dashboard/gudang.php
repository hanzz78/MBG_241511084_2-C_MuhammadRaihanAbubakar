<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="alert alert-info" role="alert">
        Selamat datang, **<?= session()->get('user_name') ?>**! Anda login sebagai **Petugas Gudang (Admin)**.
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Kelola Bahan Baku</h5>
                    <p class="card-text">Input, lihat status kedaluwarsa, dan kelola stok.</p>
                    <a href="<?= base_url('gudang/bahanbaku') ?>" class="btn btn-primary">Akses Menu</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Proses Permintaan Dapur</h5>
                    <p class="card-text">Lihat dan setujui/tolak permintaan bahan baku dari dapur.</p>
                    <a href="<?= base_url('gudang/permintaan') ?>" class="btn btn-success">Akses Menu</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>