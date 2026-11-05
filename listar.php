
<?php
session_start();

$usuario = $_SESSION['usuario'] ?? '';
if (empty($usuario)) {
    header('Location: login.php');
    exit;
}

require 'base_de_datos/db.php';

// Eliminar una incidencia desde el listado.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $idEliminar = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($idEliminar) {
        $stmt = $cn->prepare('DELETE FROM incidencias WHERE id = ?');
        $stmt->bind_param('i', $idEliminar);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: listar.php');
    exit;
}

// El listado es un resumen; la información completa se consulta en detalle.php.
$resultado = $cn->query('SELECT id, problema, prioridad, situacion FROM incidencias ORDER BY id DESC');
$totalIncidencias = $resultado ? $resultado->num_rows : 0;

function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function clasePrioridad($prioridad): string
{
    $valor = strtolower(trim((string) $prioridad));
    if ($valor === 'alta') return 'priority-high';
    if ($valor === 'media') return 'priority-medium';
    if ($valor === 'baja') return 'priority-low';
    return '';
}

function claseSituacion($situacion): string
{
    $valor = strtolower(trim((string) $situacion));
    if (in_array($valor, ['resuelta', 'cerrada', 'cerrado'], true)) return 'status-resolved';
    if (in_array($valor, ['en proceso', 'en_proceso', 'en curso'], true)) return 'status-progress';
    if (in_array($valor, ['abierta', 'abierto', 'pendiente'], true)) return 'status-open';
    return '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111a2e">
    <title>AulaFix | Gestión de incidencias</title>
    <link rel ="icon" type = "image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="listar.php" aria-label="AulaFix, ir al panel">
            <span class="brand-mark"><img src = "img/logo.png" class = "logo"></span>
            <span class="brand-copy">AulaFix<small>INCIDENCE MANAGEMENT</small></span>
        </a>

        <p class="nav-caption">Espacio de trabajo</p>
        <nav class="side-nav" aria-label="Navegación principal">
            <a class="active" href="listar.php" aria-current="page">
                <span class="nav-icon" aria-hidden="true">▦</span>
                <span>Incidencias</span>
            </a>
            <a href="formulario.php">
                <span class="nav-icon" aria-hidden="true">＋</span>
                <span>Nueva incidencia</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
    <div class="user-panel">

        <div class="user-greeting">
            Hola <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋
        </div>

        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">
                Cerrar sesión🚪
            </button>
        </form>

    </div>
</div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <p class="page-kicker">AULAFIX / PANEL DE CONTROL</p>
                <h1>Gestión de incidencias</h1>
                <p class="page-description">Consulta los resúmenes y abre cada incidencia para ver toda su información.</p>
            </div>
            <div class="topbar-actions">
                <button class="theme-toggle" id="theme-toggle" type="button" aria-pressed="false">
                    <span class="theme-icon" aria-hidden="true">☾</span>
                    <span>Modo oscuro</span>
                </button>
                <a class="btn btn-primary" href="formulario.php"><span aria-hidden="true">＋</span> Nueva incidencia</a>
            </div>
        </header>

        <section class="content-card" aria-labelledby="list-title">
            <div class="card-heading">
                <div>
                    <h2 id="list-title">Incidencias registradas</h2>
                    <p>Elige «Ver detalle» para consultar toda la información de una incidencia.</p>
                </div>
                <span class="record-count" id="totalRegistros"><?= (int) $totalIncidencias ?> REGISTROS</span>
            </div>

            <div class="list-toolbar">
                <label class="search-field" for="buscar">
                    <span aria-hidden="true">⌕</span>
                    <input
                        type="search"
                        id="buscar"
                        placeholder="Buscar por problema, ID, prioridad o situación..."
                        autocomplete="off"
                        aria-controls="tablaIncidencias">
                </label>
                <span class="list-hint">Vista resumida</span>
            </div>

            <?php if ($totalIncidencias > 0): ?>
                <div class="table-wrap">
                    <table id="tablaIncidencias">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Problema</th>
                                <th>Prioridad</th>
                                <th>Situación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <?php
                            $busquedaFila = implode(' ', [
                                $fila['id'] ?? '',
                                $fila['problema'] ?? '',
                                $fila['prioridad'] ?? '',
                                $fila['situacion'] ?? ''
                            ]);
                            ?>
                            <tr data-search="<?= e($busquedaFila) ?>">
                                <td>#<?= (int) $fila['id'] ?></td>
                                <td class="problem-cell"><?= e($fila['problema']) ?></td>
                                <td>
                                    <span class="badge <?= e(clasePrioridad($fila['prioridad'])) ?>">
                                        <?= e($fila['prioridad']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= e(claseSituacion($fila['situacion'])) ?>">
                                        <?= e($fila['situacion']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a class="btn btn-detail botond" href="detalle.php?id=<?= (int) $fila['id'] ?>">Ver detalle</a>
                                        <a class="btn btn-edit" href="editar.php?id=<?= (int) $fila['id'] ?>">Editar</a>
                                        <form method="POST" action="listar.php" onsubmit="return confirm('¿Seguro que deseas eliminar esta incidencia? Esta acción no se puede deshacer.');">
                                            <input type="hidden" name="id" value="<?= (int) $fila['id'] ?>">
                                            <button type="submit" name="eliminar" class="btn btn-delete">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div id="sinResultados" class="search-empty" hidden>
                    <span class="search-empty-icon" aria-hidden="true">⌕</span>
                    <strong>No se encontraron incidencias</strong>
                    <p>Prueba con otro término o comprueba el identificador.</p>
                </div>
                <p class="search-feedback" id="resultadoBusqueda" aria-live="polite"></p>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon" aria-hidden="true">⌁</div>
                    <h3>Todavía no hay incidencias</h3>
                    <p>Cuando registres una incidencia, aparecerá aquí para que puedas gestionarla.</p>
                    <a class="btn btn-primary" href="formulario.php" style="margin-top: 18px;">＋ Registrar incidencia</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>
</body>
</html>
