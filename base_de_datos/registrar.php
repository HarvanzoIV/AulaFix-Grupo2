<?php

require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Recoger los datos del formulario register.php
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = $_POST['apellidos'] ?? '';
    $correo = trim($_POST['correo'] ?? '');
    $usuario = trim($_POST['user'] ?? '');
    $key1 = trim($_POST['key1'] ?? '');
    $key2 = trim($_POST['key2'] ?? '');
    //valida las 2 contraseñas 
    if ($key1 !== $key2) {
        $_SESSION['error']= "Las contraseñas no coinciden.";
        header("Location: ../register.php");
        exit();
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
        $_SESSION['error']= "Este correo electrónico ya está registrado.";
        header("Location: ../register.php");
    }

    $sqlInsert = "INSERT INTO usuarios (nombre,apellidos,correo,usuario,contrasena)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $cn->prepare($sqlInsert);
    $stmt->bind_param("sssss", $nombre, $apellidos, $correo, $usuario, $key1);
    if ($stmt->execute()) {

        $_SESSION['success'] = 'Cuenta creada correctamente. Ya puedes iniciar sesión.';
        header('Location: ../login.php');
        exit;

    } else {
        $_SESSION['error']= "Error en el base de datos.";
        header("Location: ../register.php");
        exit();
    }
}
?>