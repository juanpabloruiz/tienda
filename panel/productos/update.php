<?php
require_once __DIR__ . '/../../conexion.php';

$id = $_POST['id'];

$producto = $_POST['producto'];
$precio = $_POST['precio'];

$sentencia = $conexion->prepare("UPDATE productos SET producto = ?, precio = ? WHERE id = ?");
$sentencia->bind_param("sii", $producto, $precio, $id);
$sentencia->execute();

header('Location: ./');
