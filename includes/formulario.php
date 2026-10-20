



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Incidecias</title>
</head>
<body>
    <h1>Control de incidecias</h1>
    <p>Complete el formulario para poder tramitar su incidecia</p>
    <form method="POST" action=>
        <label>Problema:</label>
        <input type="text" name="problema" require><br><br>

        <label>Ubicación:</label>
        <input type="text" name="ubicacion" require><br><br>

        <label>Prioridad</label>
        <select name="prioridad" require>
            <option></option>
            <option>Baja</option>
            <option>Media</option>
            <option>Alta</option>
        </select><br><br>

        <label>Situación</label>
        <select name="situacion" require>
            <option></option>
            <option>Abierta</option>
            <option>En proceso</option>
            <option>Resuelta</option>
        </select><br><br>

    </form>
</body>
</html>


