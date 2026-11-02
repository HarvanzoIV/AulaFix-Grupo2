/* ==========================================================
   AULAFIX · JAVASCRIPT PRINCIPAL
   1. Tema claro y oscuro mediante cookies.
   2. Buscador dinámico de incidencias.
   ========================================================== */

(() => {
  "use strict";

  /* ======================================================
       1. SISTEMA DE TEMA CLARO / OSCURO
       ====================================================== */

  const COOKIE_NAME = "aulafix_theme";
  const COOKIE_MAX_AGE = 60 * 60 * 24 * 365;

  // Leer cookie
  function readCookie(name) {
    const prefix = name + "=";

    const cookie = document.cookie
      .split("; ")
      .find((item) => item.startsWith(prefix));

    if (!cookie) return null;

    try {
      return decodeURIComponent(cookie.substring(prefix.length));
    } catch {
      return null;
    }
  }

  // Guardar cookie durante un año
  function saveCookie(theme) {
    const secure = window.location.protocol === "https:" ? "; Secure" : "";

    document.cookie =
      COOKIE_NAME +
      "=" +
      encodeURIComponent(theme) +
      "; Max-Age=" +
      COOKIE_MAX_AGE +
      "; Path=/; SameSite=Lax" +
      secure;
  }

  // Aplicar tema
  function applyTheme(theme) {
    const selectedTheme = theme === "dark" ? "dark" : "light";

    document.documentElement.dataset.theme = selectedTheme;

    const toggle = document.getElementById("theme-toggle");

    if (toggle) {
      const isDark = selectedTheme === "dark";

      toggle.setAttribute("aria-pressed", String(isDark));

      toggle.setAttribute(
        "aria-label",
        isDark ? "Activar modo claro" : "Activar modo oscuro",
      );

      toggle.innerHTML = isDark
        ? '<span class="theme-icon" aria-hidden="true">☀</span><span>Modo claro</span>'
        : '<span class="theme-icon" aria-hidden="true">☾</span><span>Modo oscuro</span>';
    }
  }

  // Inicializar tema
  function initTheme() {
    const savedTheme = readCookie(COOKIE_NAME);

    applyTheme(savedTheme === "dark" ? "dark" : "light");

    const toggle = document.getElementById("theme-toggle");

    if (toggle) {
      toggle.addEventListener("click", () => {
        const currentTheme = document.documentElement.dataset.theme;

        const nextTheme = currentTheme === "dark" ? "light" : "dark";

        applyTheme(nextTheme);
        saveCookie(nextTheme);
      });
    }
  }

  /* ======================================================
       2. BUSCADOR DE INCIDENCIAS
       ====================================================== */

  // Normalizar texto para ignorar mayúsculas y tildes
  function normalizarTexto(valor) {
    return String(valor || "")
      .toLocaleLowerCase("es")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .trim();
  }

  // Inicializar buscador
  function initSearch() {
    const buscador = document.getElementById("buscar");
    const tabla = document.getElementById("tablaIncidencias");

    // Si estamos en otra página, no hacemos nada
    if (!buscador || !tabla) {
      return;
    }

    const filas = Array.from(tabla.querySelectorAll("tbody tr"));

    const aviso = document.getElementById("sinResultados");

    const contador = document.getElementById("resultadoBusqueda");

    // Filtrar incidencias
    function filtrarIncidencias() {
      const termino = normalizarTexto(buscador.value);

      let visibles = 0;

      filas.forEach(function (fila) {
        // Utilizar los datos preparados en PHP.
        // Como alternativa, buscar en las cuatro columnas.
        const contenido = normalizarTexto(
          fila.getAttribute("data-search") ||
            Array.from(fila.cells)
              .slice(0, 4)
              .map((celda) => celda.textContent)
              .join(" "),
        );

        const coincide = contenido.includes(termino);

        fila.hidden = !coincide;

        if (coincide) {
          visibles++;
        }
      });

      // Mostrar mensaje cuando no hay coincidencias
      if (aviso) {
        aviso.hidden = visibles !== 0;
      }

      // Actualizar contador de resultados
      if (contador) {
        if (termino === "") {
          contador.textContent = "";
        } else {
          contador.textContent =
            visibles +
            (visibles === 1
              ? " incidencia encontrada"
              : " incidencias encontradas");
        }
      }
    }

    // Detectar escritura y borrado
    buscador.addEventListener("input", filtrarIncidencias);

    // Compatibilidad con el botón de borrar de input type search
    buscador.addEventListener("search", filtrarIncidencias);
  }

  /* ======================================================
       3. INICIALIZACIÓN
       ====================================================== */

  function iniciarAulaFix() {
    initTheme();
    initSearch();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", iniciarAulaFix);
  } else {
    iniciarAulaFix();
  }
})();

