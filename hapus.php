<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
csrf_check();

$id = (int)($_POST['id'] ?? 0);
$stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE id = ?');
$stmt->execute([$id]);

flash($stmt->rowCount() ? 'Data dihapus.' : 'Data tidak ditemukan.', $stmt->rowCount() ? 'ok' : 'err');
header('Location: index.php');
exit;
