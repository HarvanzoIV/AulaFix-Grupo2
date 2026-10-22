<?php

require 'db.php';

// Comprobar que el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoger datos
    $problema = trim($_POST['problema']);
    $ubicacion = trim($_POST['ubicacion']);
    $prioridad = $_POST['prioridad'];
    $situacion = $_POST['situacion'];

    // Consulta SQL
    $sql = "INSERT INTO incidencias
            (problema, ubicacion, prioridad, situacion)
            VALUES (?, ?, ?, ?)";

    // Preparar consulta
    $stmt = $cn->prepare($sql);

    // Sustituir parámetros
    $stmt->bind_param(
        "ssss",
        $problema,
        $ubicacion,
        $prioridad,
        $situacion
    );

    // Ejecutar INSERT
    $stmt->execute();

    echo "Incidencia guardada correctamente";
}
?>