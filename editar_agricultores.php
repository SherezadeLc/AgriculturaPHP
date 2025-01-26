<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Agricultores</title>
    </head>
    <body>
        <form action="añadir_agricultores.php" method="POST">
            <input type="submit" name="volver" value="Volver"><br>
        </form>
        <?php
        // Conectar con el servidor de base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");

        // Verificar si el formulario fue enviado para eliminar un agricultor
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            $id = $_POST['id_agricultor'];
            $consultaEliminar = "DELETE FROM agricultor WHERE id_agricultor='$id'";

            if (mysqli_query($conexion, $consultaEliminar)) {
                echo "<p>El agricultor ha sido eliminado correctamente.</p>";
            } else {
                echo "<p>Error al eliminar el agricultor: " . mysqli_error($conexion) . "</p>";
            }
        }

        // Consultar todos los agricultores
        $consulta = "SELECT * FROM agricultor";
        $resultado = mysqli_query($conexion, $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            ?>
            <h2>Editar Agricultores</h2>
            <table border="1">
                <tr>
                    <th>ID Agricultor</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                <?php
                // Mostrar los agricultores en la tabla
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                            <td>" . $fila['id_agricultor'] . "</td>
                            <td>" . $fila['nombre'] . "</td>
                            <td>" . $fila['dni'] . "</td>
                            <td>" . $fila['estado'] . "</td>
                            <td>
                                <form action='modificar_agricultores.php' method='GET'>
                                    <input type='hidden' name='id_agricultor' value='" . $fila['id_agricultor'] . "'>
                                    <input type='submit' name='modificar' value='Editar'>
                                </form>
                                <!-- Botón Eliminar -->
                                <form action='' method='POST' onsubmit='return confirm(\"¿Estás seguro de eliminar este agricultor?\")'>
                                    <input type='hidden' name='id_agricultor' value='" . $fila['id_agricultor'] . "'>
                                    <input type='submit' name='eliminar' value='Eliminar'>
                                </form>
                            </td>
                          </tr>";
                }
                ?>
            </table>
            <?php
        } else {
            echo "<p>No hay agricultores registrados.</p>";
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
        ?>

        <br>
        <!-- Formulario para volver al menú principal -->
        <form action="menu.php" method="POST">
            <input type="submit" name="volver" value="Volver"><br>
        </form>
    </body>
</html>

