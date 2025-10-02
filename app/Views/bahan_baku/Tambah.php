<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Tambah Bahan Baku Baru</h5>
                </div>
                <div class="card-body">

                    <!-- Tampilkan Error Validasi -->
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Form Input Bahan Baku -->
                    <form action="<?= base_url('bahanbaku/simpan') ?>" method="post" onsubmit="return confirm('Yakin ingin menyimpan bahan baku ini?');">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Bahan Baku</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" id="kategori" name="kategori" value="<?= old('kategori') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jumlah" class="form-label">Jumlah (Stok Awal)</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= old('jumlah') ?? 1 ?>" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="satuan" class="form-label">Satuan (Contoh: kg, liter, ikat)</label>
                                <input type="text" class="form-control" id="satuan" name="satuan" value="<?= old('satuan') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= old('tanggal_masuk') ?? date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                                <input type="date" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="<?= old('tanggal_kadaluarsa') ?>" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-3"> Simpan Bahan Baku</button>
                        <a href="<?= base_url('dashboard/gudang') ?>" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
