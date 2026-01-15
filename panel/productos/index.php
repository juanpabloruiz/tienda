<?php require_once __DIR__ . '/../../conexion.php'; ?>
<a href="nuevo.php">nuevo</a>
<table>
    <tr>
        <th>Producto</th>
        <th>Precio</th>
        <th>Fecha</th>
        <th>Funciones</th>
    </tr>


    <?php
    $resultado = $conexion->query("SELECT * FROM productos ORDER BY id DESC LIMIT 12");
    while ($fila = $resultado->fetch_assoc()):
    ?>

        <tr onclick="window.location='editar.php?id=<?= $fila['id'] ?>';" style="cursor: pointer;">
            <td><?= $fila['producto'] ?></td>
            <td><?= $fila['precio'] ?></td>
            <td><?= $fila['fecha'] ?></td>
            <td></td>
        </tr>

    <?php endwhile ?>

</table>