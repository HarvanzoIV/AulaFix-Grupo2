<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
</head>

<body>
    <div>
        <form method="post" action="login.php"><!--Boton de volver-->
            <button type="submit">Volver</button>
        </form>
        <form method="post" action="base_de_datos/registrar.php"><!--Añadir fºuncion del formulario-->
            <h1>Crear nueva cuenta</h1>
            <br><br>
            <label>Nombre:</label><input type="text" name="nombre" required>
            <br><br>
            <label>Apellidos:</label><input type="text" name="apellidos" required>
            <br><br>
            <label>Correo:</label><input type="email" name="correo" required>
            <br><br>
            <label>Nombre de usuario:</label><br>
            <input type="text" name="user" required>
            <br><br>
            <label>Contraseña:</label><br><input type="password" name="key1" required>
            <!--key1 y key2 hace referencia de confirmar la contraseña-->
            <br><br>
            <label>Confirmar contraseña:</label><br><input type="password" name="key2" required>
            <br><br>
            <button type="submit">Registrar</button>
        </form>
    </div>


</body>

</html>