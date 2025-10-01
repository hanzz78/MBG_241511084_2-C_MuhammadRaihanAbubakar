<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        .container { max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; }
        .alert-error { color: red; }
        .alert-info { color: blue; }
        .alert-success { color: green; }
    </style>
</head>
<body>

<div class="container">
    <h2>Login Aplikasi MBG</h2>
    
    <?php if (session()->getFlashdata('error')): ?>
        <p class="alert-error"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('info')): ?>
        <p class="alert-info"><?= session()->getFlashdata('info') ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert-error">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('login/process') ?>" method="post">
        <?= csrf_field() ?>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= old('email') ?>" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    
    </p>
</div>

</body>
</html>