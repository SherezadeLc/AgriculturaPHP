<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Menu</title>
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

            .container {
                width: 30%;
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
        </style>
    </head>
    <body>
        <div class="container">
            <?php
            if (isset($_SESSION['tipo'])) {
                echo "<h1>Bienvenido, " . $_SESSION['nombre'] . "</h1>";
                echo "<p>Rol: " . $_SESSION['tipo'] . "</p>";
                
                if ($_SESSION['tipo'] === 'administrador') {
                    echo '<form action="editar_agricultores.php" method="POST">
                            <input type="submit" value="Añadir Agricultores">
                          </form>';
                    echo '<form action="editar_clientes.php" method="POST">
                            <input type="submit" value="Listar Clientes">
                          </form>';
                    echo '<form action="editar_maquinas.php" method="POST">
                            <input type="submit" value="Añadir Máquinas">
                          </form>';
                } elseif ($_SESSION['tipo'] === 'agricultor') {
                    echo '<form action="elegir_trabajo.php" method="POST">
                            <input type="submit" value="Elegir trabajo">
                          </form>';
                    echo '<form action="cambiar_contraseña.php" method="POST">
                            <input type="submit" value="Cambiar contraseña">
                          </form>';
                } elseif ($_SESSION['tipo'] === 'cliente') {
                    echo '<form action="editar_parcela.php" method="POST">
                            <input type="submit" value="Añadir parcelas">
                          </form>';
                    echo '<form action="crear_trabajo.php" method="POST">
                            <input type="submit" value="Crear trabajo">
                          </form>';
                }
                echo '<form action="logout.php" method="POST">
                        <input type="submit" value="Cerrar sesión">
                      </form>';
            } else {
                echo '<p class="alert">Usted no tiene acceso a esta página.</p>';
            }
            ?>
        </div>
    </body>
</html>
