<?php

require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $usuario = trim($_POST['user'] ?? '');
    $key1 = trim($_POST['key1'] ?? '');
    $key2 = trim($_POST['key2'] ?? '');

    // Validar contraseñas
    if ($key1 !== $key2) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
        header("Location: ../register.php");
        exit();
    }

    try {

        // Iniciar transacción
        $cn->begin_transaction();

        // Comprobar usuario
        $sqlUsuario = "SELECT id FROM usuarios WHERE usuario = ?";
        $stmt = $cn->prepare($sqlUsuario);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            throw new Exception("Este usuario ya está registrado.");
        }

        $stmt->close();

        // Comprobar correo
        $sqlCorreo = "SELECT id FROM usuarios WHERE correo = ?";
        $stmt = $cn->prepare($sqlCorreo);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            throw new Exception("Este correo electrónico ya está registrado.");
        }

        $stmt->close();

        // Hash de contraseña
        $hash = password_hash($key1, PASSWORD_DEFAULT);

        // Insertar usuario
        $sqlInsert = "
            INSERT INTO usuarios
            (nombre, apellidos, correo, usuario, contrasena)
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt = $cn->prepare($sqlInsert);
        $stmt->bind_param(
            "sssss",
            $nombre,
            $apellidos,
            $correo,
            $usuario,
            $hash
        );

        $stmt->execute();

        // Confirmar transacción
        $cn->commit();

        $_SESSION['success'] =
            "Cuenta creada correctamente. Ya puedes iniciar sesión.";

        header("Location: ../login.php");
        exit();

    } catch (Exception $e) {

        // Deshacer cambios
        $cn->rollback();

        $_SESSION['error'] = $e->getMessage();

        header("Location: ../register.php");
        exit();
    }
}
?>