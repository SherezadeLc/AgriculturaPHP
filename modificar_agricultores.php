<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modificar Agricultores</title>
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
        h1 {
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
        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #2e7d32;
            color: white;
        }
        td form {
            margin: 0;
        }
        td input[type="submit"] {
            width: auto;
            margin: 5px;
            padding: 5px 10px;
        }
        .back-form {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Modificar Agricultores</h2>
        <?php
        // Conectar con la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
            or die("No se puede conectar con el servidor");

        // Verifica si se envió el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
            $id = $_POST['dni'];  // Corregido: ahora usa el campo correcto
            $nuevoNombre = $_POST['nombre'];
            $nuevaContrasena = $_POST['contrasena'];

            // Evitar inyección SQL
            $id = mysqli_real_escape_string($conexion, $id);
            $nuevoNombre = mysqli_real_escape_string($conexion, $nuevoNombre);
            $nuevaContrasena = mysqli_real_escape_string($conexion, $nuevaContrasena);

            // Consulta para actualizar
            $consultaActualizar = "UPDATE agricultor SET Nombre='$nuevoNombre', contrasena='$nuevaContrasena' WHERE dni='$id'";

            if (mysqli_query($conexion, $consultaActualizar)) {
                echo "<p>El agricultor ha sido modificado correctamente.</p>";
            } else {
                echo "<p>Error al modificar el agricultor: " . mysqli_error($conexion) . "</p>";
            }
        }

        // Obtener la lista de agricultores
        $consulta = "SELECT dni, Nombre FROM agricultor";
        $resultado = mysqli_query($conexion, $consulta);

        if (mysqli_num_rows($resultado) > 0) {
        ?>
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
    </div>
</body>
</html>
