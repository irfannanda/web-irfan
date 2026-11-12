<?php
require 'config.php';

// Satu file untuk Tambah (Create) dan Ubah (Update)
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$data = ['nim' => '', 'nama' => '', 'email' => '', 'jurusan' => ''];
$errors = [];

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM mahasiswa WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        flash('Data tidak ditemukan.', 'err');
        header('Location: index.php');
        exit;
    }
    $data = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    foreach (['nim', 'nama', 'email', 'jurusan'] as $f) {
        $data[$f] = trim($_POST[$f] ?? '');
    }

    if ($data['nim'] === '')     $errors['nim'] = 'NIM wajib diisi.';
    elseif (!preg_match('/^[0-9A-Za-z]{3,20}$/', $data['nim'])) $errors['nim'] = 'NIM hanya huruf/angka, 3–20 karakter.';
    if ($data['nama'] === '')    $errors['nama'] = 'Nama wajib diisi.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Format email tidak valid.';
    if ($data['jurusan'] === '') $errors['jurusan'] = 'Jurusan wajib diisi.';

    // Cek NIM unik
    if (!isset($errors['nim'])) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM mahasiswa WHERE nim = ? AND id <> ?');
        $stmt->execute([$data['nim'], $id]);
        if ($stmt->fetchColumn() > 0) $errors['nim'] = 'NIM sudah terdaftar.';
    }

    if (!$errors) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE mahasiswa SET nim=?, nama=?, email=?, jurusan=? WHERE id=?');
            $stmt->execute([$data['nim'], $data['nama'], $data['email'], $data['jurusan'], $id]);
            flash('Perubahan disimpan.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO mahasiswa (nim, nama, email, jurusan) VALUES (?,?,?,?)');
            $stmt->execute([$data['nim'], $data['nama'], $data['email'], $data['jurusan']]);
            flash('Mahasiswa ditambahkan.');
        }
        header('Location: index.php');
        exit;
    }
}

$title = $id ? 'Edit mahasiswa' : 'Tambah mahasiswa';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="wrap narrow">
    <header class="top">
        <h1><?= e($title) ?></h1>
        <a class="btn ghost" href="index.php">Kembali</a>
    </header>

    <form class="card" method="post" novalidate>
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <?php
        $fields = [
            'nim'     => ['NIM', 'text'],
            'nama'    => ['Nama lengkap', 'text'],
            'email'   => ['Email', 'email'],
            'jurusan' => ['Jurusan', 'text'],
        ];
        foreach ($fields as $name => [$label, $type]): ?>
            <label>
                <span><?= $label ?></span>
                <input type="<?= $type ?>" name="<?= $name ?>" value="<?= e($data[$name]) ?>"
                       class="<?= isset($errors[$name]) ? 'invalid' : '' ?>" required>
                <?php if (isset($errors[$name])): ?>
                    <small class="error"><?= e($errors[$name]) ?></small>
                <?php endif; ?>
            </label>
        <?php endforeach; ?>

        <button class="btn primary" type="submit"><?= $id ? 'Simpan perubahan' : 'Tambah mahasiswa' ?></button>
    </form>
</main>
</body>
</html>
