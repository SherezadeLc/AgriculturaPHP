<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Añadir Agricultor</title>
    </head>
    <body>
        

        <?php
        // Conectar con la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura");

        if (!$conexion) {
            die("No se puede conectar con la base de datos");
        }

        // Verificar si se envió el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
            // Obtener los datos del formulario
            @$nombre = $_POST['nombre'];
            @$dni = $_POST['dni'];
            @$estado = $_POST['estado'];

            // Insertar los datos en la base de datos
            $consulta = "INSERT INTO agricultor (nombre, dni, estado) VALUES ('$nombre', '$dni', '$estado')";
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

        <h2>Añadir Agricultor</h2>
        <!-- Formulario para añadir un agricultor -->
        <form action="añadir_agricultores.php" method="POST">
            Nombre:<input type="text" id="nombre" name="nombre" required><br><br>
            DNI:<input type="text" id="dni" name="dni" required><br><br>
            
            <input type="submit" name="agregar" value="Añadir Agricultor">
        </form>
        <form action="editar_agricultores.php" method="POST">
            <input type="submit" name="volver" value="Volver"><br>
        </form>
    </body>
</html>

