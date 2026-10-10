# Gestión de artículos (PHP + MySQL)

CRUD de artículos con campos: nombre, marca, unidades y ubicación.

## Instalación
1. Sube la carpeta a un servidor con PHP 7.4+ (con extensión PDO MySQL).
2. Si hace falta, ajusta las credenciales en `config.php`.
3. Abre `index.php` en el navegador. La tabla `articulos` se crea sola si no existe.

## Archivos
- `config.php`: conexión PDO, creación de tabla y utilidades (CSRF, mensajes).
- `index.php`: listado y búsqueda.
- `form.php`: crear y editar.
- `delete.php`: eliminar (solo POST).
- `css/style.css`: estilos.
