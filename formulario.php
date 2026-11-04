<?php
session_start();

$usuario = $_SESSION['usuario'] ?? '';
if (empty($usuario)) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111a2e">
    <title>AulaFix | Nueva incidencia</title>
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
            <a href="listar.php">
                <span class="nav-icon" aria-hidden="true">▦</span>
                <span>Incidencias</span>
            </a>
            <a class="active" href="formulario.php" aria-current="page">
                <span class="nav-icon" aria-hidden="true">＋</span>
                <span>Nueva incidencia</span>
            </a>
        </nav>
        
        <div class="sidebar-bottom">
    <div class="user-panel">

        <div class="user-greeting">
            Hola <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋
        </div>

        <form action="includes/logout.php" method="post">
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
                <p class="page-kicker">AULAFIX / REGISTRO</p>
                <h1>Nueva incidencia</h1>
                <p class="page-description">Completa los datos para registrar una nueva solicitud.</p>
            </div>
            <div class="topbar-actions">
                <button class="theme-toggle" id="theme-toggle" type="button" aria-pressed="false">
                    <span class="theme-icon" aria-hidden="true">☾</span><span>Modo oscuro</span>
                </button>
                <a class="btn btn-secondary" href="listar.php">← Volver al listado</a>
            </div>
        </header>

        <section class="form-card">
            <div class="form-intro">
                <div class="form-intro-icon" aria-hidden="true">＋</div>
                <div>
                    <h2>Información de la incidencia</h2>
                    <p>Los campos marcados son necesarios para poder tramitarla.</p>
                </div>
            </div>

            <!-- Se mantienen el método, el destino y los nombres originales del formulario. -->
            <form method="POST" action="base_de_datos/guardar.php">
                <div class="form-grid">
                    <div class="form-field form-field-full">
                        <label for="problema">Problema</label>
                        <input id="problema" type="text" name="problema" placeholder="Describe brevemente el problema" required>
                        <p class="form-help">Explica qué ha ocurrido para facilitar su resolución.</p>
                    </div>

                    <div class="form-field form-field-full">
                        <label for="ubicacion">Ubicación</label>
                        <input id="ubicacion" type="text" name="ubicacion" placeholder="Ej. Aula 2, laboratorio o recepción" required>
                    </div>

                    <div class="form-field">
                        <label for="prioridad">Prioridad</label>
                        <select id="prioridad" name="prioridad" required>
                            <option value="">Seleccione una opción</option>
                            <option>Baja</option>
                            <option>Media</option>
                            <option>Alta</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="situacion">Situación</label>
                        <select id="situacion" name="situacion" required>
                            <option value="">Seleccione una opción</option>
                            <option>Abierta</option>
                            <option>En proceso</option>
                            <option>Resuelta</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="submit" value="Guardar incidencia">
                    <a class="btn btn-secondary" href="listar.php">Cancelar</a>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>
