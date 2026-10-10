<?php
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
csrf_check();

$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $st = $pdo->prepare('DELETE FROM articulos WHERE id = ?');
    $st->execute([$id]);
    flash($st->rowCount() ? 'Artículo eliminado.' : 'El artículo no existe.', $st->rowCount() ? 'ok' : 'error');
}
header('Location: index.php');
exit;
