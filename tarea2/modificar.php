<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a href="menu.php">Volver al menu</a>
    
    <form method="POST" action="">

        <h2>Buscar por identificación</h2><br><br>
        <input type="text" name="buscar" required><br>
        <h2>Cambiar nombre </h2><br><br>
        <input type="text" name="nombre" required><br>
        <h2>Cambiar contraseña</h2><br><br>
        <input type="text" name="contr" required><br>
        <h2>Cambiar rol</h2><br><br>
        <input type="text" name="rol" required><br>
        <button type="submit">Buscar</button>
    </form>
</body>

<?php

require_once('alertas.php');
require('conect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
    $nombre=trim($_POST["nombre"] ??"");
    $contr=trim($_POST["contr"] ??"");
    $rol=trim($_POST["rol"] ??"");
    $conexion = conectar();
    file_put_contents(
        "historial.txt",
        date("H:i") . " registro" . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
    $sql = "UPDATE usuarios SET nombre_usuario=?,contr=?,rol=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $nombre,$contr,$rol,$buscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($buscar === "") {
        alerta_campo();
    } else {
        echo "Usuario modificado";
        }
       
    }




?>

</html>