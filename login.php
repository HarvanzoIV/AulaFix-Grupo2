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

    <title>AulaFix | Iniciar sesión</title>

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

        <button
            class="theme-toggle"
            id="theme-toggle"
            type="button"
            aria-pressed="false">

            <span class="theme-icon" aria-hidden="true">☾</span>
            <span>Modo oscuro</span>

        </button>

    </header>


    <!-- CONTENIDO PRINCIPAL -->
    <main class="auth-container">

        <section class="auth-card">

            <!-- PRESENTACIÓN -->
            <div class="auth-card-header">

                <div class="auth-icon">
                    <span aria-hidden="true">↗</span>
                </div>

                <p class="auth-eyebrow">
                    BIENVENIDO A AULAFIX
                </p>

                <h1>Inicia sesión</h1>

                <p class="auth-description">
                    Accede a tu cuenta para gestionar y consultar
                    las incidencias de tu centro.
                </p>

            </div>


            <!-- FORMULARIO -->
            <form
                method="POST"
                action="base_de_datos/cuenta.php"
                class="auth-form">

                <div class="auth-field">

                    <label for="user">Usuario</label>

                    <div class="auth-input-wrapper">

                        <span class="auth-input-icon" aria-hidden="true">♙</span>

                        <input
                            type="text"
                            id="user"
                            name="user"
                            placeholder="Introduce tu usuario"
                            autocomplete="username"
                            required>

                    </div>

                </div>


                <div class="auth-field">

                    <label for="key">Contraseña</label>

                    <div class="auth-input-wrapper">

                        <span class="auth-input-icon" aria-hidden="true">⌑</span>

                        <input
                            type="password"
                            id="key"
                            name="key"
                            placeholder="Introduce tu contraseña"
                            autocomplete="current-password"
                            required>

                    </div>

                </div>
            

                <button type="submit" class="auth-submit">

                    Iniciar sesión

                    <span aria-hidden="true">→</span>

                </button>

            </form>


            <!-- ENLACE DE REGISTRO -->
            <div class="auth-divider"></div>

            <div class="auth-register">

                <p>¿Todavía no tienes una cuenta?</p>

                <a href="register.php">
                    Crear una cuenta
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
