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
        // Verificar si el formulario fue enviado para eliminar la maquina
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            //aqui cogemos la informacion de la tabla
            $id_maquina = $_POST['id_maquina'];
            //aqui hacemos la consulta de eliminar de la base de datos
            $consultaEliminar = "DELETE FROM maquina WHERE id_maquina='$id_maquina'";

            //aqui hacemos la conexion a la base de datos
            if (mysqli_query($conexion, $consultaEliminar)) {
                //aqui sale el mensaje que se a eliminado de manera correcta la maquina
                echo "<p>La maquina ha sido eliminado correctamente.</p>";
            } else {
                //aqui sale un mensaje de que ha habido un error a la hora de eliminar la maquina
                echo "<p>Error al eliminar la maquina: " . mysqli_error($conexion) . "</p>";
            }
        }

        // aqui esta la consulta de todos las maquinas
        $consulta = "SELECT * FROM maquina";
        //aqui esta hacemos la conexion a la base de datos
        $resultado = mysqli_query($conexion, $consulta);
        //aqui entra si existe alguna maquina
        if (mysqli_num_rows($resultado) > 0) {
            ?>
            <!--aqui empezamos la tabla-->
            <h2>Lista de Maquinas</h2>
            <table border="1">
                <tr>
                    <th>ID Maquina</th>
                    <th>Tipo Maquina</th>
                    <th>Estado</th>
                   
                </tr>
                <?php
                // aqui sacamos la informacion de la tabla de maquinas
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    //aqui hacemos un boton que cunado se pulse el boton de eliminar se eliminar la maquina
                    echo "<tr>
                            <td>" . $fila['id_maquina'] . "</td>
                            <td>" . $fila['tipo_maquina'] . "</td>
                            <td>" . $fila['estado'] . "</td>
                           
                            <td>
                             
                            </form>
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
            //aqui sale un mensaje de que no hay ninguna maquina
            echo "<p>No hay maquinas registrados.</p>";
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