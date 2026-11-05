<?php
session_start();

$usuario = $_SESSION['usuario'] ?? '';

if (empty($usuario)) {
    header('Location: login.php');
    exit;
}

require 'base_de_datos/db.php';

$incidencia = null;
$error = '';

// Escapar contenido HTML
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Obtener el identificador
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Si el formulario se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

    $problema = trim($_POST['problema'] ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $prioridad = $_POST['prioridad'] ?? '';
    $situacion = $_POST['situacion'] ?? '';

    // Validar los datos
    if (!$id || $id <= 0) {
        $error = 'El identificador de la incidencia no es válido.';
    } elseif (
        $problema === '' ||
        $ubicacion === '' ||
        !in_array($prioridad, ['Baja', 'Media', 'Alta'], true) ||
        !in_array($situacion, ['Abierta', 'En proceso', 'Resuelta'], true)
    ) {
        $error = 'Comprueba que todos los campos estén correctamente completados.';
    } else {

        $stmtAnterior = $cn->prepare("
        SELECT problema, ubicacion, prioridad, situacion
        FROM incidencias
        WHERE id = ?
    ");

        $stmtAnterior->bind_param("i", $id);
        $stmtAnterior->execute();

        $datosAnteriores = $stmtAnterior->get_result()->fetch_assoc();

        $stmtAnterior->close();

        $sql = "UPDATE incidencias
                SET problema = ?,
                    ubicacion = ?,
                    prioridad = ?,
                    situacion = ?
                WHERE id = ?";

        $stmt = $cn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param(
                "ssssi",
                $problema,
                $ubicacion,
                $prioridad,
                $situacion,
                $id
            );

            if ($stmt->execute()) {

                $campos = [
                    'problema' => $problema,
                    'ubicacion' => $ubicacion,
                    'prioridad' => $prioridad,
                    'situacion' => $situacion
                ];

                foreach ($campos as $campo => $valorNuevo) {

                    $valorAnterior = $datosAnteriores[$campo];

                    if ($valorAnterior != $valorNuevo) {

                        $stmtHistorial = $cn->prepare("
            INSERT INTO historial_incidencias
            (
                incidencia_id,
                campo_modificado,
                valor_anterior,
                valor_nuevo
            )
            VALUES (?, ?, ?, ?)
        ");

                        $stmtHistorial->bind_param(
                            "isss",
                            $id,
                            $campo,
                            $valorAnterior,
                            $valorNuevo
                        );

                        $stmtHistorial->execute();
                        $stmtHistorial->close();
                    }
                }

                $stmt->close();

                header("Location: listar.php");
                exit;
            } else {
                $error = 'No se han podido guardar los cambios.';
                $stmt->close();
            }
        } else {
            $error = 'No se ha podido preparar la actualización.';
        }
    }
}

// Consultar la incidencia
if ($id && $id > 0) {

    $sql = "SELECT * FROM incidencias WHERE id = ?";

    $stmt = $cn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        $incidencia = $resultado->fetch_assoc() ?: null;

        $stmt->close();
    }
}

// Recuperar los valores enviados si hubo algún error
if ($incidencia && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $incidencia['problema'] = $_POST['problema'] ?? $incidencia['problema'];
    $incidencia['ubicacion'] = $_POST['ubicacion'] ?? $incidencia['ubicacion'];
    $incidencia['prioridad'] = $_POST['prioridad'] ?? $incidencia['prioridad'];
    $incidencia['situacion'] = $_POST['situacion'] ?? $incidencia['situacion'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111a2e">
    <link rel ="icon" type = "image/x-icon" href="img/logo.png">
    <title>AulaFix | Editar incidencia</title>

    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>

<body>

    <div class="app-shell">

        <!-- BARRA LATERAL -->
        <aside class="sidebar">

            <a class="brand" href="listar.php">

                <span class="brand-mark"><img src = "img/logo.png" class = "logo"></span>

                <span class="brand-copy">
                    AulaFix
                    <small>INCIDENCE MANAGEMENT</small>
                </span>

            </a>

            <p class="nav-caption">Espacio de trabajo</p>

            <nav class="side-nav">

                <a class="active" href="listar.php">
                    <span class="nav-icon">▦</span>
                    <span>Incidencias</span>
                </a>

                <a href="formulario.php">
                    <span class="nav-icon">＋</span>
                    <span>Nueva incidencia</span>
                </a>

            </nav>

       <div class="sidebar-bottom">
    <div class="user-panel">

        <div class="user-greeting">
            Hola <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋
        </div>

        <form action=logout.php" method="post">
            <button type="submit" class="logout-btn">
                Cerrar sesión🚪
            </button>
        </form>

    </div>
    </div>
    </aside>


        <!-- CONTENIDO PRINCIPAL -->
        <main class="main-content">

            <header class="topbar">

                <div>
                    <p class="page-kicker">AULAFIX / GESTIÓN / EDICIÓN</p>

                    <h1>Editar incidencia</h1>

                    <p class="page-description">
                        Actualiza los detalles de la incidencia y guarda los cambios.
                    </p>
                </div>

                <div class="topbar-actions">

                    <button
                        class="theme-toggle"
                        id="theme-toggle"
                        type="button"
                        aria-pressed="false">

                        <span class="theme-icon">☾</span>
                        <span>Modo oscuro</span>

                    </button>

                    <a class="btn btn-secondary" href="listar.php">
                        ← Volver al listado
                    </a>

                </div>

            </header>


            <?php if ($incidencia): ?>

                <section class="edit-layout">

                    <!-- INFORMACIÓN DE LA INCIDENCIA -->
                    <div class="edit-context">

                        <div class="edit-context-icon botond">✎</div>

                        <div class="edit-context-copy">

                            <span class="edit-context-label">
                                EDITANDO REGISTRO
                            </span>

                            <strong>
                                Incidencia #<?= (int) $incidencia['id'] ?>
                            </strong>

                            <p>
                                Los cambios se aplicarán a esta incidencia cuando pulses «Guardar cambios».
                            </p>

                        </div>

                        <span class="edit-context-status">
                            <span></span> Edición activa
                        </span>

                    </div>


                    <!-- FORMULARIO -->
                    <section class="form-card edit-form-card">

                        <div class="form-intro">

                            <div class="form-intro-icon botond">☷</div>

                            <div>
                                <h2>Información de la incidencia</h2>

                                <p>
                                    Modifica los campos que necesites.
                                    Los datos actuales aparecen precargados.
                                </p>
                            </div>

                        </div>


                        <?php if ($error !== ''): ?>

                            <p class="badge priority-high">
                                <?= e($error) ?>
                            </p>

                        <?php endif; ?>


                        <form method="POST" action="editar.php">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int) $incidencia['id'] ?>">

                            <div class="form-grid">

                                <div class="form-field form-field-full">

                                    <label for="problema">Problema</label>

                                    <input
                                        id="problema"
                                        type="text"
                                        name="problema"
                                        value="<?= e($incidencia['problema']) ?>"
                                        placeholder="Describe brevemente el problema"
                                        required>

                                    <p class="form-help">
                                        Describe qué ha ocurrido para facilitar su seguimiento.
                                    </p>

                                </div>


                                <div class="form-field form-field-full">

                                    <label for="ubicacion">Ubicación</label>

                                    <input
                                        id="ubicacion"
                                        type="text"
                                        name="ubicacion"
                                        value="<?= e($incidencia['ubicacion']) ?>"
                                        placeholder="Ej. Aula 2, laboratorio o recepción"
                                        required>

                                </div>


                                <div class="form-field">

                                    <label for="prioridad">Prioridad</label>

                                    <select id="prioridad" name="prioridad" required>

                                        <option value="Baja"
                                            <?= $incidencia['prioridad'] === 'Baja' ? 'selected' : '' ?>>
                                            Baja
                                        </option>

                                        <option value="Media"
                                            <?= $incidencia['prioridad'] === 'Media' ? 'selected' : '' ?>>
                                            Media
                                        </option>

                                        <option value="Alta"
                                            <?= $incidencia['prioridad'] === 'Alta' ? 'selected' : '' ?>>
                                            Alta
                                        </option>

                                    </select>

                                </div>


                                <div class="form-field">

                                    <label for="situacion">Situación</label>

                                    <select id="situacion" name="situacion" required>

                                        <option value="Abierta"
                                            <?= $incidencia['situacion'] === 'Abierta' ? 'selected' : '' ?>>
                                            Abierta
                                        </option>

                                        <option value="En proceso"
                                            <?= $incidencia['situacion'] === 'En proceso' ? 'selected' : '' ?>>
                                            En proceso
                                        </option>

                                        <option value="Resuelta"
                                            <?= $incidencia['situacion'] === 'Resuelta' ? 'selected' : '' ?>>
                                            Resuelta
                                        </option>

                                    </select>

                                </div>

                            </div>


                            <div class="form-actions">

                                <button class="btn btn-primary" type="submit">
                                    ✓ Guardar cambios
                                </button>

                                <a class="btn btn-secondary" href="listar.php">
                                    Cancelar
                                </a>

                            </div>

                        </form>

                    </section>


                    <p class="edit-footnote">
                        <span>ⓘ</span>
                        Comprueba que la información sea correcta antes de guardar los cambios.
                    </p>

                </section>

            <?php else: ?>

                <!-- INCIDENCIA NO ENCONTRADA -->
                <section class="detail-panel detail-not-found">

                    <div class="empty-icon">!</div>

                    <h2>No se ha encontrado la incidencia</h2>

                    <p>
                        El identificador no es válido o la incidencia ya no existe.
                    </p>

                    <a class="btn btn-primary" href="listar.php">
                        ← Volver al listado
                    </a>

                </section>

            <?php endif; ?>

        </main>

    </div>

</body>

</html>