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
        <form method="POST">
            <h1>Registro</h1><br>
            <br>
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
            <br><br>
            <label>Apellidos:</label>
            <input type="text" name="apellidos" required>
            <br><br>
            <label>DNI:</label>
            <input type="text" name="dni" required>
            <br><br>
            <label>Id_catastro:</label>
            <input type="text" name="id_catastro" required>
            <br><br>
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" name="enviar" value="Resgistrar">
            <br>
            <hr>
            <p>¿Ya te has registrado?</p>
            <a href="login.php"><input type="button" class="login-button" name="login" value="Login"/></a>
            
        </form>
        
       <?php
            // Conexión a la base de datos
            $conexion = mysqli_connect("localhost", "root", "", "agricultura")
            or die("No se puede conectar con el servidor o seleccionar la base de datos");

            // Procesar el formulario solo si se presionó el botón "Registrar"
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) 
            {
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $dni = $_POST['dni'];
                $id_catastro = $_POST['id_catastro'];
                $contrasena = $_POST['password'];

                // Verifica que los campos obligatorios estén completos
                if ($nombre && $id_catastro) 
                {
                    // consulta a la base de datos para insertar al ususario en la misma
                    $registrar_usuario = "INSERT INTO cliente (dni, nombre, contrasena, id_catastro) VALUES ('$dni', '$nombre', '$contrasena', '$id_catastro')";
                    //conxion a la base de datos para la insercion de la informacion
                    if (mysqli_query($conexion, $registrar_usuario)) {
                        echo "Registro exitoso.";
                    } else {
                        echo "Error al registrar el usuario: " . mysqli_error($conexion);
                    }
                } else {
                    echo "Por favor, completa todos los campos.";
                }
            }

            // Cerrar conexión
            $conexion->close();
        ?>
    </body>
</html>
