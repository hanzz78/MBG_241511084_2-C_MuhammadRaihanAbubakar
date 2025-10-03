<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Bahan Baku</h2>
        <a href="<?= site_url('/gudang/bahanbaku/new') ?>" class="btn btn-primary">Tambah Bahan Baku Baru</a>
    </div>
    
    <p>Berikut adalah daftar semua bahan baku yang tersedia di gudang.</p>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Bahan</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Tgl Masuk</th>
                    <th>Tgl Kadaluarsa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bahan_baku)): ?>
                    <?php foreach ($bahan_baku as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= esc($item['nama']) ?></td>
                        <td><?= esc($item['kategori']) ?></td>
                        <td><?= $item['jumlah'] ?></td>
                        <td><?= esc($item['satuan']) ?></td>
                        <td><?= date('d M Y', strtotime($item['tanggal_masuk'])) ?></td>
                        <td><?= date('d M Y', strtotime($item['tanggal_kadaluarsa'])) ?></td>
                        <td>
                            <span class="badge 
                                <?php 
                                    switch($item['status']) {
                                        case 'tersedia': echo 'bg-success'; break;
                                        case 'segera_kadaluarsa': echo 'bg-warning text-dark'; break;
                                        case 'kadaluarsa': echo 'bg-danger'; break;
                                        case 'habis': echo 'bg-secondary'; break;
                                    }
                                ?>">
                                <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= site_url('gudang/bahanbaku/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            
                            <form action="<?= site_url('gudang/bahanbaku/delete/' . $item['id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Apakah Anda yakin? Hanya bahan kadaluarsa yang bisa dihapus.')"
                                        <?= $item['status'] !== 'kadaluarsa' ? 'disabled' : '' ?>>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data bahan baku.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>