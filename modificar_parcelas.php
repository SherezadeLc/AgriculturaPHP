<?php
     session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modificar Parcelas</title>
</head>
<body>
    <?php
    // Conexión a la base de datos
    $conexion = mysqli_connect("localhost", "root", "", "agricultura") or die("No se puede conectar con el servidor");

    // Mostramos las parcelas existentes
    echo "<h2>PARCELAS EXISTENTES</h2>";
    $parcelas_existentes = mysqli_query($conexion, "SELECT * FROM parcela");
    $puntos_existentes = mysqli_query($conexion, "SELECT * FROM puntos");

     // Consultar todas las parcelas asociadas al cliente (dni_cliente)
        $dni_cliente = $_SESSION['dni'];
        $consulta = "SELECT * FROM parcela WHERE id_parcela IN (SELECT id_parcela FROM puntos_parcela WHERE dni_cliente='$dni_cliente')";
        $resultado = mysqli_query($conexion, $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            ?>
            <h2>Editar Parcelas</h2>
            <table border="1">
                <tr>
                    <th>ID Parcela</th>
                    <th>ID Catastro</th>
                    <th>Número Parcela</th>
                    
                </tr>
                <?php
                // Mostrar las parcelas en la tabla
                while ($row = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                        <td>" . $row['id_parcela'] . "</td>
                        <td>" . $row['id_catastro'] . "</td>
                        <td>" . $row['numero_parcela'] . "</td>
                        
                            
                      </tr>";
                }
                ?>
            </table>
            <?php
        } else {
            echo "<p>No hay parcelas registradas.</p>";
        }

    ?>
<br><br><br><br>
    <br>

    <!-- Formulario para modificar la parcela -->
    <form action="cambiar_parcela.php"method="POST">
        <label for="id_parcelas">ID Parcela:</label>
        <input type="text" name="id_parcelas" id="id_parcelas" required><br><br>

        <label for="id_catastros">Numero Catastro:</label>
        <input type="text" name="id_catastros" id="id_catastros" required><br><br>

        <label for="numero_parcelas">Numero Parcela:</label>
        <input type="text" name="numero_parcelas" id="numero_parcelas" required><br><br>

        <label for="latitudes">Latitud:</label>
        <input type="text" name="latitudes" id="latitudes" required><br><br>

        <label for="longitudes">Longitud:</label>
        <input type="text" name="longitudes" id="longitudes" required><br><br>

        <input type="submit" name="modificar" value="Modificar">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
        // Recogemos los datos del formulario
        $id_parcelas = $_POST['id_parcelas'];
        $id_catastros = $_POST['id_catastros'];
        $numero_parcelas = $_POST['numero_parcelas'];
        $latitudes = $_POST['latitudes'];
        $longitudes = $_POST['longitudes'];

        // Verificamos si el número de catastro existe en la tabla cliente
        $verificar_catastro = "SELECT * FROM cliente WHERE id_catastro = '$id_catastros'";
        $resultado_verificacion = mysqli_query($conexion, $verificar_catastro);

        if (mysqli_num_rows($resultado_verificacion) > 0) {
            // Comprobamos si existe la parcela
            $consultaIntermedia = "SELECT * FROM puntos_parcela WHERE id_parcela = '$id_parcelas'";
            $seleccionar_punto = mysqli_query($conexion, $consultaIntermedia);

            if (mysqli_num_rows($seleccionar_punto) > 0) {
                // Actualizamos los datos de la parcela
                $datos = mysqli_fetch_assoc($seleccionar_punto);
                $id_puntos = $datos['id_punto'];

                // Actualizamos la tabla parcela
                $actualizar_parcela = "UPDATE parcela SET id_catastro='$id_catastros', numero_parcela='$numero_parcelas' WHERE id_parcela='$id_parcelas'";
                if (mysqli_query($conexion, $actualizar_parcela)) {
                    echo "Se ha actualizado la parcela correctamente.<br>";
                } else {
                    echo "Error al actualizar la parcela: " . mysqli_error($conexion) . "<br>";
                }

                // Actualizamos la tabla puntos
                $actualizar_puntos = "UPDATE puntos SET latitud='$latitudes', longitud='$longitudes' WHERE id_punto='$id_puntos'";
                if (mysqli_query($conexion, $actualizar_puntos)) {
                    echo "Se han actualizado los puntos correctamente.<br>";
                } else {
                    echo "Error al actualizar los puntos: " . mysqli_error($conexion) . "<br>";
                }
            } else {
                echo "No se encontró ningún punto asociado a la parcela.<br>";
            }
        } else {
            echo "El Número Catastro ingresado no existe en la tabla Cliente.<br>";
        }
    }
    ?>

    <!-- Formulario para volver -->
    <form action="editar_parcela.php" method="POST">
        <input type="submit" name="volver" value="Volver">
    </form>
</body>
</html>