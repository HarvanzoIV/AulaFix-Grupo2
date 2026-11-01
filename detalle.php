
<?php
session_start();

$usuario = $_SESSION['usuario'] ?? '';
if (empty($usuario)) {
    header('Location: login.php');
    exit;
}

require 'base_de_datos/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$incidencia = null;

if ($id && $id > 0) {
    $stmt = $cn->prepare('SELECT * FROM incidencias WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $incidencia = $resultado->fetch_assoc() ?: null;
    $stmt->close();
}

function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function etiquetaCampo(string $campo): string
{
    $etiquetas = [
        'id' => 'Identificador',
        'problema' => 'Problema',
        'descripcion' => 'Descripción',
        'ubicacion' => 'Ubicación',
        'prioridad' => 'Prioridad',
        'situacion' => 'Situación',
        'fecha' => 'Fecha',
        'fecha_creacion' => 'Fecha de creación',
        'fecha_actualizacion' => 'Última actualización',
        'usuario' => 'Usuario',
        'id_usuario' => 'ID de usuario'
    ];

    return $etiquetas[$campo] ?? ucfirst(str_replace('_', ' ', $campo));
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

$camposLargos = ['problema', 'descripcion', 'observaciones', 'comentarios', 'detalle'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111a2e">
    <title>AulaFix | Detalle de incidencia</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="listar.php" aria-label="AulaFix, ir al panel">
            <span class="brand-mark">A</span>
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
            <div class="system-state"><span class="system-dot"></span> Sistema operativo</div>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <p class="page-kicker">AULAFIX / INCIDENCIAS / DETALLE</p>
                <h1>Detalle de incidencia</h1>
                <p class="page-description">Toda la información del registro, organizada en una sola ficha.</p>
            </div>
            <div class="topbar-actions">
                <button class="theme-toggle" id="theme-toggle" type="button" aria-pressed="false">
                    <span class="theme-icon" aria-hidden="true">☾</span>
                    <span>Modo oscuro</span>
                </button>
                <a class="btn btn-secondary" href="listar.php">← Volver al listado</a>
            </div>
        </header>

        <?php if ($incidencia): ?>
            <section class="detail-page" aria-label="Ficha de la incidencia">
                <div class="detail-hero">
                    <div class="detail-hero-main">
                        <span class="detail-hero-kicker">FICHA DE INCIDENCIA · #<?= (int) $incidencia['id'] ?></span>
                        <h2><?= e($incidencia['problema'] ?? 'Incidencia sin título') ?></h2>
                        <p>Consulta los datos registrados y el estado actual de esta incidencia.</p>
                    </div>
                    <div class="detail-hero-badges">
                        <?php if (!empty($incidencia['prioridad'])): ?>
                            <span class="badge <?= e(clasePrioridad($incidencia['prioridad'])) ?>"><?= e($incidencia['prioridad']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($incidencia['situacion'])): ?>
                            <span class="badge <?= e(claseSituacion($incidencia['situacion'])) ?>"><?= e($incidencia['situacion']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <section class="detail-panel">
                    <div class="detail-panel-heading">
                        <div class="detail-panel-icon" aria-hidden="true">▤</div>
                        <div>
                            <h2>Información registrada</h2>
                            <p>Los datos se muestran tal y como están guardados en la incidencia.</p>
                        </div>
                    </div>

                    <div class="detail-fields">
                        <?php foreach ($incidencia as $campo => $valor): ?>
                            <?php if (in_array($campo, ['id', 'problema'], true)) continue; ?>
                            <?php
                            $esLargo = in_array($campo, $camposLargos, true);
                            $claseCampo = $esLargo ? 'detail-field detail-field-wide' : 'detail-field';
                            ?>
                            <article class="<?= e($claseCampo) ?>">
                                <span class="detail-field-label"><?= e(etiquetaCampo((string) $campo)) ?></span>

                                <?php if ($campo === 'prioridad' && $valor !== null && $valor !== ''): ?>
                                    <div class="detail-field-value">
                                        <span class="badge <?= e(clasePrioridad($valor)) ?>"><?= e($valor) ?></span>
                                    </div>
                                <?php elseif ($campo === 'situacion' && $valor !== null && $valor !== ''): ?>
                                    <div class="detail-field-value">
                                        <span class="badge <?= e(claseSituacion($valor)) ?>"><?= e($valor) ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="detail-field-value <?= $esLargo ? 'detail-field-long-text' : '' ?>">
                                        <?= $valor !== null && $valor !== '' ? nl2br(e($valor)) : '—' ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div class="detail-actions">
                        <a class="btn btn-primary" href="editar.php?id=<?= (int) $incidencia['id'] ?>">✎ Editar incidencia</a>
                        <a class="btn btn-secondary" href="listar.php">← Volver al listado</a>
                    </div>
                </section>
            </section>
        <?php else: ?>
            <section class="detail-panel detail-not-found">
                <div class="empty-icon" aria-hidden="true">!</div>
                <h2>No se ha encontrado la incidencia</h2>
                <p>El identificador no es válido o la incidencia ya no existe.</p>
                <a class="btn btn-primary" href="listar.php">← Volver al listado</a>
            </section>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
