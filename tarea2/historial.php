
<?php
$archivo = __DIR__ . "/historial.txt";
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
