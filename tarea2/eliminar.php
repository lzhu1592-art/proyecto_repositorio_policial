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
        <input type="number" name="buscar"><br>
        <button type="submit">Buscar</button>
    </form>
</body>

<?php

require_once('alertas.php');
require('conect.php');
//Aqui directamente con la base de datos, no lo hize como si fuera localmente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
    $en = false;
    $conexion = conectar();
    file_put_contents(
        "historial.txt",
        date("H:i") . " registro" . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
    $sql = "DELETE FROM usuarios WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $buscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($buscar === "") {
        alerta_campo();
    } else {
        echo "Usuario eliminado";
       
    }


}

?>

</html>