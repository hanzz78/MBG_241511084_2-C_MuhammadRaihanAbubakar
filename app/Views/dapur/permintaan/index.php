<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= esc($title) ?></h2>
        <a href="/dapur/permintaan/new" class="btn btn-primary">Buat Permintaan Baru</a>
    </div>

    <p>Berikut adalah riwayat permintaan bahan baku yang telah Anda ajukan.</p>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Permintaan</th>
                    <th>Menu Masakan / Bahan Baku</th>
                    <th>Jumlah Porsi</th>
                    <th>Tanggal Masak</th>
                    <th>Tanggal Diajukan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($permintaan)): ?>
                    <?php foreach ($permintaan as $item): ?>
                        <tr>
                            <td><?= esc($item['id']) ?></td>
                            <td><?= esc($item['menu_makan']) ?></td>
                            <td><?= esc($item['jumlah_porsi']) ?></td>
                            <td><?= date('d M Y', strtotime($item['tgl_masak'])) ?></td>
                            <td><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></td>
                            <td>
                                <?php
                                $status = esc($item['status']);
                                $badgeClass = 'secondary'; // Default
                                if ($status === 'disetujui') {
                                    $badgeClass = 'success';
                                } elseif ($status === 'menunggu') {
                                    $badgeClass = 'warning';
                                } elseif ($status === 'ditolak') {
                                    $badgeClass = 'danger';
                                }
                                ?>
                                <span class="badge bg-<?= $badgeClass; ?>"><?= ucfirst($status); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Anda belum pernah mengajukan permintaan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>