<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>

        <div class="registro-container">
            <h1>Registro</h1>
            <form method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>

                <label>Apellidos:</label>
                <input type="text" name="apellidos" required>

                <label>DNI:</label>
                <input type="text" name="dni" required>

                <label>Id Catastro:</label>
                <input type="text" name="id_catastro" required>

                <label>Número Parcela:</label>
                <input type="text" name="numero_parcela" required>

                <label>Latitud:</label>
                <input type="text" name="latitud" required>

                <label>Longitud:</label>
                <input type="text" name="longitud" required>

                <label>Contraseña:</label>
                <input type="password" name="password" required>

                <input type="submit" name="enviar" value="Registrar">
            </form>

            <hr>
            <div class="login-container">
                <p>¿Ya te has registrado?</p>
                <a href="login.php"><button class="login-button">Iniciar sesión</button></a>
            </div>

            <?php
            // Conexión a la base de datos
            $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                    or die("No se puede conectar con el servidor o seleccionar la base de datos");

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $dni = $_POST['dni'];
                $id_catastro = $_POST['id_catastro'];
                $contrasena = $_POST['password'];
                $numero_parcela = $_POST['numero_parcela'];
                $latitud = $_POST['latitud'];
                $longitud = $_POST['longitud'];

                // Iniciar una transacción para evitar inconsistencias
                mysqli_begin_transaction($conexion);

                try {
                    // Insertar el cliente solo si no existe el id_catastro
                    $verificar_cliente = "SELECT id_catastro FROM cliente WHERE id_catastro = '$id_catastro'";
                    $resultado_cliente = mysqli_query($conexion, $verificar_cliente);

                    if (mysqli_num_rows($resultado_cliente) === 0) {
                        $insertar_cliente = "INSERT INTO cliente (dni, nombre, contrasena, id_catastro) 
                                     VALUES ('$dni', '$nombre', '$contrasena', '$id_catastro')";
                        mysqli_query($conexion, $insertar_cliente);
                    }

                    // Insertar la parcela solo si no existe el numero_parcela
                    $verificar_parcela = "SELECT id_parcela FROM parcela WHERE numero_parcela = '$numero_parcela'";
                    $resultado_parcela = mysqli_query($conexion, $verificar_parcela);

                    if (mysqli_num_rows($resultado_parcela) === 0) {
                        $insertar_parcela = "INSERT INTO parcela (id_catastro, numero_parcela) 
                                     VALUES ('$id_catastro', '$numero_parcela')";
                        mysqli_query($conexion, $insertar_parcela);
                    }

                    // Insertar los puntos solo si no existe un registro con la misma latitud y longitud
                    $verificar_punto = "SELECT id_punto FROM puntos WHERE latitud = '$latitud' AND longitud = '$longitud'";
                    $resultado_punto = mysqli_query($conexion, $verificar_punto);

                    if (mysqli_num_rows($resultado_punto) === 0) {
                        $insertar_punto = "INSERT INTO puntos (numero_parcela, latitud, longitud) 
                                   VALUES ('$numero_parcela', '$latitud', '$longitud')";
                        mysqli_query($conexion, $insertar_punto);
                    }

                    // Confirmar la transacción
                    mysqli_commit($conexion);
                    echo "<p class='success-message'>Registro completado con éxito.</p>";
                } catch (Exception $e) {
                    // Revertir la transacción en caso de error
                    mysqli_rollback($conexion);
                    echo "<p class='error-message'>Error al registrar: " . $e->getMessage() . "</p>";
                }
            }

            // Cerrar la conexión
            mysqli_close($conexion);
            ?>
        </div>

    </body>
</html>
