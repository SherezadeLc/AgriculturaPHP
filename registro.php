<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
    </head>
    <body>
        <form method="POST">
            <h1>Registro</h1><br>
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br><br>

            <label>Apellidos:</label>
            <input type="text" name="apellidos" required><br><br>

            <label>DNI:</label>
            <input type="text" name="dni" required><br><br>

            <label>Id_catastro:</label>
            <input type="text" name="id_catastro" required><br><br>

            <label for="numero_parcela">Numero Parcela:</label><br>
            <input type="text" name="numero_parcela" id="numero_parcela" required><br><br>

            <label for="latitud">Latitud:</label><br>
            <input type="text" name="latitud" id="latitud" required><br><br>

            <label for="longitud">Longitud:</label><br>
            <input type="text" name="longitud" id="longitud" required><br><br>

            <label>Contraseña:</label>
            <input type="password" name="password" required><br><br>

            <input type="submit" name="enviar" value="Registrar"><br><hr>
            <p>¿Ya te has registrado?</p>
            <a href="login.php"><input type="button" class="login-button" name="login" value="Login"></a>
        </form>

        <?php
        // Conexión a la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor o seleccionar la base de datos");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
            @$nombre = $_POST['nombre'];
            @$apellidos = $_POST['apellidos'];
            @$dni = $_POST['dni'];
            @$id_catastro = $_POST['id_catastro'];
            @$contrasena = $_POST['password'];
            @$numero_parcela = $_POST['numero_parcela'];
            @$latitud = $_POST['latitud'];
            @$longitud = $_POST['longitud'];

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

                // Obtener el id_parcela de la parcela recién insertada o existente
                $consulta_parcela = "SELECT id_parcela FROM parcela WHERE numero_parcela = '$numero_parcela'";
                $resultado_id_parcela = mysqli_query($conexion, $consulta_parcela);
                $fila_parcela = mysqli_fetch_assoc($resultado_id_parcela);
                $id_parcela = $fila_parcela['id_parcela'];

                // Insertar los puntos solo si no existe un registro con la misma latitud y longitud
                $verificar_punto = "SELECT id_punto FROM puntos WHERE latitud = '$latitud' AND longitud = '$longitud'";
                $resultado_punto = mysqli_query($conexion, $verificar_punto);

                if (mysqli_num_rows($resultado_punto) === 0) {
                    $insertar_punto = "INSERT INTO puntos (numero_parcela, latitud, longitud) 
                                   VALUES ('$numero_parcela', '$latitud', '$longitud')";
                    mysqli_query($conexion, $insertar_punto);
                }

                // Obtener el id_punto del punto recién insertado o existente
                $consulta_punto = "SELECT id_punto FROM puntos WHERE latitud = '$latitud' AND longitud = '$longitud'";
                $resultado_id_punto = mysqli_query($conexion, $consulta_punto);

                if (mysqli_num_rows($resultado_id_punto) === 0) {
                    throw new Exception("El punto no se pudo insertar correctamente.");
                }

                $fila_punto = mysqli_fetch_assoc($resultado_id_punto);
                $id_punto = $fila_punto['id_punto'];

                // Insertar en puntos_parcela solo si no existe la combinación id_punto y id_parcela
                $verificar_puntos_parcela = "SELECT * FROM puntos_parcela WHERE id_punto = '$id_punto' AND id_parcela = '$id_parcela'";
                $resultado_puntos_parcela = mysqli_query($conexion, $verificar_puntos_parcela);

                if (mysqli_num_rows($resultado_puntos_parcela) === 0) {
                    $insertar_puntos_parcela = "INSERT INTO puntos_parcela (id_punto, id_parcela) 
                                            VALUES ('$id_punto', '$id_parcela')";
                    mysqli_query($conexion, $insertar_puntos_parcela);
                }

                // Confirmar la transacción
                mysqli_commit($conexion);
                echo "Registro completado con éxito.";
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                mysqli_rollback($conexion);
                echo "Error al registrar: " . $e->getMessage();
            }
        }

        // Cerrar la conexión
        mysqli_close($conexion);
        ?>
    </body>
</html>
