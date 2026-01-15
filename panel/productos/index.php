<?php require_once __DIR__ . '/../../conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Styles -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilo.css">

</head>

<body>

    <main class="container-fluid my-3">

        <!-- Agregar categoría -->
        <?php
        if (isset($_POST['category'])):
            $nombre = $_POST['nombre'] ?? '';
            $sentencia = $conexion->prepare("INSERT INTO categorias (nombre) VALUES (?)");
            $sentencia->bind_param('s', $nombre);
            $sentencia->execute();
            $sentencia->close();
        endif;

        ?>
        <div class="row">
            <div class="col-md-6">
                <form method="POST" class="d-flex flex-column flex-md-row gap-2 mb-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Categoría">
                    <input type="submit" name="category" value="Agregar categoría" class="btn btn-primary">
                </form>
                <?php
                $sentencia = $conexion->prepare("SELECT * FROM categorias");
                $sentencia->execute();
                $resultado = $sentencia->get_result();
                ?>
                <ul>
                    <?php while ($campo = $resultado->fetch_assoc()): ?>
                        <li><?= $campo['nombre'] ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <div>
            <h5>Generar interés sobre productos</h5>
            <form method="post">
                <?php
                if (isset($_POST['porcentual'])) {
                    $categoria = $_POST['categoria'];
                    $porcentaje = $_POST['porcentaje'];
                    $porcentaje_entero = round($porcentaje);
                    $consulta = "UPDATE productos SET precio = ROUND(precio * (1 + ?/100)) WHERE categoria = ?";
                    $sentencia = $conexion->prepare($consulta);
                    $sentencia->bind_param("ii", $porcentaje_entero, $categoria);
                    if ($sentencia->execute()):
                        echo '<script>window.location="./"</script>';
                    else:
                        echo '<div class="alert alert-danger">Error en la actualización: ' . $sentencia->error . '</div>';
                    endif;
                }
                ?>
                <div class="row">
                    <div class="col-md-auto">
                        <select name="categoria" class="form-select mb-3">
                            <option disabled selected>Categoría</option>

                            <?php
                            $consulta = $conexion->query("SELECT * FROM categorias");
                            while ($campo = $consulta->fetch_assoc()):
                            ?>

                                <option value="<?php echo $campo['id']; ?>"><?php echo $campo['nombre']; ?></option>

                            <?php endwhile ?>

                        </select>
                    </div>
                    <div class="col-md-auto">
                        <div class="input-group mb-3">
                            <input type="number" name="porcentaje" class="form-control" aria-label="Amount (to the nearest dollar)">
                            <span class="input-group-text">%</span>
                            <input type="submit" name="porcentual" value="Aplicar aumento" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php
        $editar = $_GET['editar'] ?? null;
        $campo = null;

        if ($editar):
            $sentencia = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
            $sentencia->bind_param("i", $editar);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $editar = $resultado->fetch_assoc();

        ?>

            <form method="POST" action="actualizar.php">
                <input type="hidden" name="id" class="form-control" value="<?= $editar['id'] ?? '' ?>">
                <input type="text" name="producto" class="form-control" value="<?= $editar['producto'] ?? '' ?>">
                <input type="text" name="precio" class="form-control" value="<?= $editar['precio'] ?? '' ?>">
                <input type="submit" name="insertar" value="Actualizar">
            </form>

        <?php else: ?>

            <form method="POST" action="insertar.php">
                <input type="text" name="producto" class="form-control" placeholder="Producto">
                <input type="text" name="precio" class="form-control" placeholder="precio">
                <input type="submit" name="insertar" value="Agregar">
            </form>

        <?php endif; ?>

        <input type="search" placeholder="Buscar aquí..." name="busqueda" id="buscar" class="form-control">
        <hr>
        <div id="datos">
            <form method="POST" action="eliminar.php">
                <button type="submit" class="btn btn-danger mb-3" onclick="return confirm('¿Estás seguro de eliminar los productos seleccionados?')">Eliminar seleccionados</button>

                <table class="table table-hover">
                    <tr class="table-dark">
                        <th class="text-center"><input type="checkbox" id="selectAll"></th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Fecha</th>
                    </tr>

                    <?php
                    $resultado = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
                    while ($fila = $resultado->fetch_assoc()):
                    ?>

                        <tr>
                            <td class="text-center"><input type="checkbox" name="ids[]" value="<?= $fila['id'] ?>"></td>
                            <td onclick="window.location='?editar=<?= $fila['id'] ?>';" style="cursor:pointer;"><?= $fila['producto'] ?></td>
                            <td onclick="window.location='?editar=<?= $fila['id'] ?>';" style="cursor:pointer;"><?= $fila['precio'] ?></td>
                            <td onclick="window.location='?editar=<?= $fila['id'] ?>';" class="text-center" style="cursor:pointer;">
                                <?= date('d/m/y', strtotime($fila['fecha'])) ?>
                                <i class="fa-regular fa-alarm-clock mx-2 text-primary"></i>
                                <?= date('H:i', strtotime($fila['fecha'])) ?>
                            </td>
                        </tr>

                    <?php endwhile ?>

                </table>

            </form>

        </div>

    </main>

    <!-- Scripts -->
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script src="../../js/selectAll.js"></script>
    <script src="../../js/buscar.js"></script>

</body>

</html>