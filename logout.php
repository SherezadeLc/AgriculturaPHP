<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Logout</title>
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

            .contenedor{
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
        <div class="contenedor">
            <?php
            //
            //
            
            session_start();
            print '<h1>Estás en Logout</h1>';
            if (isset($_SESSION["nombre"])) {
                print 'Hasta pronto, ' . $_SESSION["nombre"] . '<br>';
            } else {
                print 'No hay usuario activo.<br>';
            }
            session_destroy();
            print 'Se ha cerrado la sesión.';
            ?>
            <form action="login.php" method="POST">
                <input type="submit" value="Volver">
            </form>
        </div>
    </body>
</html>

