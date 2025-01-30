<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                min-height: 100vh;
            }

            .contenedor {
                margin-top: 10px;
                margin-bottom: 10px;
                width: 30%;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h1 {
                color: #2e7d32;
                margin-top: 20px;
                font-size: 24px;
            }

            label {
                display: block;
                margin: 15px 0 5px;
                color: #333;
                font-size: 16px;
            }

            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
                box-sizing: border-box;
            }

            input[type="submit"], .boton-login {
                background-color: #2e7d32;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
                transition: background-color 0.3s;
                margin-top: 15px;
            }

            input[type="submit"]:hover, .boton-login:hover {
                background-color: #1b5e20;
            }

            .error {
                color: red;
                font-weight: bold;
                text-align: center;
                margin-top: 10px;
            }

            a {
                text-decoration: none;
                display: block;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>

        <div class="contenedor">
            <h1>Registro</h1>
            <form method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>

                <label>Apellidos:</label>
                <input type="text" name="apellidos" required>

                <label>DNI:</label>
                <input type="text" name="dni" required>

                <label>Id Catastro:</label>
                <input type="text" name="id_catastro" required>

                <label>Número Parcela:</label>
                <input type="text" name="numero_parcela" required>

                <label>Latitud:</label>
                <input type="text" name="latitud" required>

                <label>Longitud:</label>
                <input type="text" name="longitud" required>

                <label>Contraseña:</label>
                <input type="password" name="password" required>

                <input type="submit" name="enviar" value="Registrar">
            </form>

            <hr>
            <div >
                <p>¿Ya te has registrado?</p>
                <a href="login.php"><button class="boton-login">Iniciar sesión</button></a>
            </div>
        </div>
    </body>
</html>