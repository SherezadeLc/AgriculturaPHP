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

            /* Estilo para los inputs de tipo password */
            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 5px;
                border: 1px solid #ccc;
                font-size: 14px;
            }

            /* Estilo para el botón de enviar */
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

            /* Hover para el botón de enviar */
            input[type="submit"]:hover {
                background-color: #2c6e29;
            }

            /* Estilo para el botón de volver */
            input[type="button"] {
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
            input[type="button"]:hover {
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
            <h2>Cambiar Contraseña</h2>
            <form action="cambiar_contraseña.php" method="POST">
                Antigua Contraseña: <input type="password" name="antigua_contrasena" required><br><br>
                Nueva Contraseña: <input type="password" name="nueva_contrasena" required><br><br>
                Confirmar Nueva Contraseña: <input type="password" name="confirmar_contrasena" required><br><br>
                <input type="submit" name="cambiar" value="Cambiar Contraseña"><br><br>
            </form>
            <a href="menu.php"><input type="button" value="Volver al menú"></a><br><br>
        </div>
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





