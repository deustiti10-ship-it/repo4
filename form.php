<?php
require __DIR__ . '/config.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$datos = ['nombre' => '', 'marca' => '', 'unidades' => 0, 'ubicacion' => ''];
$errores = [];

if ($id) {
    $st = $pdo->prepare('SELECT * FROM articulos WHERE id = ?');
    $st->execute([$id]);
    $fila = $st->fetch();
    if (!$fila) { flash('El artículo no existe.', 'error'); header('Location: index.php'); exit; }
    $datos = $fila;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $datos['nombre']    = trim($_POST['nombre'] ?? '');
    $datos['marca']     = trim($_POST['marca'] ?? '');
    $datos['unidades']  = $_POST['unidades'] ?? '';
    $datos['ubicacion'] = trim($_POST['ubicacion'] ?? '');

    if ($datos['nombre'] === '' || mb_strlen($datos['nombre']) > 150)       $errores[] = 'El nombre es obligatorio (máx. 150 caracteres).';
    if ($datos['marca'] === '' || mb_strlen($datos['marca']) > 100)         $errores[] = 'La marca es obligatoria (máx. 100 caracteres).';
    if ($datos['ubicacion'] === '' || mb_strlen($datos['ubicacion']) > 150) $errores[] = 'La ubicación es obligatoria (máx. 150 caracteres).';
    if (filter_var($datos['unidades'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false)
        $errores[] = 'Las unidades deben ser un número entero igual o mayor a 0.';

    if (!$errores) {
        $params = [$datos['nombre'], $datos['marca'], (int)$datos['unidades'], $datos['ubicacion']];
        if ($id) {
            $pdo->prepare('UPDATE articulos SET nombre=?, marca=?, unidades=?, ubicacion=? WHERE id=?')
                ->execute([...$params, $id]);
            flash('Artículo actualizado correctamente.');
        } else {
            $pdo->prepare('INSERT INTO articulos (nombre, marca, unidades, ubicacion) VALUES (?,?,?,?)')
                ->execute($params);
            flash('Artículo creado correctamente.');
        }
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $id ? 'Editar' : 'Nuevo' ?> artículo</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<main class="container narrow">
    <header class="top"><h1><?= $id ? 'Editar' : 'Nuevo' ?> artículo</h1></header>

    <?php foreach ($errores as $er): ?>
        <div class="alert error"><?= e($er) ?></div>
    <?php endforeach; ?>

    <form method="post" class="card">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int)$id ?>">

        <label>Nombre
            <input type="text" name="nombre" maxlength="150" required value="<?= e($datos['nombre']) ?>">
        </label>
        <label>Marca
            <input type="text" name="marca" maxlength="100" required value="<?= e($datos['marca']) ?>">
        </label>
        <label>Unidades
            <input type="number" name="unidades" min="0" step="1" required value="<?= e($datos['unidades']) ?>">
        </label>
        <label>Ubicación
            <input type="text" name="ubicacion" maxlength="150" required value="<?= e($datos['ubicacion']) ?>">
        </label>

        <div class="buttons">
            <button class="btn primary" type="submit">Guardar</button>
            <a class="btn" href="index.php">Cancelar</a>
        </div>
    </form>
</main>
</body>
</html>
