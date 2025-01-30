<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Agricultores</title>
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
            <form action="añadir_agricultores.php" method="POST">
                <input type="submit" name="añadir" value="Añadir agricultores"><br>
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
                <table>
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

            <div class="boton-volver">
                <form action="menu.php" method="POST">
                    <input type="submit" name="volver" value="Volver"><br>
                </form>
            </div>
        </div>
    </body>
</html>


