<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Login - Agricultura</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            /* Estilos generales */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            /* Contenedor del formulario */
            .login-container {
                background: #ffffff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                text-align: center;
                width: 100%;
                max-width: 400px;
            }

            /* Título */
            h1 {
                color: #2c6e49;
                font-size: 24px;
                margin-bottom: 20px;
            }

            /* Etiquetas y campos de entrada */
            label {
                font-size: 16px;
                display: block;
                margin: 10px 0 5px;
                font-weight: bold;
                color: #555;
            }

            input[type="text"],
            input[type="password"] {
                width: calc(100% - 20px);
                padding: 10px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 5px;
                outline: none;
                transition: border 0.3s ease-in-out;
            }

            input[type="text"]:focus,
            input[type="password"]:focus {
                border-color: #2c6e49;
            }

            /* Botones */
            input[type="submit"], .login-button {
                background: #2c6e49;
                color: white;
                border: none;
                padding: 12px 20px;
                font-size: 16px;
                border-radius: 5px;
                cursor: pointer;
                transition: background 0.3s ease-in-out;
                display: inline-block;
                width: 100%;
                margin-top: 10px;
                text-decoration: none;
            }

            input[type="submit"]:hover, .login-button:hover {
                background: #3a945b;
            }

            /* Mensaje de error */
            .error-message {
                color: red;
                font-size: 16px;
                margin-top: 10px;
            }

            /* Línea divisoria */
            hr {
                margin: 20px 0;
                border: none;
                border-top: 1px solid #ddd;
            }

            /* Link de registro */
            .register-container {
                font-size: 14px;
            }

            .register-container a {
                text-decoration: none;
                color: #2c6e49;
                font-weight: bold;
            }

            .register-container a:hover {
                text-decoration: underline;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .login-container {
                    width: 90%;
                }
            }
        </style>
    </head>
    <body>

        <div class="login-container">
            <h1>Login</h1>
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <label for="dni">DNI:</label>
                <input type="text" name="dni" required>

                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>

                <input type="submit" name="enviar" value="Iniciar sesión">
            </form>

            <hr>
            <div class="register-container">
                <p>¿No estás registrado?</p>
                <a href="registro.php"><button class="login-button">Registrarse</button></a>
            </div>

            <?php
            // Verificar si se ha cerrado la sesión anterior
            if (isset($_REQUEST['salir'])) {
                unset($_SESSION['nombre']);
                session_destroy();
            }

            if (isset($_POST['enviar'])) {
                // Conexión a la base de datos
                $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                        or die("No se puede conectar con el servidor o seleccionar la base de datos");

                $dni = $_POST['dni'];
                $contrasena = $_POST['password'];

                if (!empty($dni) && !empty($contrasena)) {
                    // Cliente
                    $consulta_cliente = "SELECT * FROM cliente WHERE dni='$dni' AND contrasena='$contrasena'";
                    $resultado_cliente = mysqli_query($conexion, $consulta_cliente);
                    if (mysqli_num_rows($resultado_cliente) > 0) {
                        $datos_cliente = mysqli_fetch_assoc($resultado_cliente);
                        $_SESSION['nombre'] = $datos_cliente['nombre'];
                        $_SESSION['dni'] = $datos_cliente['dni'];
                        $_SESSION['tipo'] = 'cliente';
                        header("Location: menu.php");
                        exit();
                    }

                    // Agricultor
                    $consulta_agricultor = "SELECT * FROM agricultor WHERE dni='$dni' AND contrasena='$contrasena'";
                    $resultado_agricultor = mysqli_query($conexion, $consulta_agricultor);
                    if (mysqli_num_rows($resultado_agricultor) > 0) {
                        $datos_agricultor = mysqli_fetch_assoc($resultado_agricultor);
                        $_SESSION['nombre'] = $datos_agricultor['nombre'];
                        $_SESSION['dni'] = $datos_agricultor['dni'];
                        $_SESSION['tipo'] = 'agricultor';
                        header("Location: menu.php");
                        exit();
                    }

                    // Administrador
                    $consulta_administrador = "SELECT * FROM administrador WHERE dni='$dni' AND contrasena='$contrasena'";
                    $resultado_administrador = mysqli_query($conexion, $consulta_administrador);
                    if (mysqli_num_rows($resultado_administrador) > 0) {
                        $datos_administrador = mysqli_fetch_assoc($resultado_administrador);
                        $_SESSION['nombre'] = $datos_administrador['nombre'];
                        $_SESSION['dni'] = $datos_administrador['dni'];
                        $_SESSION['tipo'] = 'administrador';
                        header("Location: menu.php");
                        exit();
                    }

                    // Si no se encuentra en ninguna tabla
                    echo "<p class='error-message'>Error: usuario o contraseña incorrectos.</p>";
                } else {
                    echo "<p class='error-message'>Completa todos los campos por favor.</p>";
                }
            }
            ?>
        </div>

    </body>
</html>
