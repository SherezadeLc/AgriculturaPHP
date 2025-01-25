<?php
session_start();
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <form name="form" action="" method="POST" enctype="multipart/form-data">
            <h1>Loggin</h1>
            <label>DNI:</label>
            <input type="text" name="dni" required>
            <br><br>
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" name="enviar" value="Enviar">
            <br>
            <hr>
            <p>¿No estas registrado?</p>
            <a href="registro.php"><input type="button" class="login-button" name="Registrar" value="Registrar"/></a>

        </form>

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
                    $_SESSION['tipo']='cliente';
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
                     $_SESSION['tipo']='agricultor';
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
                     $_SESSION['tipo']='administrador';
                    header("Location: menu.php");
                    exit();
                }

                // Si no se encuentra en ninguna tabla
                echo "Error: usuario o contraseña incorrecto.";
            } else {
                echo "Completa todos los campos por favor.";
            }
        }
        ?>
    </body>
</html>
