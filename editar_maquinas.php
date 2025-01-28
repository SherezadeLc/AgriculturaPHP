<?php
session_start();
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        // Conectar con el servidor de base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");
        // Verificar si el formulario fue enviado para eliminar un cliente
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            $id_maquina = $_POST['id_maquina'];
            $consultaEliminar = "DELETE FROM maquina WHERE id_maquina='$id_maquina'";

            if (mysqli_query($conexion, $consultaEliminar)) {
                echo "<p>El cliente ha sido eliminado correctamente.</p>";
            } else {
                echo "<p>Error al eliminar el cliente: " . mysqli_error($conexion) . "</p>";
            }
        }

        // Consulta de todos los clientes
        $consulta = "SELECT * FROM maquina";
        $resultado = mysqli_query($conexion, $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            ?>
            <h2>Lista de Maquinas</h2>
            <table border="1">
                <tr>
                    <th>ID Maquina</th>
                    <th>Tipo Maquina</th>
                    <th>Estado</th>
                   
                </tr>
                <?php
                // Mostrar los clientes en la tabla
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                            <td>" . $fila['id_maquina'] . "</td>
                            <td>" . $fila['tipo_maquina'] . "</td>
                            <td>" . $fila['estado'] . "</td>
                           
                            <td>
                                <form action='' method='POST' onsubmit='return confirm(\"¿Estás seguro de eliminar esta maquina?\")'>
                                    <input type='hidden' name='id_maquina' value='" . $fila['id_maquina'] . "'>
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
        <form action="añadir_maquina.php" method="POST">
            <input type="submit" name="volver" value="Añadir Maquina"><br>
        </form>
    </body>
</html>