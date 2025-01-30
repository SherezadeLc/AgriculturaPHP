<?
    php session_start(); 
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        
    // Conexión a la base de datos
    $conexion = mysqli_connect("localhost", "root", "", "agricultura") or die("No se puede conectar con el servidor");

    // Mostramos las parcelas existentes
    echo "<h2>PARCELAS EXISTENTES</h2>";
    $parcelas_existentes = mysqli_query($conexion, "SELECT * FROM parcela");
    $puntos_existentes = mysqli_query($conexion, "SELECT * FROM puntos");

    if (mysqli_num_rows($parcelas_existentes) > 0 && mysqli_num_rows($puntos_existentes) > 0) {
        echo "<div style='float:left'>";
        echo "<table border='1'>";
        echo "<tr><th>ID Parcela</th><th>Numero Catastro</th><th>Numero Parcela</th></tr>";
        
        // Aquí recuperamos primero todos los resultados de las parcelas
        while ($fila1 = mysqli_fetch_assoc($parcelas_existentes)) {
            echo "<tr>
            <td>{$fila1['id_parcela']}</td>
                        <td>{$fila1['id_catastro']}</td>
                        <td>{$fila1['numero_parcela']}</td>"
                        . "</tr>";
            
        }
        echo "</table>";
        echo "</div>";
        echo "<div style='float:left'>";
        echo "<table border='1'>";
       
        echo "<tr><th>Latitud</th><th>Longitud</th></tr>";
        // Luego recuperamos todos los resultados de los puntos (puedes usar un contador o lógica adicional para combinar correctamente)
            while ($fila2 = mysqli_fetch_assoc($puntos_existentes)) {
             echo "<tr>
                        
                        <td>{$fila2['latitud']}</td>
                        <td>{$fila2['longitud']}</td>
                    </tr>";
            }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>No hay parcelas existentes.</p>";
    }
    ?>
        <br> <br> <br> <br> <br>
         <!-- Formulario para volver -->
    <form action="editar_parcela.php" method="POST">
        <input type="submit" name="volver" value="Volver">
    </form>
    </body>
</html>
