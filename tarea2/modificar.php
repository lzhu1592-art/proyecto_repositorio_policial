<?php
session_start();

// Evitar que el navegador guarde la página en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Si falta usuario O contraseña en la sesión, error
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["contra"])) {
    header("Location: funciones/login_error.php");
    exit;
}

/*
Aqui lo hare usando bd.php como base de datos
session_start();
require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
    $nombre=trim($_POST["nombre"] ??"");
    $contr=trim($_POST["contr"] ??"");
    $rol=trim($_POST["rol"] ??"");
   
   
    if ($buscar === "") {
        alerta_campo();
    } else {
        $_SESSION["usuarios"][$buscar] = [
            "nombre" => $nombre,
            "contr" => $contr,
            "rol" => $rol
        ];
         file_put_contents(
        "historial.txt",
        date("H:i") . " modificar usuario" . PHP_EOL,
        FILE_APPEND | LOCK_EX

        echo "Usuario modificado";
    );
    }

*/

require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/conect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
    $nombre=trim($_POST["nombre"] ??"");
    $contr=trim($_POST["contr"] ??"");
    $rol=trim($_POST["rol"] ??"");
    $conexion = conectar();
    file_put_contents(
        "historial.txt",
        date("H:i") . " modificar usuario" . PHP_EOL,
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

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar usuario</title>
</head>

<body>
    <a href="menu.php">Volver al menu</a>
    
    <form method="POST" action="">

        <h2>Buscar por identificación o nombre</h2><br><br>
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



</html>