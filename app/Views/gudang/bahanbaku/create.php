<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <h2>Tambah Bahan Baku Baru</h2>
    <p>Silakan isi form di bawah ini untuk menambahkan bahan baku baru.</p>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <strong>Gagal menyimpan data!</strong>
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= site_url('gudang/bahanbaku') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Bahan</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama') ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <input type="text" class="form-control" id="kategori" name="kategori" value="<?= old('kategori') ?>">
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= old('jumlah') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="satuan" class="form-label">Satuan (e.g., Kg, Liter, Pcs)</label>
                <input type="text" class="form-control" id="satuan" name="satuan" value="<?= old('satuan') ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= old('tanggal_masuk') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                <input type="date" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="<?= old('tanggal_kadaluarsa') ?>" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Data</button>
        <a href="<?= site_url('gudang/bahanbaku') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>