<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Daftar Permintaan Bahan Baku (Persetujuan Gudang)</h5>
        </div>
        <div class="card-body">
            <p>
                <a href="<?= base_url('dashboard/gudang') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
            </p>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mt-2"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
             <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger mt-2"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            
            <?php if (empty($listPermintaan)): ?>
                <div class="alert alert-info mt-3">Tidak ada permintaan bahan baku yang masuk saat ini.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Pemohon</th>
                                <th>Tgl. Masak</th>
                                <th>Menu Makanan</th>
                                <th>Jml. Porsi</th>
                                <th>Status</th>
                                <th>Diajukan Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listPermintaan as $permintaan): ?>
                            <tr>
                                <td><?= esc($permintaan['id']) ?></td>
                                <td><?= esc($permintaan['user_name'] ?? 'N/A') ?></td>
                                <td><?= esc(date('d-m-Y', strtotime($permintaan['tgl_masak']))) ?></td>
                                <td><?= esc($permintaan['menu_makan']) ?></td>
                                <td><?= esc($permintaan['jumlah_porsi']) ?></td>
                                <td>
                                    <?php 
                                        $statusClass = [
                                            'menunggu' => 'badge bg-warning text-dark',
                                            'disetujui' => 'badge bg-success',
                                            'ditolak' => 'badge bg-danger',
                                        ];
                                    ?>
                                    <span class="<?= $statusClass[$permintaan['status']] ?? 'badge bg-secondary' ?>">
                                        <?= strtoupper(esc($permintaan['status'])) ?>
                                    </span>
                                </td>
                                <td><?= esc(date('d-m-Y H:i', strtotime($permintaan['created_at']))) ?></td>
                                <td>
                                    <a href="<?= base_url('permintaan/detail/' . $permintaan['id']) ?>" class="btn btn-primary btn-sm" title="Lihat Detail & Proses">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>d