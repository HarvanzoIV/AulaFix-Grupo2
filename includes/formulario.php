<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Incidencias</title>
</head>
<body>

    <h1>Control de Incidencias</h1>
    
    <p>Complete el formulario para poder tramitar su incidencia.</p>

    <form method="POST" action="guardar.php">

        <label>Problema:</label>
        <input type="text" name="problema" required>
        <br><br>

        <label>Ubicación:</label>
        <input type="text" name="ubicacion" required>
        <br><br>

        <label>Prioridad:</label>
        <select name="prioridad" required>
            <option value="">Seleccione una opción</option>
            <option>Baja</option>
            <option>Media</option>
            <option>Alta</option>
        </select>
        <br><br>

        <label>Situación:</label>
        <select name="situacion" required>
            <option value="">Seleccione una opción</option>
            <option>Abierta</option>
            <option>En proceso</option>
            <option>Resuelta</option>
        </select>
        <br><br>

        <input type="submit" value="Guardar">

    </form>

</body>
</html>


