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
            <hr>
            <p>¿Ya te has registrado?</p>
            <a href="login.php"><input type="button" class="login-button" name="login" value="Login"/></a>
            
        </form>
        
        <?php
        // put your code here
        //DNI, nombre, contrasena, id_catastro
        
        //conexion a la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
        or die("No se puede conectar con el servidor o seleccionar la base de datos");
        
        //s
        // Procesar el formulario solo si se presionó el botón "Registrar"
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) 
        {
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $dni = $_POST['dni'];
            $id_catastro= $_POST['id_catastro'];
        }
        
        
        
        
        ?>
    </body>
</html>
