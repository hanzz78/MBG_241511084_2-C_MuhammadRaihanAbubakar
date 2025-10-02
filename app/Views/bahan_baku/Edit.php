<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Bahan Baku: <?= esc($bahan['nama']) ?></h5>
        </div>
        <div class="card-body">
            <p>
                <a href="<?= base_url('bahanbaku') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Stok</a>
            </p>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form Edit. Menggunakan method POST dan disembunyikan sebagai PUT/PATCH untuk update -->
            <?= form_open('bahanbaku/update/' . $bahan['id']) ?>
            
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT"> <!-- Method Spoofing untuk UPDATE -->

                <div class="row">
                    <!-- Nama & Kategori -->
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama Bahan Baku</label>
                        <!-- Nama dan Kategori dikunci, karena biasanya tidak diubah -->
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama', $bahan['nama']) ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" name="kategori" id="kategori" class="form-control" value="<?= old('kategori', $bahan['kategori']) ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Jumlah & Satuan -->
                    <div class="col-md-6 mb-3">
                        <label for="jumlah" class="form-label">Stok Tersedia (Jumlah)</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?= old('jumlah', $bahan['jumlah']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control" value="<?= old('satuan', $bahan['satuan']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Tanggal Masuk & Kadaluarsa -->
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk Gudang</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="<?= old('tanggal_masuk', $bahan['tanggal_masuk']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" class="form-control" value="<?= old('tanggal_kadaluarsa', $bahan['tanggal_kadaluarsa']) ?>" required>
                        <div class="form-text text-danger">Pastikan tanggal ini tidak mendahului hari ini.</div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-info btn-lg text-white"><i class="bi bi-save"></i> Simpan Perubahan</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>