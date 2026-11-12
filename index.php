<?php
require 'config.php';

$q = trim($_GET['q'] ?? '');
$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));

$where = '';
$params = [];
if ($q !== '') {
    $where = 'WHERE nim LIKE :q OR nama LIKE :q OR jurusan LIKE :q';
    $params[':q'] = "%$q%";
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM mahasiswa $where");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();
$pages = max(1, (int)ceil($total / $perPage));
$page = min($page, $pages);
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("SELECT * FROM mahasiswa $where ORDER BY id DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$rows = $stmt->fetchAll();

$flash = flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="wrap">
    <header class="top">
        <div>
            <h1>Data Mahasiswa</h1>
            <p class="muted"><?= $total ?> data<?= $q !== '' ? ' untuk “' . e($q) . '”' : '' ?></p>
        </div>
        <a class="btn primary" href="form.php">Tambah mahasiswa</a>
    </header>

    <?php if ($flash): ?>
        <div class="flash <?= e($flash['type']) ?>" role="status"><?= e($flash['msg']) ?></div>
    <?php endif; ?>

    <form class="search" method="get">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari NIM, nama, atau jurusan" aria-label="Cari">
        <button class="btn" type="submit">Cari</button>
        <?php if ($q !== ''): ?><a class="btn ghost" href="index.php">Reset</a><?php endif; ?>
    </form>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jurusan</th>
                    <th class="right">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$rows): ?>
                <tr><td colspan="5" class="empty">Belum ada data. Klik “Tambah mahasiswa” untuk memulai.</td></tr>
            <?php endif; ?>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= e($r['nim']) ?></td>
                    <td><?= e($r['nama']) ?></td>
                    <td><?= e($r['email']) ?></td>
                    <td><?= e($r['jurusan']) ?></td>
                    <td class="right actions">
                        <a class="btn small" href="form.php?id=<?= (int)$r['id'] ?>">Edit</a>
                        <form method="post" action="hapus.php" onsubmit="return confirm('Hapus data <?= e(addslashes($r['nama'])) ?>?')">
                            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <button class="btn small danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pages > 1): ?>
    <nav class="pager" aria-label="Halaman">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a class="<?= $i === $page ? 'current' : '' ?>"
               href="?page=<?= $i ?><?= $q !== '' ? '&q=' . urlencode($q) : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </nav>
    <?php endif; ?>
</main>
</body>
</html>
