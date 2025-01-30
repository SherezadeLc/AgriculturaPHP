<?php
session_start();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Cambiar parcela</title>
        <style>
            /* Estilo general del cuerpo */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f7f6;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            /* Contenedor principal */
            .contenedor {
                background-color: #ffffff;
                padding: 30px;
                width: 80%;
                max-width: 800px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                box-sizing: border-box;
                text-align: center;
            }

            /* Títulos */
            h2 {
                color: #388e3c;
                margin-bottom: 20px;
            }

            /* Estilo para las tablas */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            /* Estilo para las celdas */
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }

            th {
                background-color: #388e3c;
                color: white;
            }

            /* Estilo para los botones */
            input[type="submit"] {
                background-color: #388e3c;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
                transition: background-color 0.3s;
            }

            /* Hover para el botón de volver */
            input[type="submit"]:hover {
                background-color: #2c6e29;
            }

            /* Estilo para los mensajes */
            p {
                color: #d32f2f;
                font-weight: bold;
            }
        </style>

    </head>
    <body>
        <div class="contenedor">
            <?php
            // Conexión a la base de datos
            $conexion = mysqli_connect("localhost", "root", "", "agricultura") or die("No se puede conectar con el servidor");
            $dniUsuario = $_SESSION['dni'];

            // Inicializar variables con valores predeterminados
            $id_punto = null;
            $id_parcela = null;

            // Consulta para obtener los datos de puntos_parcela
            $coger_datos = "SELECT * FROM puntos_parcela WHERE dni_cliente='$dniUsuario'";

            // Ejecuta la consulta
            $resultado = mysqli_query($conexion, $coger_datos);

            if ($resultado) {
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        $id_punto = $fila['id_punto'];
                        $id_parcela = $fila['id_parcela'];
                    }
                }
            } else {
                echo "Error al obtener los datos.";
            }

            // Mostramos las parcelas existentes si las variables son válidas
            if ($id_punto !== null && $id_parcela !== null) {
                echo "<h2>PARCELAS EXISTENTES</h2>";
                $parcelas_existentes = mysqli_query($conexion, "SELECT * FROM parcela WHERE id_parcela = '$id_parcela'");
                $puntos_existentes = mysqli_query($conexion, "SELECT * FROM puntos WHERE id_punto = '$id_punto'");

                if (mysqli_num_rows($parcelas_existentes) > 0 && mysqli_num_rows($puntos_existentes) > 0) {
                    echo "<div style='float:left'>";
                    echo "<table border='1'>";
                    echo "<tr><th>ID Parcela</th><th>Numero Catastro</th><th>Numero Parcela</th></tr>";

                    while ($fila1 = mysqli_fetch_assoc($parcelas_existentes)) {
                        echo "<tr>
                                <td>{$fila1['id_parcela']}</td>
                                <td>{$fila1['id_catastro']}</td>
                                <td>{$fila1['numero_parcela']}</td>
                            </tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                    echo "<div style='float:left'>";
                    echo "<table border='1'>";
                    echo "<tr><th>Latitud</th><th>Longitud</th></tr>";

                    while ($fila2 = mysqli_fetch_assoc($puntos_existentes)) {
                        echo "<tr>
                                <td>{$fila2['latitud']}</td>
                                <td>{$fila2['longitud']}</td>
                            </tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<p>No hay parcelas existentes.</p>";
                }
            } else {
                echo "<p>No se encontraron datos para el usuario.</p>";
            }

            // Cerrar conexión
            mysqli_close($conexion);
            ?>
            <br> <br> <br> <br> <br>
            <!-- Formulario para volver -->
            <form action="editar_parcela.php" method="POST">
                <input type="submit" name="volver" value="Volver">
            </form>
        </div>
    </body>
</html>
