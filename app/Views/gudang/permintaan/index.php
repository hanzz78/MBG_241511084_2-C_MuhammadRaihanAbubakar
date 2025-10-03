<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= esc($title) ?></h2>
    </div>
    
    <p>Berikut adalah daftar permintaan bahan baku yang diajukan oleh petugas dapur.</p>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Pemohon</th>
                    <th>Menu Masakan</th>
                    <th>Jml Porsi</th>
                    <th>Tgl Masak</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($permintaan)): ?>
                    <?php foreach ($permintaan as $item): ?>
                        <tr>
                            <td><?= esc($item['id']) ?></td>
                            <td><?= esc($item['nama_pemohon']) ?></td>
                            <td><?= esc($item['menu_makan']) ?></td>
                            <td><?= esc($item['jumlah_porsi']) ?></td>
                            <td><?= date('d M Y', strtotime($item['tgl_masak'])) ?></td>
                            <td>
                                <?php
                                $status = esc($item['status']);
                                $badgeClass = 'secondary';
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
                            <td>
                                <?php if($item['status'] === 'menunggu'): ?>
                                    <button class="btn btn-sm btn-success">Setujui</button>
                                    <button class="btn btn-sm btn-danger">Tolak</button>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada permintaan bahan baku.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>  