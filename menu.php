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
        if (isset($_SESSION["usuario_valido"])) {
            echo "Bienvenido, " . $_SESSION["usuario_valido"] . " con el rol: " . $_SESSION["roles"] . "<br>";

            // Mostrar los botones según el rol del usuario
            $rol = $_SESSION["roles"];

            if ($rol === 'Administrador') {
                // Mostrar todos los botones si es administrador
                ?>
                <br><br>
                <form action="editar_agricultores.php" method="POST">
                    <input type="submit" name="añadir" value="Modificar agricultores"><br>
                </form><br>

                <form action="editar_clientes.php" method="POST">
                    <input type="submit" name="editar" value="Modificar clientes"><br>
                </form><br>

                <form action="editar_parcela.php" method="POST">
                    <input type="submit" name="editar" value="Modificar parcelas"><br>
                </form><br>
                <form action="editar_trabajos.php" method="POST">
                    <input type="submit" name="editar" value="Modificar trabajos"><br>
                </form><br>

                <?php
            } elseif ($rol === 'Agricultor') {
                // Mostrar solo añadir y consultar noticias si es profesor
                ?>
                <br><br>
                <form action="inserta_noticia.php" method="POST">
                    <input type="submit" name="añadir" value="Elegir trabajo"><br>
                </form><br>

                <form action="consulta_noticias2.php" method="POST">
                    <input type="submit" name="consultar" value="Consultar noticias"><br>
                </form><br>

                <?php
            } elseif ($rol === 'Cliente') {
                // Mostrar solo consultar noticias si es alumno
                ?>
                <br><br>
                <form action="consulta_noticias2.php" method="POST">
                    <input type="submit" name="consultar" value="Consultar noticias"><br>
                </form><br>

                <?php
            }
            // Botón de logout
            ?>
            <form action="logout.php" method="POST">
                <input type="submit" name="logout" value="Cerrar seseión"><br>
            </form>
        <?php
        } else {
            // Si no hay sesión activa, mostrar mensaje de acceso no autorizado
            echo '<p>Usted no tiene acceso a esta página.</p>';
        }
        ?>
    </body>
</html>
