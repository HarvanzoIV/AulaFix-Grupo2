<?php

require 'db.php';

// Comprobar que el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoger datos
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $usuario = trim($_POST['user'] ?? '');
    $key1 = trim($_POST['key1'] ?? '');
    $key2 = trim($_POST['key2'] ?? '');

    if ($key1 == $key2) {
        $stmt = $cn->prepare("SELECT correo FROM usuarios WHERE correo=?");
        $stmt->bind_param("s", $email);
//FALTA TODO LO ABAJO

        if ($stmt->affected_rows == 0) {
            $stmt2 = $cn->prepare(
                'INSERT INTO alumnos(nombre,email,nota) VALUES (?,?,?)'
            );
            $stmt2->bind_param('ssd', $nombre, $email, $nota);
            $stmt2->execute();
        }

    }
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