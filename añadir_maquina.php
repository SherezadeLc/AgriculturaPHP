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
        <form method="POST" action="">


            <label for="tipo_maquina">Tipo maquina:</label><br>
            <select name="tipo_maquina" id="tipo_maquina" required> 
                <option value="">Seleccione un tipo de maquina</option>
                <option value="Labrar">Labrar</option>
                <option value="Sembrar">Sembrar</option>
                <option value="Regar">Regar</option>
                <option value="Fertilizar">Fertilizar</option>
                <option value="Cosechar">Cosechar</option>
                <option value="Desherbar">Desherbar</option>
                <option value="Arar">Arar</option>
                <option value="Roturar">Roturar</option>
            </select>
            <br>
            <label for="estado">estado:</label><br>
            <input type="text" name="estado" id="estado" required><br><br>



            <button type="submit" name="agregar_maquina">Agregar Maquina</button><br><br>

        </form>
        <?php
        // Conectar con el servidor de base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");
        if (isset($_POST['agregar_maquina'])) {
            //aqui cogemos la informacion que se ha recogido del formulario
            $tipo_maquina = $_POST['tipo_maquina'];
            $estado = $_POST['estado'];
            //aqui hacemos la consulta de introducir la informacion a la tabla parcela con la informacion 
            $insertar_maquina = "INSERT INTO maquina (tipo_maquina, estado) VALUES ('$tipo_maquina', '$estado')";
            //aqui hacemos la conexion a la base de datos
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
    </body>
</html>
