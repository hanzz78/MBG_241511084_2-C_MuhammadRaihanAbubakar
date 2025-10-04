<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= esc($title) ?></h2>
        <a href="/gudang/permintaan" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>

    <!-- Placeholder untuk pesan dari AJAX -->
    <div id="response-message" style="display: none;"></div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Informasi Permintaan</h4>
                <div id="status-badge-container">
                    <?php
                        $status = esc($permintaan['status']);
                        $badgeClass = 'secondary';
                        if ($status === 'disetujui') $badgeClass = 'success';
                        elseif ($status === 'menunggu') $badgeClass = 'warning';
                        elseif ($status === 'ditolak') $badgeClass = 'danger';
                    ?>
                    <span class="badge bg-<?= $badgeClass; ?> fs-6"><?= ucfirst($status); ?></span>
                </div>
            </div>
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
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama Bahan</th>
                        <th>Jumlah Diminta</th>
                        <th>Stok Saat Ini</th>
                        <th>Status Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detail_bahan as $item): ?>
                        <tr>
                            <td><?= esc($item['nama']) ?></td>
                            <td><?= esc($item['jumlah_diminta']) ?> <?= esc($item['satuan']) ?></td>
                            <td><?= esc($item['stok_saat_ini']) ?> <?= esc($item['satuan']) ?></td>
                            <td>
                                <?php if ($item['stok_saat_ini'] >= $item['jumlah_diminta']): ?>
                                    <span class="badge bg-success">Cukup</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Kurang</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if ($permintaan['status'] === 'menunggu'): ?>
    <div id="action-buttons" class="mt-4 text-end">
        <!-- Form untuk Menolak Permintaan -->
        <form action="/gudang/permintaan/update/<?= esc($permintaan['id'], 'url') ?>" method="post" class="d-inline action-form">
            <?= csrf_field() ?>
            <input type="hidden" name="status" value="ditolak">
            <button type="submit" class="btn btn-danger">Tolak Permintaan</button>
        </form>

        <!-- Form untuk Menyetujui Permintaan -->
        <form action="/gudang/permintaan/update/<?= esc($permintaan['id'], 'url') ?>" method="post" class="d-inline action-form">
            <?= csrf_field() ?>
            <input type="hidden" name="status" value="disetujui">
            <button type="submit" class="btn btn-success">Setujui & Siapkan Bahan</button>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const actionForms = document.querySelectorAll('.action-form');
    const responseMessageDiv = document.getElementById('response-message');
    const statusBadgeContainer = document.getElementById('status-badge-container');
    const actionButtonsDiv = document.getElementById('action-buttons');

    actionForms.forEach(form => {
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const button = form.querySelector('button[type="submit"]');
            const originalButtonText = button.textContent;
            
            // Nonaktifkan semua tombol
            document.querySelectorAll('.action-form button').forEach(btn => btn.disabled = true);
            button.textContent = 'Memproses...';

            const formData = new FormData(form);
            
            // Kita perlu mengambil CSRF token terbaru setiap kali submit
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = document.querySelector('input[name=' + csrfName + ']').value;
            formData.set(csrfName, csrfHash);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const result = await response.json();
                
                // Update CSRF token di semua form dengan yang baru dari server
                if(result.csrf_hash) {
                    document.querySelectorAll('input[name=' + csrfName + ']').forEach(input => {
                        input.value = result.csrf_hash;
                    });
                }
                
                responseMessageDiv.style.display = 'block';

                if (response.ok) {
                    responseMessageDiv.className = 'alert alert-success';
                    responseMessageDiv.textContent = result.message;

                    // Update status badge
                    let newBadgeClass = 'secondary';
                    if (result.new_status === 'disetujui') newBadgeClass = 'success';
                    else if (result.new_status === 'ditolak') newBadgeClass = 'danger';
                    
                    statusBadgeContainer.innerHTML = `<span class="badge bg-${newBadgeClass} fs-6">${result.new_status.charAt(0).toUpperCase() + result.new_status.slice(1)}</span>`;

                    // Sembunyikan tombol aksi
                    if (actionButtonsDiv) {
                        actionButtonsDiv.style.display = 'none';
                    }

                } else {
                    responseMessageDiv.className = 'alert alert-danger';
                    responseMessageDiv.textContent = result.message || 'Terjadi kesalahan.';
                    // Aktifkan kembali tombol jika gagal
                    document.querySelectorAll('.action-form button').forEach(btn => btn.disabled = false);
                    button.textContent = originalButtonText;
                }

            } catch (error) {
                responseMessageDiv.className = 'alert alert-danger';
                responseMessageDiv.textContent = 'Tidak dapat terhubung ke server.';
                responseMessageDiv.style.display = 'block';
                
                // Aktifkan kembali tombol jika gagal
                document.querySelectorAll('.action-form button').forEach(btn => btn.disabled = false);
                button.textContent = originalButtonText;
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

