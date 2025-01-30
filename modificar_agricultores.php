<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <title>Modificar Agricultores</title>
    </head>
    <body>
        <?php
        // put your code here
        // Conecta con el servidor de base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");
        // Verifica si el formulario fue enviado para modificar un agricultor
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
            @$id = $_POST['id_agricultor'];
            @$nuevoNombre = $_POST['nombre'];
            @$nuevaContrasena = $_POST['contrasena'];
            @$consultaActualizar = "UPDATE FROM agricultor SET nombre='$nuevoNombre', contrasena='$nuevaContrasena' WHERE id_agricultor='$id'";
            if (mysqli_query($conexion, $consultaEliminar)) {
                echo "<p>El agricultor ha sido eliminado correctamente.</p>";
            } else {
                echo "<p>Error al eliminar el agricultor: " . mysqli_error($conexion) . "</p>";
            }
        }
        //Obtiene la lista de agricultores
        $consulta = "SELECT dni, Nombre FROM agricultor";
        $resultado = mysqli_query($conexion, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            ?>
            <h2>Modificar Agricultores</h2>
            <form action="" method="POST">
                <!-- Seleccionar agricultor -->
                <label for="dni">Seleccionar Agricultor:</label>
                <select name="dni" id="dni" required>
                    <?php
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        echo "<option value='" . $fila['dni'] . "'>" . $fila['Nombre'] . " (DNI: " . $fila['dni'] . ")</option>";
                    }
                    ?>
                </select><br><br>
                
                <label for="nombre">Nuevo Nombre:</label>
                <input type="text" name="nombre" id="nombre" required><br><br>
                <label for="contrasena">Nueva Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" required><br><br>
                
                <input type="submit" name="modificar" value="Modificar">
            </form>
            <?php
        } else {
            echo "<p>No hay agricultores registrados en la base de datos.</p>";
        }
        // Cierra la conexión a la base de datos
        mysqli_close($conexion);
        ?>
        <form action="editar_agricultores.php" method="POST">
            <input type="submit" name="volver" value="Volver"><br>
        </form>
    </body>
</html>