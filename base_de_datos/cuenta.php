<?php
session_start();
require 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['user'] ?? '');
    $key = trim($_POST['key'] ?? '');

    $sql = "SELECT id, contrasena FROM usuarios WHERE usuario = ?";
    $stmt = $cn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        $_SESSION['error']= "El usuario o la contraseña introducidos no son correctos.";
        header("Location: ../login.php");
        exit();
    }

    $fila = $resultado->fetch_assoc();

    if (password_verify($key, $fila['contrasena'])) {

        $_SESSION['usuario'] = $usuario;
        $_SESSION['id_usuario'] = $fila['id'];

        header("Location: ../listar.php");
        exit();
    } else {

        $_SESSION['error']= "El usuario o la contraseña introducidos no son correctos.";
        header("Location: ../login.php");
        exit();
    }
}

?>