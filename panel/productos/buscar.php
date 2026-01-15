<?php require_once __DIR__ . '/../../conexion.php'; ?>

<form method="POST" action="eliminar.php">
    <button type="submit" class="btn btn-danger mb-3" onclick="return confirm('¿Estás seguro de eliminar los productos seleccionados?')">Eliminar seleccionados</button>
    <table class="table align-middle table-hover">
        <tr>
            <th class="text-center"><input type="checkbox" id="selectAll"></th>
            <th>Producto</th>
            <th>Precio</th>
            <th>Fecha</th>
        </tr>

        <?php
        if (isset($_POST['busqueda']) && $_POST['busqueda'] !== ''):
            $busqueda = '%' . $_POST['busqueda'] . '%';
            $stmt = $conexion->prepare("SELECT * FROM productos WHERE producto LIKE ?");
            $stmt->bind_param("s", $busqueda);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while ($fila = $resultado->fetch_assoc()):
        ?>

                <tr>
                    <td class="text-center"><input type="checkbox" name="ids[]" value="<?= $fila['id'] ?>"></td>
                    <td onclick="window.location='editar.php?id=<?= $fila['id'] ?>';" style="cursor:pointer;"><?= $fila['producto'] ?></td>
                    <td onclick="window.location='editar.php?id=<?= $fila['id'] ?>';" style="cursor:pointer;"><?= $fila['precio'] ?></td>
                    <td onclick="window.location='editar.php?id=<?= $fila['id'] ?>';" class="text-center" style="cursor:pointer;">
                        <?= date('d/m/y', strtotime($fila['fecha'])) ?>
                        <i class="fa-regular fa-alarm-clock mx-2 text-primary"></i>
                        <?= date('H:i', strtotime($fila['fecha'])) ?>
                    </td>
                </tr>

            <?php endwhile;
        else:
            $consulta = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
            while ($fila = $consulta->fetch_assoc()):
            ?>

                <tr>
                    <td class="text-center"><input type="checkbox" name="ids[]" value="<?= $fila['id'] ?>"></td>
                    <td onclick="window.location='editar.php?id=<?= $fila['id'] ?>';" style="cursor:pointer;"><?= $fila['producto'] ?></td>
                    <td onclick="window.location='editar.php?id=<?= $fila['id'] ?>';" style="cursor:pointer;"><?= $fila['precio'] ?></td>
                    <td onclick="window.location='editar.php?id=<?= $fila['id'] ?>';" class="text-center" style="cursor:pointer;">
                        <?= date('d/m/y', strtotime($fila['fecha'])) ?>
                        <i class="fa-regular fa-alarm-clock mx-2 text-primary"></i>
                        <?= date('H:i', strtotime($fila['fecha'])) ?>
                    </td>
                </tr>

        <?php endwhile;
        endif ?>
    </table>
</form>