<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Akses Sistem MBG</h4>
                    <p class="mb-0 small">Masukkan kredensial Anda</p>
                </div>
                <div class="card-body p-4">

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('info')): ?>
                        <div class="alert alert-info"><?= session()->getFlashdata('info') ?></div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('login/process') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success mt-2">Login</button>
                        </div>
                    </form>

                    <div class="mt-4 pt-3 border-top">
                        <p class="small text-muted mb-1">Kredensial Uji Coba (Jika belum ada di DB):</p>
                        <ul class="small mb-0 list-unstyled">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
