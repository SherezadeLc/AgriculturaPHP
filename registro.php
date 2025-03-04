<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                min-height: 100vh;
            }

            .container {
                width: 30%;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h1 {
                color: #2e7d32;
                margin-top: 20px;
                font-size: 24px;
            }

            label {
                display: block;
                margin: 15px 0 5px;
                color: #333;
                font-size: 16px;
            }

            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
                box-sizing: border-box;
            }

            input[type="submit"], .login-button {
                background-color: #2e7d32;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
                transition: background-color 0.3s;
                margin-top: 15px;
            }

            input[type="submit"]:hover, .login-button:hover {
                background-color: #1b5e20;
            }

            .alert {
                color: red;
                font-weight: bold;
                text-align: center;
                margin-top: 10px;
            }

            a {
                text-decoration: none;
                display: block;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container" >
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
            <p>¿Ya te has registrado?</p>
            <a href="login.php"><button class="login-button">Iniciar sesión</button></a>
            <?php
            // Conexión a la base de datos
            $conexion = mysqli_connect("localhost", "root", "", "agricultura") or die("No se puede conectar con el servidor o seleccionar la base de datos");
//
            //
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
                @$nombre = $_POST['nombre'];
                @$apellidos = $_POST['apellidos'];
                @$dni = $_POST['dni'];
                @$id_catastro = $_POST['id_catastro'];
                @$contrasena = $_POST['contrasena'];
                @$numero_parcela = $_POST['numero_parcela'];
                @$latitud = $_POST['latitud'];
                @$longitud = $_POST['longitud'];

                // Iniciar una transacción para evitar inconsistencias
                mysqli_begin_transaction($conexion);

                try {
                   

                    // Verificar si el cliente ya existe en la base de datos
                    $consulta_cliente = "SELECT id_catastro FROM cliente WHERE id_catastro = '$id_catastro'";
                    $resultado_cliente = mysqli_query($conexion, $consulta_cliente);

                    if (mysqli_num_rows($resultado_cliente) > 0) {
                        throw new Exception("Error: El cliente ya está registrado.");
                    }

                    // Verificar si la parcela ya existe en la base de datos
                    $consulta_parcela = "SELECT id_parcela FROM parcela WHERE numero_parcela = '$numero_parcela'";
                    $resultado_parcela = mysqli_query($conexion, $consulta_parcela);

                    if (mysqli_num_rows($resultado_parcela) > 0) {
                        throw new Exception("Error: La parcela ya está registrada.");
                    }

                    // Verificar si los puntos ya existen en la base de datos
                    $consulta_punto = "SELECT id_punto FROM puntos WHERE latitud = '$latitud' AND longitud = '$longitud'";
                    $resultado_punto = mysqli_query($conexion, $consulta_punto);

                    if (mysqli_num_rows($resultado_punto) > 0) {
                        throw new Exception("Error: Las coordenadas ya están registradas.");
                    }

                    // Si no hay errores, insertar el cliente
                    $insertar_cliente = "INSERT INTO cliente (dni, nombre, contrasena, id_catastro) 
                         VALUES ('$dni', '$nombre', '$contrasena', '$id_catastro')";
                    mysqli_query($conexion, $insertar_cliente);

                    // Insertar la parcela
                    $insertar_parcela = "INSERT INTO parcela (id_catastro, numero_parcela) 
                         VALUES ('$id_catastro', '$numero_parcela')";
                    mysqli_query($conexion, $insertar_parcela);

                    // Insertar los puntos
                    $insertar_punto = "INSERT INTO puntos (numero_parcela, latitud, longitud) 
                       VALUES ('$numero_parcela', '$latitud', '$longitud')";
                    mysqli_query($conexion, $insertar_punto);

                    // Confirmar la transacción
                    mysqli_commit($conexion);
                    echo "<p class='success-message'>Registro completado con éxito.</p>";
                } catch (Exception $e) {
                    // Revertir cambios si hay error
                    mysqli_rollback($conexion);
                    echo "<p class='error-message'>" . $e->getMessage() . "</p>";
                }
            }

            // Cerrar la conexión
            mysqli_close($conexion);
            ?>
        </div>
    </body>
</html>
