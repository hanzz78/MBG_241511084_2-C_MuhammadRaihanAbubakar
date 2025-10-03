<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><?= esc($title) ?></h4>
                </div>
                <div class="card-body">
                    
                    <?php if (session()->has('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="/gudang/bahanbaku/<?= esc($bahan['id'], 'url') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Bahan</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama', $bahan['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" id="kategori" name="kategori" value="<?= old('kategori', $bahan['kategori']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= old('jumlah', $bahan['jumlah']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" name="satuan" value="<?= old('satuan', $bahan['satuan']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= old('tanggal_masuk', $bahan['tanggal_masuk']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="<?= old('tanggal_kadaluarsa', $bahan['tanggal_kadaluarsa']) ?>" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="/gudang/bahanbaku" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>