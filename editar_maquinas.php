<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lista de Maquinas</title>
        <style>
            /* General */
            body {
                font-family: Arial, sans-serif;
                background-color: #e8f5e9;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .contenedor {
                width: 50%;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h1 {
                color: #2e7d32;
            }

            input[type="submit"] {
                background-color: #2e7d32;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
                transition: background-color 0.3s;
                margin-top: 10px;
            }

            input[type="submit"]:hover {
                background-color: #1b5e20;
            }

            table {
                width: 100%;
                margin-top: 10px;
                border-collapse: collapse;
            }

            th, td {
                padding: 5px;
                text-align: center;
                border: 1px solid #ddd;
            }

            th {
                background-color: #2e7d32;
                color: white;
            }

            td form {
                margin: 0;
            }

            td input[type="submit"] {
                width: auto;
                margin: 5px;
                padding: 5px 10px;
            }

            .boton-volver {
                margin-top: 20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="contenedor">
            <?php
            // Conectar con el servidor de base de datos
            $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                    or die("No se puede conectar con el servidor");

            // Verificar si el formulario fue enviado para eliminar la maquina
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
                // Aquí cogemos la información de la tabla
                $id_maquina = $_POST['id_maquina'];
                // Aquí hacemos la consulta de eliminar de la base de datos
                $consultaEliminar = "DELETE FROM maquina WHERE id_maquina='$id_maquina'";

                // Aquí hacemos la conexión a la base de datos
                if (mysqli_query($conexion, $consultaEliminar)) {
                    // Aquí sale el mensaje que se ha eliminado de manera correcta la máquina
                    echo "<p>La máquina ha sido eliminada correctamente.</p>";
                } else {
                    // Aquí sale un mensaje de que ha habido un error al eliminar la máquina
                    echo "<p>Error al eliminar la máquina: " . mysqli_error($conexion) . "</p>";
                }
            }

            // Aquí está la consulta de todas las máquinas
            $consulta = "SELECT * FROM maquina";
            // Aquí hacemos la conexión a la base de datos
            $resultado = mysqli_query($conexion, $consulta);
            // Aquí entra si existe alguna máquina
            if (mysqli_num_rows($resultado) > 0) {
                ?>
                <h2>Lista de Máquinas</h2>
                <table>
                    <tr>
                        <th>ID Maquina</th>
                        <th>Tipo Maquina</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    <?php
                    // Aquí sacamos la información de la tabla de máquinas
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Aquí hacemos un botón que cuando se pulse el botón de eliminar se elimine la máquina
                        echo "<tr>
                                <td>" . $fila['id_maquina'] . "</td>
                                <td>" . $fila['tipo_maquina'] . "</td>
                                <td>" . $fila['estado'] . "</td>
                                <td>
                                    <form action='' method='POST' onsubmit='return confirm(\"¿Estás seguro de eliminar esta máquina?\")'>
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
                // Aquí sale un mensaje de que no hay ninguna máquina
                echo "<p>No hay máquinas registradas.</p>";
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);
            ?>

            <div class="boton-volver">
                <form action="menu.php" method="POST">
                    <input type="submit" name="volver" value="Volver"><br>
                </form>
                <form action="añadir_maquina.php" method="POST">
                    <input type="submit" name="volver" value="Añadir Máquina"><br>
                </form>
            </div>
        </div>
    </body>
</html>
