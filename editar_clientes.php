<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Listar Clientes</title>
    </head>
    <body>
        <?php
        // Conectar con el servidor de base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");

        // Verificar si el formulario fue enviado para eliminar un cliente
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            $id = $_POST['id_cliente'];
            $consultaEliminar = "DELETE FROM cliente WHERE id_cliente='$id'";

            if (mysqli_query($conexion, $consultaEliminar)) {
                echo "<p>El cliente ha sido eliminado correctamente.</p>";
            } else {
                echo "<p>Error al eliminar el cliente: " . mysqli_error($conexion) . "</p>";
            }
        }

        // Consulta de todos los clientes
        $consulta = "SELECT * FROM cliente";
        $resultado = mysqli_query($conexion, $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            ?>
            <h2>Lista de Clientes</h2>
            <table border="1">
                <tr>
                    <th>ID Cliente</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Nº Catastro</th>
                    <th>Acciones</th>
                </tr>
                <?php
                // Mostrar los clientes en la tabla
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                            <td>" . $fila['id_cliente'] . "</td>
                            <td>" . $fila['nombre'] . "</td>
                            <td>" . $fila['dni'] . "</td>
                            <td>" . $fila['id_catastro'] . "</td>
                            <td>
                                <form action='' method='POST' onsubmit='return confirm(\"¿Estás seguro de eliminar este cliente?\")'>
                                    <input type='hidden' name='id_cliente' value='" . $fila['id_cliente'] . "'>
                                    <input type='submit' name='eliminar' value='Eliminar'>
                                </form>
                            </td>
                          </tr>";
                }
                ?>
            </table>
            <?php
        } else {
            echo "<p>No hay clientes registrados.</p>";
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