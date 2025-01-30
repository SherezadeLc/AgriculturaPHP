<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Añadir Agricultor</title>
        <style>
            /* Estilo general del cuerpo */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f7f6;
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
                max-width: 500px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                box-sizing: border-box;
            }

            /* Títulos */
            h2 {
                color: #388e3c;
                text-align: center;
            }

            /* Estilo para las etiquetas de los campos */
            label {
                font-size: 16px;
                color: #388e3c;
                margin-bottom: 5px;
                display: inline-block;
            }

            /* Estilo para los inputs de tipo texto y contraseña */
            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 5px;
                border: 1px solid #ccc;
                font-size: 14px;
            }

            /* Estilo para el botón de añadir */
            input[type="submit"] {
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

            /* Hover para el botón de añadir */
            input[type="submit"]:hover {
                background-color: #2c6e29;
            }

            /* Estilo para el botón de volver */
            a input[type="submit"] {
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

            /* Hover para el botón de volver */
            a input[type="submit"]:hover {
                background-color: #2c6e29;
            }

            /* Estilo para los mensajes de error o éxito */
            p {
                text-align: center;
                color: #388e3c;
                font-weight: bold;
            }

            /* Estilo de los mensajes de error */
            p.error {
                color: #d32f2f;
            }
        </style>

    </head>
    <body>
        <div class="contenedor">
            <h2>Añadir Agricultores</h2>
            <!-- Formulario para añadir un agricultor -->
            <form action="añadir_agricultores.php" method="POST">
                Nombre: <input type="text" id="nombre" name="nombre" required><br><br>
                DNI: <input type="text" id="dni" name="dni" required><br><br>
                Contraseña: <input type="password" id="contrasena" name="contrasena" required><br><br>
                <input type="submit" name="añadir" value="Añadir"><br><br>
            </form>


            <?php
            //
            // Conectar con la base de datos//
            $conexion = mysqli_connect("localhost", "root", "", "agricultura");

            if (!$conexion) {
                die("No se puede conectar con la base de datos");
            }

            // Verificar si se envió el formulario
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['añadir'])) {
                // Obtener los datos del formulario
                @$nombre = $_POST['nombre'];
                @$dni = $_POST['dni'];
                @$contrasena = $_POST['contrasena'];

                // Insertar los datos en la base de datos
                $consulta = "INSERT INTO agricultor (nombre, dni, contrasena) VALUES ('$nombre', '$dni', '$contrasena')";
                $resultado = mysqli_query($conexion, $consulta);

                // Verificar si la inserción fue exitosa
                if ($resultado) {
                    echo "<p>¡Agricultor añadido correctamente!</p>";
                } else {
                    echo "<p>Error al añadir el agricultor: " . mysqli_error($conexion) . "</p>";
                }
            }

            // Cerrar la conexión con la base de datos
            mysqli_close($conexion);
            ?>

            <a href="editar_agricultores.php"> <input type="submit" name="volver" value="Volver"></a><br>
        </div>
    </body>
</html>

