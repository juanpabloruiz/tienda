<?php require_once __DIR__ . '/../../conexion.php'; ?>

<form method="POST" action="insertar.php">
    <input type="text" name="producto" placeholder="Producto">
    <input type="text" name="precio" placeholder="precio">
    <input type="submit" name="insertar" value="Agregar">
</form>