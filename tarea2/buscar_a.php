<?php
session_start();

// Evitar que el navegador guarde la página en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Si falta usuario O contraseña en la sesión, error
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["contra"])) {
    header("Location: login_error.php");
    exit;
}

require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
    if($buscar == ""){
        alerta_campo(); 
    }else{
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
    


}
    
/*
//Aqui lo hare usando de base de datos el bd.php

require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/bd.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
     file_put_contents(
        "historial.txt",
        date("H:i") . " Busqueda usuario" . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
    if (!isset($_SESSION["usuarios"])) {
            $_SESSION["usuarios"] = $usuarios;
    }
     $_SESSION["usuarios"][$nombre] = [
             "contra" => $contra,
             "rol" => $rol
         ];

}
         <?php foreach ($_SESSION["usuarios"] as $nombre => $datos): ?>

            <h3><?= htmlspecialchars($nombre) ?></h3>

            <p>Contraseña: <?= htmlspecialchars($datos["contra"]) ?></p>

            <p>Rol: <?= htmlspecialchars($datos["rol"]) ?></p>

            <hr>

        <?php endforeach; ?>

         */
?>


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

        <h2>Buscar por ID/Nombre</h2><br><br>
        <input type="text" name="buscar"><br>
        <button type="submit">Buscar</button>
    </form>
</body>




</html>