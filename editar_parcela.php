<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Parcelas</title>
    </head>
    <body>
        <?php
        // Conexión a la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");

        // Verificar si se envió el formulario para eliminar una parcela
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            //aqui cogemos el id que queremos eliminar
            $id_parcela = $_POST['id_parcela'];


            //aqui hago la consulta para comprobar si exista lo que se quiere eliminar
            $verificarReferencias = "SELECT * FROM puntos_parcela WHERE id_parcela='$id_parcela'";
            //aqui hago la conexion a la base de datos 
            $resultadoReferencias = mysqli_query($conexion, $verificarReferencias);

            //aqui si existe alguno con ese id y si es asi entra
            if (mysqli_num_rows($resultadoReferencias) > 0) {
                //aqui guardamos la informacion de puntos_parcela con el id que se ha recogido antes
                $seleccionar_puntos = "SELECT * FROM puntos_parcela WHERE id_parcela='$id_parcela'";
                //aqui hago la conexion a la base de datos
                $seleccionar_punto = mysqli_query($conexion, $seleccionar_puntos);
                //si hay una coincidencia entra
                if (mysqli_num_rows($seleccionar_punto) > 0) {
                    //aqui recogo la informacion de la base de datos en la variable
                    $datos = mysqli_fetch_assoc($seleccionar_punto);
                    //aqui solo cogo la informacion del id_punto
                    $id_puntos = $datos['id_punto'];
                }

                //aqui le borramos el punto de asociacion para poder eliminar luego tanto las parcelas como los puntos
                $eliminar_punto_asociado = "DELETE FROM puntos_parcela WHERE id_parcela='$id_parcela'";
                //aqui hago la conexion a la base de datos
                if (mysqli_query($conexion, $eliminar_punto_asociado)) {
                    echo "Eliminado punto de asociacion";
                }
                //aqui le borramos el punto de asociacion con el id que he recogido antes
                $eliminar_punto = "DELETE FROM puntos WHERE id_punto='$id_puntos'";

                //aqui hago la conexion a la base de datos 
                if (mysqli_query($conexion, $eliminar_punto)) {
                    echo "Eliminado punto";
                }



                // aqui hago la consulta a la base de datos para eliminar la parcela
                $eliminarParcela = "DELETE FROM parcela WHERE id_parcela='$id_parcela'";
                //aqui hago la conexion a la base de datos con la consulta para eliminar la parcela
                if (mysqli_query($conexion, $eliminarParcela)) {
                    echo "<p>La parcela ha sido eliminada correctamente.</p>";
                } else {
                    echo "<p>Error al eliminar la parcela: " . mysqli_error($conexion) . "</p>";
                }
            } else {
                echo "<p>No puedes eliminar esta parcela porque no se encuentran puntos asociados.</p>";
            }
        }



        // Consultar todas las parcelas
        $consulta = "SELECT * FROM parcela";
        $resultado = mysqli_query($conexion, $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            ?>
            <h2>Editar Parcelas</h2>
            <table border="1">
                <tr>
                    <th>ID Parcela</th>
                    <th>ID Catastro</th>
                    <th>Número Parcela</th>
                    <th>Acciones</th>
                </tr>
                <?php
                // Mostrar las parcelas en la tabla
                while ($row = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                        <td>" . $row['id_parcela'] . "</td>
                        <td>" . $row['id_catastro'] . "</td>
                        <td>" . $row['numero_parcela'] . "</td>
                        <td>
                            <!-- Botón Editar -->
                            <form action='modificar_parcelas.php' method='GET' style='display:inline-block;'>
                                <input type='hidden' name='id_parcela' value='" . $row['id_parcela'] . "'>
                                <input type='submit' value='Editar'>
                            </form>
                            <!-- Botón Eliminar -->
                            <form action='' method='POST' onsubmit='return confirm(\"¿Estás seguro de eliminar esta parcela?\")' style='display:inline-block;'>
                                <input type='hidden' name='id_parcela' value='" . $row['id_parcela'] . "'>
                                <input type='submit' name='eliminar' value='Eliminar'>
                            </form>
                        </td>
                      </tr>";
                }
                ?>
            </table>
            <?php
        } else {
            echo "<p>No hay parcelas registradas.</p>";
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
        ?>
        <br>
        <!-- Botón para volver al menú principal -->
        <form action="añadir_parcelas.php" method="POST">
            <input type="submit" name="anadir_parcela" value="Añadir Parcela">
        </form>
        <br>
        <!-- Botón para volver al menú principal -->
        <form action="menu.php" method="POST">
            <input type="submit" name="volver" value="Volver">
        </form>
    </body>
</html>