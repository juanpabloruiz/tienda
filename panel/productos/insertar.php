<?php
require_once __DIR__ . '/../../conexion.php';

$producto = $_POST['producto'];
$precio = $_POST['precio'];

$sentencia = $conexion->prepare("INSERT INTO productos (producto, precio) VALUES (?, ?)");
$sentencia->bind_param("si", $producto, $precio);
$sentencia->execute();

header('location:./');
?>