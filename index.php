<?php
require __DIR__ . '/config.php';

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $like = '%' . $q . '%';
    $st = $pdo->prepare('SELECT * FROM articulos WHERE nombre LIKE ? OR marca LIKE ? OR ubicacion LIKE ? ORDER BY id DESC');
    $st->execute([$like, $like, $like]);
} else {
    $st = $pdo->query('SELECT * FROM articulos ORDER BY id DESC');
}
$articulos = $st->fetchAll();
$flash = flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gestión de artículos</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<main class="container">
    <header class="top">
        <h1>Gestión de artículos</h1>
        <a class="btn primary" href="form.php">+ Nuevo artículo</a>
    </header>

    <?php if ($flash): ?>
        <div class="alert <?= e($flash[1]) ?>"><?= e($flash[0]) ?></div>
    <?php endif; ?>

    <form class="search" method="get">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar por nombre, marca o ubicación…">
        <button class="btn" type="submit">Buscar</button>
        <?php if ($q !== ''): ?><a class="btn" href="index.php">Limpiar</a><?php endif; ?>
    </form>

    <div class="table-wrap">
    <table>
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Marca</th><th>Unidades</th><th>Ubicación</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php if (!$articulos): ?>
            <tr><td colspan="6" class="empty">No hay artículos para mostrar.</td></tr>
        <?php endif; ?>
        <?php foreach ($articulos as $a): ?>
            <tr>
                <td><?= (int)$a['id'] ?></td>
                <td><?= e($a['nombre']) ?></td>
                <td><?= e($a['marca']) ?></td>
                <td><?= (int)$a['unidades'] ?></td>
                <td><?= e($a['ubicacion']) ?></td>
                <td class="actions">
                    <a class="btn small" href="form.php?id=<?= (int)$a['id'] ?>">Editar</a>
                    <form method="post" action="delete.php" onsubmit="return confirm('¿Eliminar este artículo?');">
                        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                        <button class="btn small danger" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <p class="count"><?= count($articulos) ?> artículo(s)</p>
</main>
</body>
</html>
