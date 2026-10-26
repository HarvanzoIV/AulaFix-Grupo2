<?php
require 'base_de_datos/db.php';

$sql = "SELECT * FROM incidencias";
$resultado = $cn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Incidencias</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Listado de Incidencias</h1>

    <a href="formulario.php">Nueva incidencia</a>

    <br><br>

    <?php if ($resultado->num_rows > 0): ?>

        <table>
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
                        <a class="editar"
                            href="editar.php?id=<?= $fila['id'] ?>">
                            Editar
                        </a>

                        |

                        <a class="eliminar"
                            href="eliminar.php?id=<?= $fila['id'] ?>"
                            onclick="return confirm('¿Seguro que deseas eliminar esta incidencia?');">
                            Eliminar
                        </a>
                    </td>
                </tr>

            <?php endwhile; ?>

        </table>

    <?php else: ?>

        <p>No hay incidencias registradas.</p>

    <?php endif; ?>

</body>

</html>