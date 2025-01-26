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
            <label for="numero_parcela">Numero Parcela:</label><br>
            <input type="text" name="numero_parcela" id="numero_parcela" required><br><br>

            <label for="latitud">Latitud:</label><br>
            <input type="text" name="latitud" id="latitud" required><br><br>

            <label for="longitud">Longitud:</label><br>
            <input type="text" name="longitud" id="longitud" required><br><br>
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
            //aqui cogemos la informacion que me te el usuario en el formulario

            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $dni = $_POST['dni'];
            $id_catastro = $_POST['id_catastro'];
            $contrasena = $_POST['password'];
            $id_catastro = $_POST['id_catastro'];
            $numero_parcela = $_POST['numero_parcela'];
            $latitud = $_POST['latitud'];
            $longitud = $_POST['longitud'];

            // Verifica que los campos obligatorios estén completos
            if ($nombre && $id_catastro) {

                //aqui hacemos la consulta a la base de datos para meter la informacion que ha introducido el usuario en el formulario la metemos en la base de datos 
                $insertar_parcela = "INSERT INTO parcela (id_catastro, numero_parcela) VALUES ('$id_catastro', '$numero_parcela')";

                //aqui hacemos la conexion a la base de datos
                if (mysqli_query($conexion, $insertar_parcela)) {
                    echo "Insertado en parcela";
                }
                //aqui hacemos la consulta a la base de datos para meter la informacion que ha introducido el usuario en el formulario la metemos en la base de datos 
                $insertar_punto = "INSERT INTO puntos ( numero_parcela, latitud, longitud) VALUES ('$numero_parcela','$latitud','$longitud')";
                if (mysqli_query($conexion, $insertar_punto)) {
                    echo "Insertado en puntos";
                }
                //aqui hacemos la consulta a la base de datos para meter la informacion que ha introducido el usuario en el formulario la metemos en la base de datos 
                $selecionar = "SELECT * FROM puntos WHERE numero_parcela='$numero_parcela'";
                //aqui hago la conexion a la base de datos 
                $resultadoReferencias = mysqli_query($conexion, $selecionar);
                //aqui ya esta registrado en la tablas tanto de puntos como parcela y si hay una coinicidencia entra
                if (mysqli_num_rows($resultadoReferencias) > 0) {
                    //aqui guardamos la informacion de puntos_parcela con el id que se ha recogido antes
                    $seleccionar_puntos = "SELECT * FROM parcela WHERE numero_parcela='$numero_parcela'";
                    //aqui hago la conexion a la base de datos
                    $seleccionar_punto = mysqli_query($conexion, $seleccionar_puntos);
                    //si hay una coincidencia entra
                    if (mysqli_num_rows($seleccionar_punto) > 0) {
                        //aqui recogo la informacion de la base de datos en la variable
                        $datos = mysqli_fetch_assoc($seleccionar_punto);
                        //aqui solo cogo la informacion del id_parcela
                        $id_parcela = $datos['id_parcela'];
                        //aqui solo cogo la informacion del id_punto
                        $dato = mysqli_fetch_assoc($resultadoReferencias);
                        $id_punto = $dato['id_punto'];
                    }
                    
                    //aqui hacemos la consulta a la base de datos para intrp¡oducir la informacion que se ha sacado antes de las tablas de parcelas y puntos y lo hemos metido en la 
                    //tabla puntos_paracela
                    $insertar_puntos_parcela = "INSERT INTO puntos_parcela (id_punto,id_parcela) VALUES ('$id_punto','$id_parcela')";
                    //aqui hacemos la conexion a la base de datos
                    if (mysqli_query($conexion, $insertar_puntos_parcela)) {
                        echo "Insertado en puntos_parcela";
                    }
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
        }
        // Cerrar conexión
        $conexion->close();
        ?>
    </body>
</html>
