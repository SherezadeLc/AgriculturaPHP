<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Menu</title>
        <style>
            /* Estilos generales */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                padding: 0;
                text-align: center;
            }

            /* Contenedor principal */
            .container {
                width: 80%;
                margin: auto;
                padding: 20px;
                background: #ffffff;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                margin-top: 50px;
            }

            /* Título */
            h1 {
                color: #2c6e49;
                font-size: 28px;
            }

            /* Botones */
            form {
                margin: 15px 0;
            }

            input[type="submit"] {
                background: #2c6e49;
                color: white;
                border: none;
                padding: 12px 20px;
                font-size: 16px;
                border-radius: 5px;
                cursor: pointer;
                transition: background 0.3s ease-in-out;
            }

            input[type="submit"]:hover {
                background: #3a945b;
            }

            /* Mensaje de bienvenida */
            .welcome-message {
                font-size: 18px;
                font-weight: bold;
                color: #2c6e49;
                margin-bottom: 20px;
            }

            /* Mensaje de error */
            .error-message {
                color: red;
                font-size: 16px;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .container {
                    width: 95%;
                }
            }
        </style>
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
                    <input type="submit" name="añadir" value="Añadir Agricultores"><br>
                </form><br>

                <form action="editar_clientes.php" method="POST">
                    <input type="submit" name="editar" value="Listar Clientes"><br>
                </form><br>

                <form action="editar_maquinas.php" method="POST">
                    <input type="submit" name="editar" value="Añadir Máquinas"><br>
                </form><br>
                <?php
            } elseif ($_SESSION['tipo'] === 'agricultor') {
                ?>
                <br><br>
                <form action="elegir_trabajo.php" method="POST">
                    <input type="submit" name="añadir" value="Elegir trabajo"><br>
                </form><br>
                <form action="cambiar_contraseña.php" method="POST">
                    <input type="submit" name="cambiar" value="Cambiar contraseña"><br>
                </form><br>

                <?php
            } elseif ($_SESSION['tipo'] === 'cliente') {
                ?>
                <br><br>
                <form action="editar_parcela.php" method="POST">
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
