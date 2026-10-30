<?php

session_start();
$usuario=$_SESSION['usuario']??'';

if(empty($usuario)){
    header("Location: login.php");
}
require 'includes/header.php';
require 'base_de_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $problema = $_POST['problema'];
    $ubicacion = $_POST['ubicacion'];
    $prioridad = $_POST['prioridad'];
    $situacion = $_POST['situacion'];

    $sql = "UPDATE incidencias
            SET problema = ?,
                ubicacion = ?,
                prioridad = ?,
                situacion = ?
            WHERE id = ?";

    $stmt = $cn->prepare($sql);
    $stmt->bind_param(
        "ssssi",
        $problema,
        $ubicacion,
        $prioridad,
        $situacion,
        $id
    );

    $stmt->execute();

    header("Location: listar.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM incidencias WHERE id = ?";
$stmt = $cn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$incidencia = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar incidencia</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Editar incidencia</h1>

    <form method="POST">

        <input type="hidden" name="id" value="<?= $incidencia['id'] ?>">

        <label>Problema:</label>
        <input
            type="text"
            name="problema"
            value="<?= htmlspecialchars($incidencia['problema']) ?>"
            required>

        <br><br>

        <label>Ubicación:</label>
        <input
            type="text"
            name="ubicacion"
            value="<?= htmlspecialchars($incidencia['ubicacion']) ?>"
            required>

        <br><br>

        <label>Prioridad:</label>
        <select name="prioridad">
            <option <?= $incidencia['prioridad'] == 'Baja' ? 'selected' : '' ?>>Baja</option>
            <option <?= $incidencia['prioridad'] == 'Media' ? 'selected' : '' ?>>Media</option>
            <option <?= $incidencia['prioridad'] == 'Alta' ? 'selected' : '' ?>>Alta</option>
        </select>

        <br><br>

        <label>Situación:</label>
        <select name="situacion">
            <option <?= $incidencia['situacion'] == 'Abierta' ? 'selected' : '' ?>>Abierta</option>
            <option <?= $incidencia['situacion'] == 'En proceso' ? 'selected' : '' ?>>En proceso</option>
            <option <?= $incidencia['situacion'] == 'Resuelta' ? 'selected' : '' ?>>Resuelta</option>
        </select>

        <br><br>

        <input type="submit" value="Actualizar">

    </form>

</body>

</html>