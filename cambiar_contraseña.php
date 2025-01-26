<?php
session_start();

// Verificar si el usuario está autenticado y si tiene el rol de agricultor
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'agricultor') {
    echo '<p>Acceso no autorizado.</p>';
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cambiar Contraseña</title>
    </head>
    <body>
        <h2>Cambiar Contraseña</h2>
        <form action="cambiar_contraseña.php" method="POST">
            Antigua Contraseña: <input type="password" name="antigua_contrasena" required><br><br>
            Nueva Contraseña: <input type="password" name="nueva_contrasena" required><br><br>
            Confirmar Nueva Contraseña: <input type="password" name="confirmar_contrasena" required><br><br>
            <input type="submit" name="cambiar" value="Cambiar Contraseña"><br><br>
        </form>
        <a href="menu.php"><input type="button" value="Volver al menú"></a><br><br>
    </body>
</html>

<?php
// Conectar con la base de datos
$conexion = mysqli_connect("localhost", "root", "", "agricultura");

if (!$conexion) {
    die("No se puede conectar con la base de datos");
}

// Verificar si se envió el formulario para cambiar la contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar'])) {
    @$dni_agricultor = $_SESSION['dni']; // ID del agricultor desde la sesión
    @$antigua_contrasena = $_POST['antigua_contrasena'];
    @$nueva_contrasena = $_POST['nueva_contrasena'];
    @$confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Consultar la contraseña actual del agricultor
    $consulta = "SELECT contrasena FROM agricultor WHERE dni = '$dni_agricultor'";
    $resultado = mysqli_query($conexion, $consulta);

    // Verificar si la consulta devuelve algún resultado
    if (mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        $contrasena_actual = $fila['contrasena'];

        // Verificar si la antigua contraseña coincide
        if ($antigua_contrasena === $contrasena_actual) {
            // Verificar si las nuevas contraseñas coinciden
            if ($nueva_contrasena === $confirmar_contrasena) {
                // Actualizar la contraseña
                $consulta_actualizar = "UPDATE agricultor SET contrasena = '$nueva_contrasena' WHERE dni = '$dni_agricultor'";

                if (mysqli_query($conexion, $consulta_actualizar)) {
                    echo "<p>Contraseña cambiada correctamente.</p>";
                } else {
                    echo "<p>Error al cambiar la contraseña: " . mysqli_error($conexion) . "</p>";
                }
            } else {
                echo "<p>Las contraseñas nuevas no coinciden.</p>";
            }
        } else {
            echo "<p>La antigua contraseña es incorrecta.</p>";
        }
    } else {
        echo "<p>Usuario no encontrado.</p>";
    }
}

// Cerrar la conexión con la base de datos
mysqli_close($conexion);
?>





