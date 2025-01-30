<?php
session_start();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Añadir Parcelas</title>
        <style>
            /* Estilo general del cuerpo */
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

            /* Contenedor principal */
            .contenedor {
                background-color: #ffffff;
                padding: 30px;
                width: 80%;
                max-width: 600px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                box-sizing: border-box;
            }

            /* Títulos */
            h2 {
                color: #388e3c;
                margin-bottom: 20px;
            }

            /* Estilo para los labels */
            label {
                font-size: 16px;
                font-weight: bold;
                color: #333;
            }

            /* Estilo para los campos de entrada */
            input[type="text"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            /* Estilo para el botón de enviar */
            button[type="submit"] {
                background-color: #388e3c;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
                transition: background-color 0.3s;
            }

            /* Hover para el botón de agregar */
            button[type="submit"]:hover {
                background-color: #2c6e29;
            }

            /* Estilo para los mensajes de error y éxito */
            .mensaje {
                font-size: 14px;
                color: #d32f2f;
                font-weight: bold;
                margin-top: 10px;
            }

            .mensaje-exito {
                color: #388e3c;
            }

            /* Formulario de volver */
            form input[type="submit"] {
                background-color: #388e3c;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
                margin-top: 20px;
            }

            /* Hover para el botón de volver */
            form input[type="submit"]:hover {
                background-color: #2c6e29;
            }
        </style>

    </head>
    <body>
        <div class="contenedor">
            <form method="POST" action="">



                <label for="id_catastro">Numero Catastro:</label><br>
                <input type="text" name="id_catastro" id="id_catastro" required><br><br>

                <label for="numero_parcela">Numero Parcela:</label><br>
                <input type="text" name="numero_parcela" id="numero_parcela" required><br><br>

                <label for="latitud">Latitud:</label><br>
                <input type="text" name="latitud" id="latitud" required><br><br>

                <label for="longitud">Longitud:</label><br>
                <input type="text" name="longitud" id="longitud" required><br><br>

                <button type="submit" name="agregar_parcela">Agregar Parcela</button><br><br>

            </form>
            <?php
            
            // Conexión a la base de datos
            $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                    or die("No se puede conectar con el servidor");


            // Verificar si se envió el formulario
            if (isset($_POST['agregar_parcela'])) {
                //aqui cogemos la informacion que se ha recogido del formulario
                $id_catastro = $_POST['id_catastro'];
                $numero_parcela = $_POST['numero_parcela'];
                $latitud = $_POST['latitud'];
                $longitud = $_POST['longitud'];

                //aqui hacemos la consulta de introducir la informacion a la tabla parcela con la informacion 
                $insertar_parcela = "INSERT INTO parcela (id_catastro, numero_parcela) VALUES ('$id_catastro', '$numero_parcela')";
                //aqui hacemos la conexion a la base de datos
                if (mysqli_query($conexion, $insertar_parcela)) {
                    echo "Insertado en parcela";
                } else {
                    echo "Error al insertar en parcela";
                }
                //aqui hacemos la consulta de introducir la informacion a la tabla puntos con la informacion 
                $insertar_punto = "INSERT INTO puntos ( numero_parcela, latitud, longitud) VALUES ('$numero_parcela','$latitud','$longitud')";
                //aqui hacemos la conexion a la base de datos
                if (mysqli_query($conexion, $insertar_punto)) {
                    echo "Insertado en puntos";
                } else {
                    echo "Error al insertar en puntos";
                }



                //cogo la informacion que ya se ha guardado en la tabla puntos
                $selecionar = "SELECT * FROM puntos WHERE numero_parcela='$numero_parcela'";
                //aqui hago la conexion a la base de datos 
                $resultadoReferencias = mysqli_query($conexion, $selecionar);
                //aqui miro si hay alguna coincidencia
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
                    $cliente = $_SESSION['dni'];
                    //aqui hacemos la consulta a la base de datos para intrp¡oducir la informacion que se ha sacado antes de las tablas de parcelas y puntos y lo hemos metido en la 
                    //tabla puntos_paracela
                    $insertar_puntos_parcela = "INSERT INTO puntos_parcela (id_punto,id_parcela,dni_cliente) VALUES ('$id_punto','$id_parcela','$cliente')";
                    if (mysqli_query($conexion, $insertar_puntos_parcela)) {
                        echo "Insertado en puntos_parcela";
                    } else {
                        echo "Error al insertar en puntos_parcela";
                    }
                } else {
                    echo "Error no se ha encontrado ninguna coincidencia";
                }
                //
                //
            }
            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);
            ?> 
            <form action="editar_parcela.php" method="POST">
                <input type="submit" name="volver" value="Volver">
            </form>
        </div>
    </body>
</html>
