<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= esc($title) ?></h2>
        <a href="/gudang/permintaan" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Informasi Permintaan</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Pemohon:</strong> <?= esc($permintaan['nama_pemohon']) ?></p>
                    <p><strong>Menu Masakan:</strong> <?= esc($permintaan['menu_makan']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal Masak:</strong> <?= date('d F Y', strtotime($permintaan['tgl_masak'])) ?></p>
                    <p><strong>Jumlah Porsi:</strong> <?= esc($permintaan['jumlah_porsi']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4>Rincian Bahan Baku yang Diminta</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Bahan</th>
                            <th>Jumlah Diminta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detail_bahan)): ?>
                            <?php foreach ($detail_bahan as $item): ?>
                                <tr>
                                    <td><?= esc($item['nama']) ?></td>
                                    <td><?= esc($item['jumlah_diminta']) ?> <?= esc($item['satuan']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                             <tr>
                                <td colspan="2" class="text-center">Tidak ada rincian bahan untuk permintaan ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php if ($permintaan['status'] === 'menunggu'): ?>
    <div class="mt-4 text-end">
        <!-- Fitur untuk memproses permintaan akan ditambahkan di sini -->
        <button class="btn btn-danger">Tolak Permintaan</button>
        <button class="btn btn-success">Setujui & Siapkan Bahan</button>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
