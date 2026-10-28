<?php

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
        die("No existe el usuario.");
    }

    $fila = $resultado->fetch_assoc();

    if ($key == $fila['contrasena']) {

        echo "Login correcto";

        session_start();

        
        $_SESSION['usuario'] = $usuario;
        $_SESSION['key']=$fila['contrasena'];

        header("Location: principal.php");
        exit();


    } else {

        die("Contraseña incorrecta");

    }

}





?>