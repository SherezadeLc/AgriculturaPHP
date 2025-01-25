<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Menu</title>
    </head>
    <body>
        <?php
        // Verificar si la sesión está activa
        if (isset($_SESSION['tipo'])) {
            echo "Bienvenido, " . $_SESSION['nombre'] . " con el rol: " . $_SESSION['tipo'] . "<br>";

           

            if ($_SESSION['tipo'] === 'administrador') {
                ?>
                <br><br>
                <form action="editar_agricultores.php" method="POST">
                    <input type="submit" name="añadir" value=" Agricultores"><br>
                </form><br>

                <form action="editar_clientes.php" method="POST">
                    <input type="submit" name="editar" value="Clientes"><br>
                </form><br>

                <form action="editar_parcela.php" method="POST">
                    <input type="submit" name="editar" value="Parcelas"><br>
                </form><br>
                <form action="editar_trabajos.php" method="POST">
                    <input type="submit" name="editar" value="Trabajos"><br>
                </form><br>
                <form action="editar_maquinas.php" method="POST">
                    <input type="submit" name="editar" value="Máquinas"><br>
                </form><br>
                <?php
            } elseif ($_SESSION['tipo'] === 'agricultor') {
                ?>
                <br><br>
                <form action="elegir_trabajo.php" method="POST">
                    <input type="submit" name="añadir" value="Elegir trabajo"><br>
                </form><br>

                <?php
            } elseif ($_SESSION['tipo'] === 'cliente') {
                ?>
                <br><br>
                <form action="añadir_parcelas.php" method="POST">
                    <input type="submit" name="Añadir" value="Añadir parcelas"><br>
                </form><br>
                <form action="crear_trabajo.php" method="POST">
                    <input type="submit" name="Crear" value="Crear trabajo"><br>
                </form><br>

                <?php
            }
            // Botón de logout
            ?>
            <form action="logout.php" method="POST">
                <input type="submit" name="logout" value="Cerrar sesión"><br>
            </form>
        <?php
        } else {
            // Si no hay sesión activa, mostrar mensaje de acceso no autorizado
            echo '<p>Usted no tiene acceso a esta página.</p>';
        }
        ?>
    </body>
</html>
