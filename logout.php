<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Logout</title>
    </head>
    <body>
        <div class="container">
            <?php
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
