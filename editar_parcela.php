<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Parcelas</title>
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

            h1, h2 {
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
            // Conexión a la base de datos
            $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                    or die("No se puede conectar con el servidor");

            // Verificar si se envió el formulario para eliminar una parcela
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
                // Aquí cogemos el id que queremos eliminar
                $id_parcela = $_POST['id_parcela'];
                $dni = $_SESSION['dni'];

                // Aquí hago la consulta para comprobar si existe lo que se quiere eliminar
                $verificarReferencias = "SELECT * FROM puntos_parcela WHERE id_parcela='$id_parcela' AND dni_cliente='$dni'";
                $resultadoReferencias = mysqli_query($conexion, $verificarReferencias);

                // Si existe algún punto asociado con esa parcela
                if (mysqli_num_rows($resultadoReferencias) > 0) {
                    // Aquí le borramos el punto de asociación para poder eliminar luego las parcelas
                    $eliminar_punto_asociado = "DELETE FROM puntos_parcela WHERE id_parcela='$id_parcela' AND dni_cliente='$dni'";
                    if (mysqli_query($conexion, $eliminar_punto_asociado)) {
                        echo "Eliminado punto de asociación.";
                    }

                    // Aquí hacemos la consulta para eliminar la parcela
                    $eliminarParcela = "DELETE FROM parcela WHERE id_parcela='$id_parcela' AND dni_cliente='$dni'";
                    if (mysqli_query($conexion, $eliminarParcela)) {
                        echo "<p>La parcela ha sido eliminada correctamente.</p>";
                    } else {
                        echo "<p>Error al eliminar la parcela: " . mysqli_error($conexion) . "</p>";
                    }
                } else {
                    echo "<p>No puedes eliminar esta parcela porque no se encuentran puntos asociados o no pertenecen a tu cuenta.</p>";
                }
            }

            // Consultar todas las parcelas asociadas al cliente (dni_cliente)
            $dni_cliente = $_SESSION['dni'];
            $consulta = "SELECT * FROM parcela WHERE id_parcela IN (SELECT id_parcela FROM puntos_parcela WHERE dni_cliente='$dni_cliente')";
            $resultado = mysqli_query($conexion, $consulta);

            if (mysqli_num_rows($resultado) > 0) {
                ?>
                <h2>Editar Parcelas</h2>
                <table>
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

            <div class="boton-volver">
                <form action="añadir_parcelas.php" method="POST">
                    <input type="submit" name="anadir_parcela" value="Añadir Parcela">
                </form>
                <form action="menu.php" method="POST">
                    <input type="submit" name="volver" value="Volver">
                </form>
            </div>
        </div>
    </body>
</html>
