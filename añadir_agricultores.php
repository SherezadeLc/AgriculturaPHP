<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // Conecta con el servidor de base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");
        
        ?>
    </body>
    <?php
// Cerrar la conexión a la base de datos
    mysqli_close($conexion);
    ?>
</html>
