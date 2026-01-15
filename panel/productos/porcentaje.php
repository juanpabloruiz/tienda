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