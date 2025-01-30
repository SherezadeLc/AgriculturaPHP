<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
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
                width: 30%;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h1 {
                color: #2e7d32;
            }

            label {
                display: block;
                margin: 10px 0 5px;
                color: #333;
                font-size: 16px;
            }

            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            input[type="submit"], .boton-registro {
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

            input[type="submit"]:hover, .boton-registro:hover {
                background-color: #1b5e20;
            }

            .error {
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
        <div class="contenedor">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <h1>Login</h1>
                <label>DNI:</label>
                <input type="text" name="dni" required>
                <label>Contraseña:</label>
                <input type="password" name="password" required>
                <input type="submit" name="enviar" value="Enviar">
                <p>¿No estás registrado?</p>
                <a href="registro.php"><input type="button" class="boton-registro" name="Registrar" value="Registrar"/></a>
            </form>

            <?php
            if (isset($_REQUEST['salir'])) {
                unset($_SESSION['nombre']);
                session_destroy();
            }

            if (isset($_POST['enviar'])) {
                $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                        or die("No se puede conectar con el servidor o seleccionar la base de datos");

                $dni = $_POST['dni'];
                $contrasena = $_POST['password'];

                if (!empty($dni) && !empty($contrasena)) {
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

                    echo "<p class='error'>Error: usuario o contraseña incorrecto.</p>";
                } else {
                    echo "<p class='error'>Completa todos los campos por favor.</p>";
                }
            }
            ?>
        </div>
    </body>
</html>