<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Agregar Maquina</title>
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
                width: 50%;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h1, h2 {
                color: #2e7d32;
            }

            input[type="submit"] {
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

            input[type="submit"]:hover {
                background-color: #1b5e20;
            }

            select {
                padding: 10px;
                font-size: 16px;
                width: 100%;
                border-radius: 5px;
                border: 1px solid #ccc;
                margin-bottom: 15px;
            }

            button {
                background-color: #2e7d32;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
                transition: background-color 0.3s;
            }

            button:hover {
                background-color: #1b5e20;
            }

            .boton-volver {
                margin-top: 20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="contenedor">
            <form method="POST" action="">
                <h2>Agregar Maquina</h2>
                <label for="tipo_maquina">Tipo maquina:</label><br>
                <select name="tipo_maquina" id="tipo_maquina" required> 
                    <option value="">Seleccione un tipo de maquina</option>
                    <option value="Graduadas y cultivadores">Graduadas y cultivadores</option>
                    <option value="Sembradoras de precisión">Sembradoras de precisión</option>
                    <option value="Aspersores">Aspersores</option>
                    <option value="Abonadoras">Abonadoras</option>
                    <option value="Cosechadora">Cosechadora</option>
                    <option value="Desbrozadora">Desbrozadora</option>
                    <option value="Arado">Arado</option>
                    <option value="Subsoladores">Subsoladores</option>
                </select>
                <br>

                <button type="submit" name="agregar_maquina">Agregar Maquina</button><br><br>
            </form>
            <?php
            // Conectar con el servidor de base de datos
            $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                    or die("No se puede conectar con el servidor");
            if (isset($_POST['agregar_maquina'])) {
                // Aquí cogemos la información que se ha recogido del formulario
                $tipo_maquina = $_POST['tipo_maquina'];

                // Hacemos la consulta para insertar la información en la tabla "maquina"
                $insertar_maquina = "INSERT INTO maquina (tipo_maquina) VALUES ('$tipo_maquina')";
                // Aquí hacemos la conexión a la base de datos
                if (mysqli_query($conexion, $insertar_maquina)) {
                    echo "Insertado en maquina";
                } else {
                    echo "Error al insertar en maquina";
                }
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);
            ?>
            <form action="editar_maquinas.php" method="POST">
                <input type="submit" name="volver" value="Volver">
            </form>
        </div>
    </body>
</html>
