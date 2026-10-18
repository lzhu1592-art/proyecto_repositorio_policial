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

        <h2>Buscar por ID</h2><br><br>
        <input type="text" name="buscar"><br>
        <button type="submit">Buscar</button>
    </form>
</body>

<?php

require_once('alertas.php');
require('conect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
    $conexion = conectar();
    file_put_contents(
        "historial.txt",
        date("H:i") . " Busqueda usuario" . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
    $sql = "SELECT * FROM usuarios WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $buscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($buscar === "") {
        alerta_campo();
    } else {
        while ($fila = $resultado->fetch_assoc()) {
            echo "Nombre: " . $fila["nombre_usuario"] . "<br>";
            echo "Contraseña: " . $fila["contr"] . "<br>";
            echo "Rol: " . $fila["rol"] .  "<br><br><br>";
        }
       
    }


}

?>

</html>