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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de actividad</title>
</head>
<body>

    <h1>Historial de actividad</h1>

    <a href="menu.php">Volver al menú</a>

    <hr>

    <?php
        $archivo = __DIR__ . "/historial.txt";
    if (file_exists($archivo)) {
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lineas as $linea) {
            echo htmlspecialchars($linea) . "<br>";
        }
    } else {
        echo "Todavía no hay acciones registradas.";
    }
    ?>

</body>
</html>
