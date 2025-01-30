<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión de Trabajos</title>
        <style>
            /* Estilo general del cuerpo */
            body {
                font-family: Arial, sans-serif;
                background-color: #f1f8e9;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: flex-start;
                height: 100vh;
            }

            /* Contenedor principal */
            .contenedor {
                width: 80%;
                max-width: 1000px;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                box-sizing: border-box;
            }

            /* Títulos */
            h2 {
                color: #388e3c;
                text-align: center;
            }

            /* Estilo para las etiquetas */
            label {
                font-size: 16px;
                color: #388e3c;
                display: block;
                margin: 10px 0 5px;
            }

            /* Estilo para el select */
            select {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 5px;
                border: 1px solid #ccc;
                font-size: 14px;
            }

            /* Estilo para el botón */
            button {
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

            /* Hover para el botón */
            button:hover {
                background-color: #2c6e29;
            }

            /* Estilo para la tabla */
            table {
                width: 100%;
                margin-top: 20px;
                border-collapse: collapse;
            }

            th, td {
                padding: 10px;
                text-align: center;
                border: 1px solid #ddd;
            }

            th {
                background-color: #388e3c;
                color: white;
            }

            /* Estilo para los formularios dentro de las tablas */
            td form {
                margin: 0;
            }

            td button {
                padding: 5px 15px;
                font-size: 14px;
            }

            /* Estilo para el enlace de volver */
            form input[type="submit"] {
                background-color: #388e3c;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }

            form input[type="submit"]:hover {
                background-color: #2c6e29;
            }
        </style>

    </head>
    <body>
        <div class="contenedor">
            <h2>Elegir Trabajo</h2>

            <form method="POST" action="">
                <label for="id_trabajo">Seleccione un trabajo:</label>
                <select name="id_trabajo" id="id_trabajo" required>
                    <option value="">Seleccione un trabajo</option>
                    <?php
                    session_start();
                    $conexion = mysqli_connect("localhost", "root", "", "agricultura") or die("No se puede conectar con el servidor");

                    if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'agricultor') {
                        die("Acceso denegado. Solo los agricultores pueden acceder.");
                    }
                    if (!isset($_SESSION['dni'])) {
                        die("Error: No se encontró el DNI del agricultor en la sesión.");
                    }

                    $dni_agricultor = $_SESSION['dni'];
                    $consultaId = "SELECT id_agricultor FROM agricultor WHERE dni = '$dni_agricultor'";
                    $resultadoId = mysqli_query($conexion, $consultaId);
                    $fila = mysqli_fetch_assoc($resultadoId);

                    if (!$fila) {
                        die("Error: No se encontró el agricultor en la base de datos.");
                    }

                    $id_agricultor = $fila['id_agricultor'];

                    $consultaTrabajos = "SELECT t.id_trabajo, p.numero_parcela, m.tipo_maquina, t.tipo_trabajo, t.estado 
                                 FROM trabajo t
                                 JOIN parcela p ON t.id_parcela = p.id_parcela
                                 JOIN maquina m ON t.id_maquina = m.id_maquina
                                 WHERE t.estado = 'pendiente' AND NOT EXISTS (
                                     SELECT 1 FROM trabajo_agricultor ta WHERE ta.id_trabajo = t.id_trabajo AND ta.id_agricultor = '$id_agricultor'
                                 )";
                    $resultadoTrabajos = mysqli_query($conexion, $consultaTrabajos);

                    while ($trabajo = mysqli_fetch_assoc($resultadoTrabajos)) {
                        echo "<option value='{$trabajo['id_trabajo']}'>Trabajo #{$trabajo['id_trabajo']} - Parcela #{$trabajo['numero_parcela']} - {$trabajo['tipo_maquina']} - {$trabajo['tipo_trabajo']}</option>";
                    }
                    ?>
                </select>
                <br><br>
                <button type="submit" name="elegir_trabajo">Asignarme este trabajo</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['elegir_trabajo'])) {
                $id_trabajo = $_POST['id_trabajo'];
                $fecha_inicio = date("Y-m-d H:i:s");

                $verificarAsignacion = "SELECT * FROM trabajo_agricultor WHERE id_trabajo = '$id_trabajo' AND id_agricultor = '$id_agricultor'";
                $resultadoVerificar = mysqli_query($conexion, $verificarAsignacion);

                if (mysqli_num_rows($resultadoVerificar) == 0) {
                    $consulta = "INSERT INTO trabajo_agricultor (id_trabajo, id_agricultor) VALUES ('$id_trabajo', '$id_agricultor')";
                    if (mysqli_query($conexion, $consulta)) {
                        $actualizarTrabajo = "UPDATE trabajo SET estado = 'aceptado', fecha_inicio = '$fecha_inicio' WHERE id_trabajo = '$id_trabajo'";
                        mysqli_query($conexion, $actualizarTrabajo);
                        $actualizarAgricultor = "UPDATE agricultor SET estado = 'ocupado' WHERE id_agricultor = '$id_agricultor'";
                        mysqli_query($conexion, $actualizarAgricultor);
                        echo "<p>Trabajo asignado correctamente y marcado como aceptado.</p>";
                    } else {
                        echo "<p>Error al asignar el trabajo: " . mysqli_error($conexion) . "</p>";
                    }
                } else {
                    echo "<p>Este trabajo ya ha sido asignado a usted.</p>";
                }
            }

            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['completar_trabajo'])) {
                $id_trabajo = $_POST['id_trabajo'];
                $fecha_fin = date("Y-m-d H:i:s");

                $completarTrabajo = "UPDATE trabajo SET estado = 'completado', fecha_fin = '$fecha_fin' WHERE id_trabajo = '$id_trabajo'";
                mysqli_query($conexion, $completarTrabajo);
                $actualizarAgricultor = "UPDATE agricultor SET estado = 'libre' WHERE id_agricultor = '$id_agricultor'";
                mysqli_query($conexion, $actualizarAgricultor);

                echo "<p>Trabajo completado correctamente y marcado como finalizado.</p>";
            }
            ?>

            <h2>Trabajos Asignados</h2>
            <table border="1">
                <tr>
                    <th>ID Trabajo</th>
                    <th>Parcela</th>
                    <th>Máquina</th>
                    <th>Tipo de Trabajo</th>
                    <th>Estado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acción</th>
                </tr>
                <?php
                $consultaTrabajosAsignados = "SELECT t.id_trabajo, p.numero_parcela, m.tipo_maquina, t.tipo_trabajo, t.estado, t.fecha_inicio, t.fecha_fin 
                                       FROM trabajo t
                                       JOIN trabajo_agricultor ta ON t.id_trabajo = ta.id_trabajo
                                       JOIN parcela p ON t.id_parcela = p.id_parcela
                                       JOIN maquina m ON t.id_maquina = m.id_maquina
                                       WHERE ta.id_agricultor = '$id_agricultor'";
                $resultadoTrabajosAsignados = mysqli_query($conexion, $consultaTrabajosAsignados);

                while ($trabajo = mysqli_fetch_assoc($resultadoTrabajosAsignados)) {
                    echo "<tr>
                    <td>{$trabajo['id_trabajo']}</td>
                    <td>{$trabajo['numero_parcela']}</td>
                    <td>{$trabajo['tipo_maquina']}</td>
                    <td>{$trabajo['tipo_trabajo']}</td>
                    <td>{$trabajo['estado']}</td>
                    <td>{$trabajo['fecha_inicio']}</td>
                    <td>{$trabajo['fecha_fin']}</td>
                    <td>
                        <form method='POST' action=''>
                            <input type='hidden' name='id_trabajo' value='{$trabajo['id_trabajo']}'>
                            <button type='submit' name='completar_trabajo'>Marcar como completado</button>
                        </form>
                    </td>
                </tr>";
                }
                mysqli_close($conexion);
                ?>
            </table>
            <br>
            <form action="menu.php" method="POST">
                <input type="submit" name="volver" value="Volver"><br>
            </form>
        </div>
    </body>
</html>