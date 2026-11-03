<?php
session_start();
if(isset($_SESSION['usuario'])){
    header("Location: listar.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111a2e">

    <title>AulaFix | Crear cuenta</title>

    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>

<body class="auth-page">

    <!-- CABECERA -->
    <header class="auth-topbar">

        <a href="login.php" class="brand auth-brand">

            <span class="brand-mark">A</span>

            <span class="brand-copy">
                AulaFix
                <small>INCIDENCE MANAGEMENT</small>
            </span>

        </a>

        <div class="auth-topbar-actions">

            <a href="login.php" class="btn btn-secondary">
                ← Volver
            </a>

            <button class="theme-toggle" id="theme-toggle" type="button" aria-pressed="false">

                <span class="theme-icon" aria-hidden="true">☾</span>
                <span>Modo oscuro</span>

            </button>

        </div>

    </header>


    <!-- CONTENIDO PRINCIPAL -->
    <main class="auth-container">

        <section class="auth-card auth-card-register">

            <!-- PRESENTACIÓN -->
            <div class="auth-card-header">

                <div class="auth-icon">
                    <span aria-hidden="true">✚</span>
                </div>

                <p class="auth-eyebrow">
                    ÚNETE A AULAFIX
                </p>

                <h1>Crear nueva cuenta</h1>

                <p class="auth-description">
                    Regístrate para empezar a gestionar las incidencias
                    de tu centro.
                </p>

            </div>


            <!-- FORMULARIO DE REGISTRO -->
            <form method="POST" action="base_de_datos/registrar.php" class="auth-form auth-form-register">


                <!-- NOMBRE -->
                <div class="auth-field">

                    <label for="nombre">Nombre</label>

                    <div class="auth-input-wrapper">

                        <span class="auth-input-icon" aria-hidden="true">♙</span>

                        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" autocomplete="given-name"
                            required>

                    </div>

                </div>


                <!-- APELLIDOS -->
                <div class="auth-field">

                    <label for="apellidos">Apellidos</label>

                    <div class="auth-input-wrapper">

                        <span class="auth-input-icon" aria-hidden="true">♙</span>

                        <input type="text" id="apellidos" name="apellidos" placeholder="Tus apellidos"
                            autocomplete="family-name" required>

                    </div>

                </div>


                <!-- CORREO -->
                <div class="auth-field auth-field-full">

                    <label for="correo">Correo electrónico</label>

                    <div class="auth-input-wrapper">

                        <span class="auth-input-icon" aria-hidden="true">✉</span>

                        <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com"
                            autocomplete="email" required>

                    </div>

                </div>


                <!-- USUARIO -->
                <div class="auth-field auth-field-full">

                    <label for="user">Nombre de usuario</label>

                    <div class="auth-input-wrapper">

                        <span class="auth-input-icon" aria-hidden="true">♙</span>

                        <input type="text" id="user" name="user" placeholder="Elige un nombre de usuario"
                            autocomplete="username" required>

                    </div>

                </div>


                <!-- CONTRASEÑA -->
                <div class="auth-field">

                    <label for="key1">Contraseña</label>

                    <div class="auth-input-wrapper">

                        <span class="auth-input-icon" aria-hidden="true">⌑</span>

                        <input type="password" id="key1" name="key1" placeholder="Contraseña"
                            autocomplete="new-password" required>

                    </div>

                </div>


                <!-- CONFIRMAR CONTRASEÑA -->
                <div class="auth-field">

                    <label for="key2">Confirmar contraseña</label>

                    <div class="auth-input-wrapper">

                        <span class="auth-input-icon" aria-hidden="true">⌑</span>

                        <input type="password" id="key2" name="key2" placeholder="Repite la contraseña"
                            autocomplete="new-password" required>

                    </div>

                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-registro">
                        <span class="icono">❌</span>
                        <span><?= htmlspecialchars($_SESSION['error']) ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- BOTÓN REGISTRAR -->
                <button type="submit" class="auth-submit auth-submit-full">

                    Crear mi cuenta

                    <span aria-hidden="true">→</span>

                </button>

            </form>


            <!-- ENLACE AL LOGIN -->
            <div class="auth-divider"></div>

            <div class="auth-register">

                <p>¿Ya tienes una cuenta?</p>

                <a href="login.php">
                    Iniciar sesión
                    <span aria-hidden="true">→</span>
                </a>

            </div>

        </section>


        <!-- PIE DE PÁGINA -->
        <p class="auth-footer">
            AULAFIX © 2026 · GESTIÓN INTELIGENTE DE INCIDENCIAS
        </p>

    </main>

</body>

</html>