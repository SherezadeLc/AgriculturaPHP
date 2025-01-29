<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegir Trabajo</title>
</head>
<body>
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
                                     SELECT 1 FROM trabajo_agricultor ta WHERE ta.id_trabajo = t.id_trabajo
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

        $consulta = "INSERT INTO trabajo_agricultor (id_trabajo, id_agricultor) 
                     VALUES ('$id_trabajo', '$id_agricultor')";

        if (mysqli_query($conexion, $consulta)) {
            $actualizarTrabajo = "UPDATE trabajo 
                                  SET estado = 'aceptado', fecha_inicio = '$fecha_inicio' 
                                  WHERE id_trabajo = '$id_trabajo'";
            mysqli_query($conexion, $actualizarTrabajo);

            $actualizarAgricultor = "UPDATE agricultor SET estado = 'ocupado' WHERE id_agricultor = '$id_agricultor'";
            mysqli_query($conexion, $actualizarAgricultor);

            echo "<p>Trabajo asignado correctamente y marcado como aceptado.</p>";
        } else {
            echo "<p>Error al asignar el trabajo: " . mysqli_error($conexion) . "</p>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['completar_trabajo'])) {
        $id_trabajo = $_POST['id_trabajo'];
        $fecha_fin = date("Y-m-d H:i:s");

        $completarTrabajo = "UPDATE trabajo 
                             SET estado = 'completado', fecha_fin = '$fecha_fin' 
                             WHERE id_trabajo = '$id_trabajo'";
        mysqli_query($conexion, $completarTrabajo);

        $actualizarAgricultor = "UPDATE agricultor SET estado = 'libre' WHERE id_agricultor = '$id_agricultor'";
        mysqli_query($conexion, $actualizarAgricultor);

        echo "<p>Trabajo completado correctamente y marcado como finalizado.</p>";
    }
    mysqli_close($conexion);
    ?>

    <br>
    <form action="menu.php" method="POST">
        <input type="submit" name="volver" value="Volver"><br>
    </form>
</body>
</html>
