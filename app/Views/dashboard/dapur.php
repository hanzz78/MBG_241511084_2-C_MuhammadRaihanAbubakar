<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="alert alert-info" role="alert">
        Selamat datang, **<?= session()->get('user_name') ?>**! Anda login sebagai **Petugas Dapur (Client)**.
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Buat Permintaan Baru</h5>
                    <p class="card-text">Ajukan permintaan bahan baku untuk jadwal masak.</p>
                    <a href="<?= base_url('dapur/permintaan/new') ?>" class="btn btn-primary">Buat Permintaan</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Status Permintaan</h5>
                    <p class="card-text">Lihat status semua permintaan yang telah diajukan.</p>
                    <a href="<?= base_url('dapur/permintaan') ?>" class="btn btn-success">Lihat Status</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>