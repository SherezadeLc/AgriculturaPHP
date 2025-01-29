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
    @$fecha_inicio = $_POST['fecha_inicio'];
    @$fecha_fin = $_POST['fecha_fin'];

    // Verificar que la parcela pertenece al cliente
    $verificarParcela = "SELECT p.id_parcela FROM parcela p JOIN cliente c ON p.id_cliente = c.id_cliente WHERE p.id_parcela = '$id_parcela' AND c.dni = '$dni_cliente'";
    $resultadoVerificar = mysqli_query($conexion, $verificarParcela);

    if (mysqli_num_rows($resultadoVerificar) > 0) {
        // Insertar el trabajo
        $consulta = "INSERT INTO trabajo (id_parcela, id_maquina, tipo_trabajo, fecha_inicio, fecha_fin) 
                     VALUES ('$id_parcela', '$id_maquina', '$tipo_trabajo', '$fecha_inicio', '$fecha_fin')";

        if (mysqli_query($conexion, $consulta)) {
            echo "<p>Trabajo creado correctamente.</p>";
        } else {
            echo "<p>Error al crear el trabajo: " . mysqli_error($conexion) . "</p>";
        }
    } else {
        echo "<p>Error: La parcela seleccionada no pertenece a usted.</p>";
    }
}

// Obtener parcelas del cliente
$consultaParcelas = "SELECT p.id_parcela, p.numero_parcela FROM parcela p JOIN cliente c ON p.id_cliente = c.id_cliente WHERE c.dni = '$dni_cliente'";
$resultadoParcelas = mysqli_query($conexion, $consultaParcelas);

// Obtener máquinas disponibles
$consultaMaquinas = "SELECT id_maquina, tipo_maquina FROM maquina WHERE estado = 'disponible'";
$resultadoMaquinas = mysqli_query($conexion, $consultaMaquinas);
?>

<!DOCTYPE html>
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
            <?php while ($parcela = mysqli_fetch_assoc($resultadoParcelas)): ?>
                <option value="<?= $parcela['id_parcela'] ?>">
                    Parcela #<?= $parcela['numero_parcela'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="id_maquina">Máquina:</label>
        <select name="id_maquina" id="id_maquina" required>
            <option value="">Seleccione una máquina</option>
            <?php while ($maquina = mysqli_fetch_assoc($resultadoMaquinas)): ?>
                <option value="<?= $maquina['id_maquina'] ?>">
                    <?= $maquina['tipo_maquina'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="tipo_trabajo">Tipo de Trabajo:</label>
        <input type="text" name="tipo_trabajo" id="tipo_trabajo" required>

        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" required>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" id="fecha_fin">

        <button type="submit" name="crear_trabajo">Crear Trabajo</button>
    </form>

    <br>
    <a href="menu.php">Volver al menú</a>
</body>
</html>

<?php
// Cerrar conexión
mysqli_close($conexion);
?>