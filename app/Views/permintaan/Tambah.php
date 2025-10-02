<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-basket"></i> Ajukan Permintaan Bahan Baku (Dapur)</h5>
        </div>
        <div class="card-body">
            <p>
                <a href="<?= base_url('dashboard/dapur') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
            </p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (empty($bahanTersedia)): ?>
                <div class="alert alert-warning">Saat ini, tidak ada bahan baku yang tersedia di gudang untuk diminta.</div>
            <?php else: ?>
                <?= form_open('permintaan/simpan') ?>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="tgl_masak" class="form-label">Tanggal Rencana Memasak</label>
                        <input type="date" name="tgl_masak" id="tgl_masak" class="form-control" value="<?= old('tgl_masak') ?>" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label for="menu_makan" class="form-label">Deskripsi Menu Makanan</label>
                        <input type="text" name="menu_makan" id="menu_makan" class="form-control" placeholder="Contoh: Nasi Goreng Kampung" value="<?= old('menu_makan') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="jumlah_porsi" class="form-label">Jumlah Porsi</label>
                        <input type="number" name="jumlah_porsi" id="jumlah_porsi" class="form-control" min="1" placeholder="Porsi" value="<?= old('jumlah_porsi') ?>" required>
                    </div>
                </div>

                <h6 class="mt-4 mb-3">Pilih Bahan Baku yang Dibutuhkan:</h6>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="permintaanTable">
                        <thead class="table-info">
                            <tr>
                                <th width="5%">Pilih</th>
                                <th>Nama Bahan</th>
                                <th>Stok Tersedia</th>
                                <th>Jumlah Diminta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bahanTersedia as $bahan): ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input check-item" type="checkbox" name="bahan_id[]" value="<?= $bahan['id'] ?>" data-satuan="<?= esc($bahan['satuan']) ?>">
                                    </div>
                                </td>
                                <td><?= esc($bahan['nama']) ?> (Kategori: <?= esc($bahan['kategori']) ?>)</td>
                                <td>
                                    <?= esc($bahan['jumlah']) ?> <?= esc($bahan['satuan']) ?> 
                                    <?php if ($bahan['status'] === 'segera_kadaluarsa'): ?>
                                        <span class="badge bg-warning text-dark ms-2">Segera Kadaluarsa</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <!-- Jumlah Diminta -->
                                        <input type="number" 
                                               name="jumlah_diminta[]" 
                                               class="form-control input-jumlah" 
                                               min="1" 
                                               max="<?= $bahan['jumlah'] ?>"
                                               placeholder="Jumlah" 
                                               disabled>
                                        <span class="input-group-text"><?= esc($bahan['satuan']) ?></span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send"></i> Ajukan Permintaan</button>
                </div>
                <?= form_close() ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('permintaanTable');
    
    // Logika mengaktifkan input jumlah saat checkbox dicentang
    table.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item')) {
            const row = e.target.closest('tr');
            const inputJumlah = row.querySelector('.input-jumlah');
            
            if (e.target.checked) {
                inputJumlah.removeAttribute('disabled');
                inputJumlah.setAttribute('required', 'required');
                inputJumlah.value = ''; // Biarkan kosong agar user input
            } else {
                inputJumlah.setAttribute('disabled', 'disabled');
                inputJumlah.removeAttribute('required');
                inputJumlah.value = ''; 
            }
        }
    });
});
</script>
<?= $this->endSection() ?>