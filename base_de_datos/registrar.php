<?php

require 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Recoger los datos del formulario register.php
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $usuario = trim($_POST['user'] ?? '');
    $key1 = trim($_POST['key1'] ?? '');
    $key2 = trim($_POST['key2'] ?? '');
    //valida las 2 contraseñas 
    if ($key1 !== $key2) {
        die("Las contraseñas no coincide");
        //si falla entra esta seccion
    }

    $sqlUsuario = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt = $cn->prepare($sqlUsuario);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        die("El nombre de usuario ya existe");
    }

    $sqlCorreo = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt = $cn->prepare($sqlCorreo);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        die("El correo electrónico ya existe");
    }

    $sqlInsert = "INSERT INTO usuarios (nombre,apellidos,correo,usuario,contrasena)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $cn->prepare($sqlInsert);
    $stmt->bind_param("sssss", $nombre, $apellidos, $correo, $usuario, $key1);
    if ($stmt->execute()) {

        // Redirigir a otra página
        header("Location: ../login.php");
        exit();

    } else {
        echo "Error al guardar: " . $stmt->error;
    }
}
?>