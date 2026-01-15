<?php
require_once __DIR__ . '/../../conexion.php';

$producto = $_POST['producto'];
$precio = $_POST['precio'];

mysqli_query($conexion, "INSERT INTO productos (producto, precio) VALUES ('$producto', '$precio')");

header('location:./');
?>