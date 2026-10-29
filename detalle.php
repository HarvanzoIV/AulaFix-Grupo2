<?php
require 'base_de_datos/db.php';

// Eliminar incidencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = (int) $_POST['id'];

    $stmt = $cn->prepare("DELETE FROM incidencias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: listar.php");
    exit;
}

$sql = "SELECT * FROM incidencias";
$resultado = $cn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Incidencias </title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Listado de Incidencias Completo</h1>

    <a href="formulario.php">Nueva incidencia</a>

    <br><br>

    <label for="buscar">Buscar incidencia (Problema):</label>
    <input type="text" id="buscar" placeholder="Escribe para filtrar...">

    <br><br>
    <form action = "listar.php" method ="POST" style="display:inline;">
        <input type="hidden" name="id" value="<?= $fila['id'] ?>">
        <button type = "submit" class = "Cerrar_detalles">
        Cerrar detalles
        </button>
    </form>
    <br><br>

    <?php if ($resultado->num_rows > 0): ?>

        <table id="tablaIncidencias">
            <tr>
                <th>ID</th>
                <th>Problema</th>
                <th>Ubicación</th>
                <th>Prioridad</th>
                <th>Situación</th>
                <th>Acciones</th>
            </tr>

            <?php while ($fila = $resultado->fetch_assoc()): ?>

                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['problema']) ?></td>
                    <td><?= htmlspecialchars($fila['ubicacion']) ?></td>
                    <td><?= htmlspecialchars($fila['prioridad']) ?></td>
                    <td><?= htmlspecialchars($fila['situacion']) ?></td>
                    <td>
                        <form action="editar.php" method="GET" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                            <button type="submit" class="editar">
                                Editar
                            </button>
                        </form>

                        |

                        <form method="POST" style="display:inline;"
                            onsubmit="return confirm('¿Seguro que deseas eliminar esta incidencia?');">
                            <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                            <button type="submit" name="eliminar" class="eliminar">
                                Eliminar
                            </button>
                        </form>

                        
                    </td>

                </tr>

            <?php endwhile; ?>

        </table>

    <?php else: ?>

        <p>No hay incidencias registradas.</p>

    <?php endif; ?>

</body>

</html>

<script src="js/script.js"></script>