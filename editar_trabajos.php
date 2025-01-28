<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Trabajos</title>
    </head>
    <body>
        <?php
        // Conectar con el servidor de base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");

        // Verificar si el usuario está autenticado y es administrador
        if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'administrador') {
            die("Acceso denegado. Solo los administradores pueden acceder.");
        }

        // Procesar la actualización si el formulario fue enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_trabajo'])) {
            @$id_trabajo = $_POST['id_trabajo']; // ID del trabajo
            @$nombre = $_POST['nombre']; 
            @$descripcion = $_POST['descripcion'];
            @$estado = $_POST['estado'];

            // Consulta SQL para actualizar los datos
            $consulta = "UPDATE trabajos SET nombre = '$nombre', descripcion = '$descripcion', estado = '$estado' WHERE id_trabajo = $id_trabajo";

            // Ejecutamos la consulta
            if (mysqli_query($conexion, $consulta)) {
                echo "Trabajo actualizado correctamente.";
            } else {
                echo "Error al actualizar: " . mysqli_error($conexion);
            }
        }

        // Obtener la lista de trabajos
        $consulta = "SELECT * FROM trabajo";
        $resultado = mysqli_query($conexion, $consulta);
        ?>

        <h2>Editar Trabajos</h2>

        <?php if (mysqli_num_rows($resultado) > 0) { ?>
            <form method="POST" action="editar_trabajos.php">
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['id_trabajo']}</td>";
                        echo "<td><input type='text' name='nombre' value='{$row['nombre']}'></td>";
                        echo "<td><input type='text' name='descripcion' value='{$row['descripcion']}'></td>";
                        echo "<td>
                                <select name='estado'>
                                    <option value='pendiente' " . ($row['estado'] == 'pendiente' ? 'selected' : '') . ">Pendiente</option>
                                    <option value='en progreso' " . ($row['estado'] == 'en progreso' ? 'selected' : '') . ">En Progreso</option>
                                    <option value='completado' " . ($row['estado'] == 'completado' ? 'selected' : '') . ">Completado</option>
                                </select>
                              </td>";
                        echo "<td>
                                <input type='hidden' name='id_trabajo' value='{$row['id_trabajo']}'>
                                <input type='submit' name='editar_trabajo' value='Actualizar'>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </form>
        <?php } ?>

        <?php if (mysqli_num_rows($resultado) == 0) { ?>
            <p>No hay trabajos registrados.</p>
        <?php } ?>

        <br>
        <a href="menu.php">Volver al menú</a>

    </body>
    <?php
    // Cerrar la conexión al final del script
    mysqli_close($conexion);
    ?>
</html>

