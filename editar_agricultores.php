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
        // Conectar con el servidor de base de datos
        $conexion = mysqli_connect("localhost", "root", "","agricultura")
                or die("No se puede conectar con el servidor");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            @$id = $_POST['id_agricultor'];
            @$nombre = $_POST['nombre'];
            @$dni = $_POST['dni'];
            @$estado = $_POST['estado'];
            @$contrasena=$_POST['contrasena'];

            $consulta = "UPDATE agricultores SET nombre='$nombre', telefono='$telefono', email='$email' WHERE id='$id'";
            if (mysqli_query($conexion, $query_update)) {
                echo "<p>El agricultor ha sido actualizado correctamente.</p>";
            } else {
                echo "<p>Error al actualizar el agricultor: " . mysqli_error($conexion) . "</p>";
            }
        }
        ?>
        
        <form action="menu.php" method="POST">
            <input type="submit" name="volver" value="Volver"><br>
        </form><br>
        <?php
        // Cerrar conexión
        mysqli_close($conexion);
        ?>
    </body>

</html>
