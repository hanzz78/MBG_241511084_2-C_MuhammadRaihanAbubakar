<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .input-error { border-color: #dc3545 !important; }
    .error-message { color: #dc3545; font-size: 0.875em; margin-top: 0.25rem; }
    #response-message { display: none; }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4><?= esc($title) ?></h4>
                </div>
                <div class="card-body">
                    
                    <!-- Placeholder untuk pesan sukses/error dari AJAX -->
                    <div id="response-message" class="alert" role="alert"></div>

                    <form action="/dapur/permintaan/create" method="post" id="permintaan-form">
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
                        <div id="bahan-container"></div>
                        <button type="button" id="add-bahan" class="btn btn-outline-secondary mt-2">Tambah Bahan</button>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="/dapur/permintaan" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" id="submit-button" class="btn btn-primary">Ajukan Permintaan</button>
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
    const form = document.getElementById('permintaan-form');
    const submitButton = document.getElementById('submit-button');
    const responseMessageDiv = document.getElementById('response-message');

    const bahanOptions = `
        <option value="">Pilih Bahan...</option>
        <?php foreach ($bahan_baku as $bahan): ?>
            <option value="<?= $bahan['id'] ?>" data-stok="<?= $bahan['jumlah'] ?>">
                <?= esc($bahan['nama']) ?> (Stok: <?= $bahan['jumlah'] ?> <?= esc($bahan['satuan']) ?>)
            </option>
        <?php endforeach; ?>
    `;

    function createNewRow() {
        const div = document.createElement('div');
        div.className = 'row bahan-item mb-3 align-items-center';
        div.innerHTML = `
            <div class="col-md-6">
                <label class="form-label">Bahan Baku</label>
                <select name="bahan_id[]" class="form-select" required>${bahanOptions}</select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jumlah Diminta</label>
                <input type="number" name="jumlah_diminta[]" class="form-control jumlah-input" required min="1">
                <div class="error-message"></div>
            </div>
            <div class="col-md-2 pt-4">
                <button type="button" class="btn btn-danger w-100 remove-bahan">Hapus</button>
            </div>
        `;
        container.appendChild(div);
    }
    
    function checkAllInputs() {
        let allValid = true;
        container.querySelectorAll('.bahan-item').forEach(row => {
            const jumlahInput = row.querySelector('.jumlah-input');
            const select = row.querySelector('select');
            const errorMessageDiv = row.querySelector('.error-message');
            
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption || !selectedOption.value) return;
            
            const stok = parseInt(selectedOption.getAttribute('data-stok')) || 0;
            const jumlahDiminta = parseInt(jumlahInput.value) || 0;

            if (stok > 0 && jumlahDiminta > stok) {
                jumlahInput.classList.add('input-error');
                errorMessageDiv.textContent = 'Jumlah melebihi stok!';
                allValid = false;
            } else {
                jumlahInput.classList.remove('input-error');
                errorMessageDiv.textContent = '';
            }
        });
        submitButton.disabled = !allValid;
    }

    addButton.addEventListener('click', createNewRow);
    container.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-bahan')) {
            if (container.querySelectorAll('.bahan-item').length > 1) {
                e.target.closest('.bahan-item').remove();
                checkAllInputs();
            } else {
                alert('Minimal harus ada satu bahan yang diminta.');
            }
        }
    });

    ['change', 'keyup', 'input'].forEach(evt => container.addEventListener(evt, checkAllInputs));

    form.addEventListener('submit', async function(event) {
        event.preventDefault(); 

        submitButton.disabled = true;
        submitButton.textContent = 'Mengirim...';

        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const result = await response.json();

            if (response.ok) {
                responseMessageDiv.className = 'alert alert-success';
                responseMessageDiv.textContent = result.message;
                responseMessageDiv.style.display = 'block';
                form.reset();
                container.innerHTML = '';
                createNewRow();
                
                setTimeout(() => {
                    window.location.href = '/dapur/permintaan';
                }, 2000);

            } else {
                const errorText = result.errors ? Object.values(result.errors).join(', ') : 'Terjadi kesalahan.';
                responseMessageDiv.className = 'alert alert-danger';
                responseMessageDiv.textContent = errorText;
                responseMessageDiv.style.display = 'block';
            }

        } catch (error) {
            responseMessageDiv.className = 'alert alert-danger';
            responseMessageDiv.textContent = 'Tidak dapat terhubung ke server. Coba lagi.';
            responseMessageDiv.style.display = 'block';
        }

        submitButton.disabled = false;
        submitButton.textContent = 'Ajukan Permintaan';
    });
    
    createNewRow();
});
</script>
<?= $this->endSection() ?>

