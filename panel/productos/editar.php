<?php
require_once __DIR__ . '/../../conexion.php';
$id = $_GET['id'] ?? null;
        $campo = null;

        if ($id):
            $sentencia = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
            $sentencia->bind_param("i", $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $campo = $resultado->fetch_assoc();
        endif;
?>

<form method="POST" action="actualizar.php">
    <input type="hidden" name="id" value="<?= $campo['id'] ?? '' ?>">
    <input type="text" name="producto" value="<?= $campo['producto'] ?? '' ?>">
    <input type="text" name="precio" value="<?= $campo['precio'] ?? '' ?>">
    <input type="submit" name="insertar" value="Actualizar">
</form>

