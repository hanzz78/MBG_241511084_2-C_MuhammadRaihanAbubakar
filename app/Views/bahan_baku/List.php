<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Daftar Bahan Baku di Gudang (Petugas Gudang)</h5>
        </div>
        <div class="card-body">
            <p>
                <a href="<?= base_url('dashboard/gudang') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                <a href="<?= base_url('bahanbaku/tambah') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah Bahan Baru</a>
            </p>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mt-2"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            
            <?php if (empty($listBahan)): ?>
                <div class="alert alert-info mt-3">Tidak ada data bahan baku yang tersedia.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Bahan</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Tgl. Masuk</th>
                                <th>Tgl. Kadaluarsa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($listBahan as $bahan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($bahan['nama']) ?></td>
                                <td><?= esc($bahan['kategori']) ?></td>
                                <td><?= esc($bahan['jumlah']) ?> <?= esc($bahan['satuan']) ?></td>
                                <td><?= esc(date('d-m-Y', strtotime($bahan['tanggal_masuk']))) ?></td>
                                <td><?= esc(date('d-m-Y', strtotime($bahan['tanggal_kadaluarsa']))) ?></td>
                                <td>
                                    <?php 
                                        $statusClass = [
                                            'tersedia' => 'badge bg-success',
                                            'segera_kadaluarsa' => 'badge bg-warning text-dark',
                                            'kadaluarsa' => 'badge bg-danger',
                                            'habis' => 'badge bg-secondary',
                                        ];
                                    ?>
                                    <span class="<?= $statusClass[$bahan['status']] ?? 'badge bg-info' ?>">
                                        <?= strtoupper(str_replace('_', ' ', $bahan['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Aksi Edit/Delete (akan diimplementasikan nanti di C.1.c dan C.1.d) -->
                                    <a href="#" class="btn btn-info btn-sm disabled" title="Edit Stok"><i class="bi bi-pencil"></i></a>
                                    <a href="#" class="btn btn-danger btn-sm disabled" title="Hapus"><i class="bi bi-trash"></i></a>
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
<?= $this->endSection() ?>