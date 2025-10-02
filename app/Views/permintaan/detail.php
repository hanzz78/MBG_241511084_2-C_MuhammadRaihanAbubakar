<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Permintaan Bahan Baku #<?= esc($permintaan['id']) ?></h5>
        </div>
        <div class="card-body">
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mt-2"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
             <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger mt-2"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Pemohon:</strong> <?= esc($pemohon_name) ?></p>
                    <p><strong>Menu Makanan:</strong> <?= esc($permintaan['menu_makan']) ?></p>
                    <p><strong>Jumlah Porsi:</strong> <?= esc($permintaan['jumlah_porsi']) ?></p>
                    <p><strong>Tanggal Masak:</strong> <?= esc(date('d-m-Y', strtotime($permintaan['tgl_masak']))) ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="h4">
                        Status: 
                        <?php 
                            $status = esc($permintaan['status']);
                            $statusClass = [
                                'menunggu' => 'badge bg-warning text-dark',
                                'disetujui' => 'badge bg-success',
                                'ditolak' => 'badge bg-danger',
                            ];
                        ?>
                        <span class="<?= $statusClass[$status] ?? 'badge bg-secondary' ?>">
                            <?= strtoupper($status) ?>
                        </span>
                    </p>
                    <p>Diajukan pada: <?= esc(date('d-m-Y H:i', strtotime($permintaan['created_at']))) ?></p>
                </div>
            </div>
            
            <hr>
            <h6>Rincian Bahan yang Diminta:</h6>
            
            <?php if (empty($details)): ?>
                <div class="alert alert-danger">Rincian bahan baku tidak ditemukan.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-info">
                            <tr>
                                <th>Bahan Baku</th>
                                <th>Jumlah Diminta</th>
                                <th>Satuan</th>
                                <th class="text-end">Stok Gudang Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details as $detail): ?>
                            <tr>
                                <td><?= esc($detail['bahan_nama']) ?></td>
                                <td><?= esc($detail['jumlah_diminta']) ?></td>
                                <td><?= esc($detail['satuan']) ?></td>
                                <td class="text-end">
                                    <span class="badge bg-secondary">
                                        <?= esc($detail['stok_saat_ini']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <hr>
            
            <!-- Tombol Aksi Persetujuan (Hanya Tampil jika role GUDANG dan status MENUNGGU) -->
            <?php if ($role === 'gudang' && $permintaan['status'] === 'menunggu'): ?>
                <h6 class="mt-4">Proses Permintaan:</h6>
                
                <form action="<?= base_url('permintaan/approve/' . $permintaan['id']) ?>" method="post" class="d-inline me-2" onsubmit="return confirm('Yakin ingin MENYETUJUI permintaan ini? Stok akan dikurangi otomatis.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Setujui Permintaan</button>
                </form>

                <form action="<?= base_url('permintaan/reject/' . $permintaan['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin MENOLAK permintaan ini?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle"></i> Tolak Permintaan</button>
                </form>
            <?php endif; ?>

            <a href="<?= base_url('permintaan/' . ($role === 'gudang' ? 'gudang' : 'list')) ?>" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>

        </div>
    </div>
</div>
<?= $this->endSection() ?>