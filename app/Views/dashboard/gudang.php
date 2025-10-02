<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $title ?></title>
</head>
<body>
    <h1>Selamat Datang, <?= $name ?> (Petugas Gudang)</h1>
    <p>Autentikasi Berhasil. Anda memiliki hak akses Admin Gudang.</p>
    <ul>
        <li><a href="#">Tambah Bahan Baku (Langkah Berikutnya)</a></li>
        <li><a href="#">Lihat Data Bahan Baku (Langkah Berikutnya)</a></li>
        <li><a href="<?= base_url('logout') ?>">Logout</a></li>
    </ul>
    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
</body>
</html>