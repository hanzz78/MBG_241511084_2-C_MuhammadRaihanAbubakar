<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4><?= esc($title) ?></h4>
                </div>
                <div class="card-body">
                    
                    <?php if (session()->has('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif ?>

                    <form action="/dapur/permintaan/create" method="post">
                        <?= csrf_field() ?>

                        <h5>Informasi Utama</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tgl_masak" class="form-label">Tanggal Rencana Memasak</label>
                                <input type="date" class="form-control" id="tgl_masak" name="tgl_masak" value="<?= old('tgl_masak') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_porsi" class="form-label">Jumlah Porsi</label>
                                <input type="number" class="form-control" id="jumlah_porsi" name="jumlah_porsi" value="<?= old('jumlah_porsi') ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="menu_makan" class="form-label">Deskripsi Menu Masakan</label>
                            <textarea class="form-control" id="menu_makan" name="menu_makan" rows="2" required><?= old('menu_makan') ?></textarea>
                        </div>

                        <hr>

                        <h5>Detail Bahan Baku yang Dibutuhkan</h5>
                        <div id="bahan-container">
                            <!-- Baris pertama untuk item bahan baku -->
                            <div class="row bahan-item mb-2 align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Bahan Baku</label>
                                    <select name="bahan_id[]" class="form-select" required>
                                        <option value="">Pilih Bahan...</option>
                                        <?php foreach ($bahan_baku as $bahan): ?>
                                            <option value="<?= $bahan['id'] ?>"><?= esc($bahan['nama']) ?> (Stok: <?= $bahan['jumlah'] ?> <?= $bahan['satuan'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah_diminta[]" class="form-control" required placeholder="Jumlah yang diminta">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 remove-bahan">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-bahan" class="btn btn-outline-secondary mt-2">Tambah Bahan Lain</button>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="/dapur/permintaan" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Ajukan Permintaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('bahan-container');
    const addButton = document.getElementById('add-bahan');
    
    const bahanOptions = `
        <option value="">Pilih Bahan...</option>
        <?php foreach ($bahan_baku as $bahan): ?>
            <option value="<?= $bahan['id'] ?>"><?= esc($bahan['nama']) ?> (Stok: <?= $bahan['jumlah'] ?> <?= $bahan['satuan'] ?>)</option>
        <?php endforeach; ?>
    `;

    const newRowTemplate = `
        <div class="row bahan-item mb-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Bahan Baku</label>
                <select name="bahan_id[]" class="form-select" required>
                    ${bahanOptions}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jumlah_diminta[]" class="form-control" required placeholder="Jumlah yang diminta">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-bahan">Hapus</button>
            </div>
        </div>
    `;

    addButton.addEventListener('click', function () {
        const newRow = document.createElement('div');
        newRow.innerHTML = newRowTemplate;
        container.appendChild(newRow.firstElementChild);
    });

    container.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-bahan')) {
            if (container.querySelectorAll('.bahan-item').length > 1) {
                e.target.closest('.bahan-item').remove();
            } else {
                alert('Minimal harus ada satu bahan yang diminta.');
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
