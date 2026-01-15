<?php
require_once __DIR__ . '/../../conexion.php';
$id = $_GET['id'] ?? null;
        $editar = null;

        if ($id):
            $sentencia = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
            $sentencia->bind_param("i", $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $editar = $resultado->fetch_assoc();
        endif;
?>

<form method="POST" action="insertar.php">
    <input type="hidden" name="id" value="<?= $editar['id'] ?? '' ?>">
    <input type="text" name="producto" value="<?= $editar['producto'] ?? '' ?>">
    <input type="text" name="precio" value="<?= $editar['precio'] ?? '' ?>">
    <input type="submit" name="insertar" value="Agregar">
</form>

