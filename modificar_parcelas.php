<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Modificar Parcelas</title>
    </head>
    <body>
        <?php
        
        //conexion a la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "agricultura")
                or die("No se puede conectar con el servidor");

        //aqui sacamos lo sacamos a la una tabla con los datos de las parcelas
        //primero hacemos la consulta a la base de datos 
        echo "<h2>PARCELAS EXISTENTES</h2>";
        $parcelas_existentes = mysqli_query($conexion, "SELECT * FROM parcela");
        $puntos_existentes = mysqli_query($conexion, "SELECT * FROM puntos");
        //aqui lo sacamo a la tabla
        if (mysqli_num_rows($parcelas_existentes) > 0 && mysqli_num_rows($puntos_existentes) > 0) {
            echo "<table border='1'>";
            //aqui esta el encabezado de la tabla
            echo "<tr><th>ID Parcela</th><th>Numero Catastro</th><th>Numero Parcela</th><th>Latitud</th><th>Longitud</th></tr>";
            //aqui saco fila en fila toda la informacion de la tablas de la base de datos
            while (($fila1 = mysqli_fetch_assoc($parcelas_existentes)) && ($fila2 = mysqli_fetch_assoc($puntos_existentes))) {
                echo "<tr><td>{$fila1['id_parcela']}</td>"
                . "<td>{$fila1['id_catastro']}</td>"
                . "<td>{$fila1['numero_parcela']}</td>"
                . "<td>{$fila2['latitud']}</td>"
                . "<td>{$fila2['longitud']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay parcelas existentes.</p>";
        }
        ?>

        <br>
        <!--en este formulario es donde el usuario mete la informacion para luego combiar la informacion de la base de datos-->
        <form action="" method="POST">
            <label for="id_parcelas">ID Parcela:</label>
            <input type="text" name="id_parcelas" id="id_parcelas" required><br><br>

            <label for="id_catastros">Numero Catastro:</label>
            <input type="text" name="id_catastros" id="id_catastros" required><br><br>

            <label for="numero_parcelas">Numero Parcela:</label>
            <input type="text" name="numero_parcelas" id="numero_parcelas" required><br><br>

            <label for="latitudes">Latitud:</label>
            <input type="text" name="latitudes" id="latitudes" required><br><br>

            <label for="longitudes">Longitud:</label>
            <input type="text" name="longitudes" id="longitudes" required><br><br>

            <input type="submit" name="modificar" value="Modificar">
        </form>

        <?php
        //aqui miramos si ha pulsado el boton de modificar
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
            //aqui recogemos la informacion del formulario
            $id_parcelas = $_POST['id_parcelas'];
            $id_catastros = $_POST['id_catastros'];
            $numero_parcelas = $_POST['numero_parcelas'];
            $latitudes = $_POST['latitudes'];
            $longitudes = $_POST['longitudes'];

            // aqui miramos si existe el id_catastro en la tabla cliente
            $verificar_catastro = "SELECT * FROM cliente WHERE id_catastro = '$id_catastros'";
            //aqui hacemos la conexiona la base de datos
            $resultado_verificacion = mysqli_query($conexion, $verificar_catastro);

            //si existe alguna coincidencia entra
            if (mysqli_num_rows($resultado_verificacion) > 0) {
                //aqui hago la consulta que coincida el id_parcela
                $consultaIntermedia = "SELECT * FROM puntos_parcela WHERE id_parcela='$id_parcelas'";
                $seleccionar_punto = mysqli_query($conexion, $consultaIntermedia);
                
                //aqui si hay alguna coindicidencia entra
                if (mysqli_num_rows($seleccionar_punto) > 0) {
                    //aqui coge la informacion que ha coincidido con el id_parcela
                    $datos = mysqli_fetch_assoc($seleccionar_punto);
                    //aqui coge el id_punto que coicidia de antes
                    $id_puntos = $datos['id_punto'];

                    // aqui actualizamos la infomacion de la parcela
                    $actualizar_parcela = "UPDATE parcela SET id_catastro='$id_catastros', numero_parcela='$numero_parcelas' WHERE id_parcela='$id_parcelas'";
                    //aqui hacemos la conexion a la base de datos
                    if (mysqli_query($conexion, $actualizar_parcela)) {
                        //aqui sale un mensaje de que hasalido bien
                        echo "Se ha actualizado la parcela correctamente.<br>";
                    } else {
                        //aqui sale un mensaje de que ha pasado algo en la consulta
                        echo "Error al actualizar la parcela: " . mysqli_error($conexion) . "<br>";
                    }

                    // aqui actualizamos la informacion de la tabla de puntos
                    $actualizar_puntos = "UPDATE puntos SET latitud='$latitudes', longitud='$longitudes' WHERE id_punto='$id_puntos'";
                    //aqui hacemos la conexion a la base de datos con la consulta anterior
                    if (mysqli_query($conexion, $actualizar_puntos)) {
                        //aqui sale un mensaje de que ha salido todo bien
                        echo "Se han actualizado los puntos correctamente.<br>";
                    } else {
                        //aqui sale un mensaje de que ha habido un error en actualizar los datos de la tabla puntos
                        echo "Error al actualizar los puntos: " . mysqli_error($conexion) . "<br>";
                    }
                     
                } else {
                    //aqui sale un mensaje de que no se a encontrado la parcela
                    echo "No se encontró ningún punto asociado a la parcela.<br>";
                }
            } else {
                //aqui sale un mensaje de que no existe en la tabla cliente la parcela
                echo "El Numero Catastro ingresado no existe en la tabla Cliente.<br>";
            }
        }
        ?>
        <form action="editar_parcela.php" method="POST">
            <input type="submit" name="volver" value="Volver">
        </form>
    </body>
</html>