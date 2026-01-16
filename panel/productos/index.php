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
    <link rel="stylesheet" href="../../css/style.css">

</head>

<body>

    <main class="container-fluid my-3">

        <div class="row">

            <!-- Add category -->
            <section class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">

                        <h2 class="fs-4">Categorías</h2>

                        <?php
                        if (isset($_POST['category'])):
                            $nombre = $_POST['nombre'] ?? '';
                            $sentencia = $conexion->prepare("INSERT INTO categorias (nombre) VALUES (?)");
                            $sentencia->bind_param('s', $nombre);
                            $sentencia->execute();
                            $sentencia->close();
                        endif;
                        ?>

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
            </section>

            <!-- Percentage in products -->
            <section class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">

                        <h2 class="fs-4">Generar interés sobre productos</h2>

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
                </div>
            </section>

        </div>

        <!-- Inserción y edición -->
        <div class="card mb-3">
            <div class="card-body">
                <?php
                $id = $_GET['editar'] ?? null;
                $editar = null;

                if ($id) {
                    $sentencia = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
                    $sentencia->bind_param("i", $id);
                    $sentencia->execute();
                    $resultado = $sentencia->get_result();
                    $editar = $resultado->fetch_assoc();
                }
                ?>

                <?php if ($editar): ?>

                    <!-- Formulario edición -->
                    <form method="POST" action="update" class="d-flex flex-column flex-md-row gap-2 mb-3">
                        <input type="hidden" name="id" value="<?= $editar['id'] ?>">
                        <input type="text" name="codigo" class="form-control" value="<?= $editar['codigo'] ?>">
                        <input type="text" name="producto" class="form-control" value="<?= $editar['producto'] ?>">
                        <input type="text" name="costo" class="form-control" value="<?= $editar['costo'] ?>">
                        <input type="text" name="precio" class="form-control" value="<?= $editar['precio'] ?>">
                        <input type="submit" value="Actualizar" class="btn btn-primary">
                    </form>

                <?php else: ?>

                    <!-- Formulario inserción -->
                    <form method="POST" action="/insert" class="d-flex flex-column flex-md-row gap-2 mb-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-barcode text-danger"></i></span>
                            <input type="text" name="codigo" class="form-control" placeholder="Código de barras">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-brands fa-product-hunt"></i></span>
                            <input type="text" name="producto" class="form-control" placeholder="Producto">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" name="costo" class="form-control" placeholder="Costo">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" name="precio" class="form-control" placeholder="Precio">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-layer-group"></i></span>
                            <select name="categoria" class="form-select" aria-label="Seleccionar categoría">
                                <option selected value="0">Categoría</option>
                                <?php
                                $consulta = $conexion->query("SELECT * FROM categorias ORDER BY nombre ASC");
                                while ($fila = $consulta->fetch_assoc()):
                                ?>
                                    <option value="<?= $fila['id'] ?>"><?= $fila['nombre'] ?></option>
                                <?php
                                endwhile
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="submit" value="Agregar producto" class="btn btn-primary w-100 w-md-auto">
                        </div>
                    </form>

                <?php endif; ?>

            </div>
        </div>

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

    </main>

    <!-- Scripts -->
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script src="../../js/selectAll.js"></script>
    <script src="../../js/buscar.js"></script>

</body>

</html>