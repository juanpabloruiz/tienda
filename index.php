<?php require_once __DIR__ . '/conexion.php'; ?>
<a href="/panel">Panel</a>
<?php
$resultado = $conexion->query("SELECT * FROM productos ORDER BY id DESC LIMIT 12");
while ($fila = $resultado->fetch_assoc()):
?>
<?= $fila['producto'] ?>
<?= $fila['precio'] ?>
<?php endwhile ?>