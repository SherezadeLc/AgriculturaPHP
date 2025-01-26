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
        <h2>Añadir Agricultorer</h2>
        <!-- Formulario para añadir un agricultor -->
        <form action="añadir_agricultores.php" method="POST">
            Nombre: <input type="text" id="nombre" name="nombre" required><br><br>
            DNI: <input type="text" id="dni" name="dni" required><br><br>
            Contraseña: <input type="password" id="contrasena" name="contrasena" required><br><br>
            <input type="submit" name="añadir" value="Añadir"><br><br>
        </form>

        <?php
        // Conectar con la base de datos
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
    </body>
</html>

