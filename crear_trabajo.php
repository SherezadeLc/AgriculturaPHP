<!DOCTYPE html>
<?php
session_start();

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "agricultura")
        or die("No se puede conectar con el servidor");

// Verificar si el usuario está autenticado y es cliente
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'cliente') {
    die("Acceso denegado. Solo los clientes pueden acceder.");
}
if (!isset($_SESSION['dni'])) {
    die("Error: No se encontró el DNI del cliente en la sesión.");
}

$dni_cliente = $_SESSION['dni'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['crear_trabajo'])) {
    @$id_parcela = $_POST['id_parcela'];
    @$id_maquina = $_POST['id_maquina'];
    @$tipo_trabajo = $_POST['tipo_trabajo'];

    // Verificar si la parcela ya tiene un trabajo pendiente
    $verificarTrabajo = "SELECT id_trabajo FROM trabajo WHERE id_parcela = '$id_parcela' AND estado != 'completado'";
    $resultadoTrabajo = mysqli_query($conexion, $verificarTrabajo);

    if (mysqli_num_rows($resultadoTrabajo) > 0) {
        echo "<p>Error: La parcela ya tiene un trabajo pendiente.</p>";
    } else {
        // Verificar que la parcela pertenece al cliente
        $verificarParcela = "SELECT p.id_parcela 
                             FROM parcela p
                             JOIN cliente c ON c.id_catastro = p.id_catastro
                             WHERE p.id_parcela = '$id_parcela' AND c.dni = '$dni_cliente'";
        $resultadoVerificar = mysqli_query($conexion, $verificarParcela);

        if (mysqli_num_rows($resultadoVerificar) > 0) {
            // Insertar el trabajo
            $consulta = "INSERT INTO trabajo (id_parcela, id_maquina, tipo_trabajo, estado) 
                         VALUES ('$id_parcela', '$id_maquina', '$tipo_trabajo', 'pendiente')";

            if (mysqli_query($conexion, $consulta)) {
                echo "<p>Trabajo creado correctamente.</p>";
            } else {
                echo "<p>Error al crear el trabajo: " . mysqli_error($conexion) . "</p>";
            }
        } else {
            echo "<p>Error: La parcela seleccionada no pertenece a usted.</p>";
        }
    }
}

// Obtener parcelas del cliente
$consultaParcelas = "SELECT p.id_parcela, p.numero_parcela 
                     FROM parcela p
                     JOIN cliente c ON c.id_catastro = p.id_catastro
                     WHERE c.dni = '$dni_cliente'";
$resultadoParcelas = mysqli_query($conexion, $consultaParcelas);

// Obtener máquinas disponibles (solo las que están libres)
$consultaMaquinas = "SELECT id_maquina, tipo_maquina FROM maquina WHERE estado = 'libre'";
$resultadoMaquinas = mysqli_query($conexion, $consultaMaquinas);

// Obtener trabajos creados por el cliente
$consultaTrabajos = "SELECT t.id_trabajo, p.numero_parcela, m.tipo_maquina, t.tipo_trabajo, t.estado 
                     FROM trabajo t
                     JOIN parcela p ON t.id_parcela = p.id_parcela
                     JOIN maquina m ON t.id_maquina = m.id_maquina
                     JOIN cliente c ON c.id_catastro = p.id_catastro
                     WHERE c.dni = '$dni_cliente'";
$resultadoTrabajos = mysqli_query($conexion, $consultaTrabajos);
?>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Trabajo</title>
    </head>
    <body>
        <h2>Crear Trabajo</h2>

        <form method="POST" action="">
            <label for="id_parcela">Parcela:</label>
            <select name="id_parcela" id="id_parcela" required>
                <option value="">Seleccione una parcela</option>
                <?php while ($parcela = mysqli_fetch_assoc($resultadoParcelas)) { ?>
                    <option value="<?php echo $parcela['id_parcela']; ?>">
                        Parcela #<?php echo $parcela['numero_parcela']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="id_maquina">Máquina:</label>
            <select name="id_maquina" id="id_maquina" required>
                <option value="">Seleccione una máquina</option>
                <?php while ($maquina = mysqli_fetch_assoc($resultadoMaquinas)) { ?>
                    <option value="<?php echo $maquina['id_maquina']; ?>">
                        <?php echo $maquina['tipo_maquina']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="tipo_trabajo">Tipo de Trabajo:</label>
            <select name="tipo_trabajo" id="tipo_trabajo" required>
                <option value="">Seleccione un tipo de trabajo</option>
                <option value="Labrar">Labrar</option>
                <option value="Sembrar">Sembrar</option>
                <option value="Regar">Regar</option>
                <option value="Fertilizar">Fertilizar</option>
                <option value="Cosechar">Cosechar</option>
                <option value="Desherbar">Desherbar</option>
                <option value="Arar">Arar</option>
                <option value="Roturar">Roturar</option>
            </select>

            <button type="submit" name="crear_trabajo">Crear Trabajo</button>
        </form>

        <h2>Trabajos Creados</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Trabajo</th>
                    <th>Parcela</th>
                    <th>Tipo de Máquina</th>
                    <th>Tipo de Trabajo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($trabajo = mysqli_fetch_assoc($resultadoTrabajos)) { ?>
                    <tr>
                        <td><?php echo $trabajo['id_trabajo']; ?></td>
                        <td>Parcela #<?php echo $trabajo['numero_parcela']; ?></td>
                        <td><?php echo $trabajo['tipo_maquina']; ?></td>
                        <td><?php echo $trabajo['tipo_trabajo']; ?></td>
                        <td><?php echo $trabajo['estado']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <br>
        <form action="menu.php" method="POST">
            <input type="submit" name="volver" value="Volver"><br>
        </form>
    </body>
</html>

<?php
// Cerrar conexión
mysqli_close($conexion);
?>
